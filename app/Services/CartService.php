<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ShippingRate;
use Illuminate\Support\Collection;

class CartService
{
    private function customer() { return auth('customer'); }

    public function getCartItems(): Collection
    {
        if ($this->customer()->check()) {
            return Cart::where('customer_id', $this->customer()->id())
                ->with(['product.images', 'product.category', 'variant'])
                ->get();
        }
        return Cart::where('session_id', session()->getId())
            ->with(['product.images', 'product.category', 'variant'])
            ->get();
    }

    public function addItem(
        int $productId,
        int $quantity = 1,
        ?int $variantId = null,
        ?float $flashSalePrice = null,
        bool $embroidery = false,
        ?string $embroideryText = null,
        float $embroideryPrice = 0,
    ): Cart {
        $owner = $this->customer()->check()
            ? ['customer_id' => $this->customer()->id()]
            : ['session_id' => session()->getId()];

        // จำนวนสต๊อกที่มีจริง: ถ้ามีไซส์ (variant) ใช้สต๊อกของไซส์นั้น มิฉะนั้นใช้สต๊อกสินค้า
        $availableStock = $this->availableStock($productId, $variantId);

        // งานปักแต่ละชิ้นมีข้อความเฉพาะตัว → แยกเป็นรายการใหม่เสมอ ไม่รวมกับของเดิม
        if ($embroidery) {
            $quantity = max(1, min($quantity, $availableStock));
            $cart = Cart::create($owner + [
                'product_id'       => $productId,
                'variant_id'       => $variantId,
                'quantity'         => $quantity,
                'flash_sale_price' => $flashSalePrice,
                'embroidery'       => true,
                'embroidery_text'  => $embroideryText,
                'embroidery_price' => $embroideryPrice,
            ]);
            return $cart;
        }

        // สินค้าปกติ (ไม่ปัก) → รวมจำนวนกับรายการเดิมที่ไม่มีงานปัก
        $conditions = $owner + [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'embroidery' => false,
        ];

        $cart = Cart::firstOrCreate($conditions, ['quantity' => 0]);
        // ไม่ให้จำนวนรวมในตะกร้าเกินสต๊อกที่มีอยู่
        $newQty = min($cart->quantity + $quantity, $availableStock);
        $cart->quantity = max(1, $newQty);

        if ($this->customer()->check()) {
            $cart->session_id = null;
        }

        if ($flashSalePrice !== null) {
            $cart->flash_sale_price = $flashSalePrice;
        }

        $cart->save();

        return $cart;
    }

    /** จำนวนสต๊อกที่สั่งซื้อได้: ใช้สต๊อกของไซส์ (variant) ถ้ามี ไม่งั้นใช้สต๊อกสินค้า */
    private function availableStock(int $productId, ?int $variantId): int
    {
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if ($variant) {
                return max(0, (int) $variant->stock_quantity);
            }
        }
        $product = \App\Models\Product::find($productId);
        return $product ? max(0, (int) $product->stock_quantity) : 0;
    }

    public function updateQuantity(int $cartId, int $quantity): void
    {
        $cart = $this->findCartItem($cartId);
        if ($cart) {
            if ($quantity <= 0) {
                $cart->delete();
                return;
            }
            // จำกัดไม่ให้ปรับจำนวนเกินสต๊อกที่มีอยู่
            $quantity = min($quantity, $this->availableStock($cart->product_id, $cart->variant_id));
            $cart->update(['quantity' => max(1, $quantity)]);
        }
    }

    public function removeItem(int $cartId): void
    {
        $this->findCartItem($cartId)?->delete();
    }

    public function clearCart(): void
    {
        if ($this->customer()->check()) {
            Cart::where('customer_id', $this->customer()->id())->delete();
        } else {
            Cart::where('session_id', session()->getId())->delete();
        }
    }

    public function mergeSessionCart(?string $sessionId = null): void
    {
        if (!$this->customer()->check()) return;
        $sessionId = $sessionId ?? session()->getId();
        $sessionItems = Cart::where('session_id', $sessionId)->get();

        foreach ($sessionItems as $item) {
            // งานปักมีข้อความเฉพาะตัว → ย้ายมาเป็นของลูกค้าตรงๆ ไม่รวมกับรายการอื่น
            $existing = $item->embroidery
                ? null
                : Cart::where('customer_id', $this->customer()->id())
                    ->where('product_id', $item->product_id)
                    ->where('variant_id', $item->variant_id)
                    ->where('embroidery', false)
                    ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                $item->update(['customer_id' => $this->customer()->id(), 'session_id' => null]);
            }
        }
    }

    public function getSubtotal(): float
    {
        return $this->getCartItems()->sum(fn($item) => $item->subtotal);
    }

    public function getShippingFee(float $subtotal, ?string $couponCode = null, ?string $stackedCouponCode = null): float
    {
        foreach (array_filter([$couponCode, $stackedCouponCode]) as $code) {
            $coupon = Coupon::where('code', $code)->first();
            if ($coupon && $coupon->isValid() && $coupon->type === 'free_shipping' && $subtotal >= $coupon->minimum_order) {
                return 0;
            }
        }
        return ShippingRate::feeForQty($this->getCount());
    }

    public function getCount(): int
    {
        return $this->getCartItems()->sum('quantity');
    }

    private function findCartItem(int $cartId): ?Cart
    {
        $query = Cart::where('id', $cartId);
        if ($this->customer()->check()) {
            $query->where('customer_id', $this->customer()->id());
        } else {
            $query->where('session_id', session()->getId());
        }
        return $query->first();
    }
}
