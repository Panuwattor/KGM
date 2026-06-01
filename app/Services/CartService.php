<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Collection;

class CartService
{
    private function customer() { return auth('customer'); }

    public function getCartItems(): Collection
    {
        if ($this->customer()->check()) {
            return Cart::where('customer_id', $this->customer()->id())->with(['product.images', 'variant'])->get();
        }
        return Cart::where('session_id', session()->getId())->with(['product.images', 'variant'])->get();
    }

    public function addItem(int $productId, int $quantity = 1, ?int $variantId = null): Cart
    {
        $conditions = $this->customer()->check()
            ? ['customer_id' => $this->customer()->id()]
            : ['session_id' => session()->getId()];

        $conditions['product_id'] = $productId;
        $conditions['variant_id'] = $variantId;

        $cart = Cart::firstOrCreate($conditions, ['quantity' => 0]);
        $cart->increment('quantity', $quantity);

        if ($this->customer()->check()) {
            $cart->session_id = null;
        }
        $cart->save();

        return $cart;
    }

    public function updateQuantity(int $cartId, int $quantity): void
    {
        $cart = $this->findCartItem($cartId);
        if ($cart) {
            $quantity <= 0 ? $cart->delete() : $cart->update(['quantity' => $quantity]);
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

    public function mergeSessionCart(): void
    {
        if (!$this->customer()->check()) return;
        $sessionId = session()->getId();
        $sessionItems = Cart::where('session_id', $sessionId)->get();

        foreach ($sessionItems as $item) {
            $existing = Cart::where('customer_id', $this->customer()->id())
                ->where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
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

    public function getShippingFee(float $subtotal, ?string $couponCode = null): float
    {
        $freeThreshold = (float) env('FREE_SHIPPING_AMOUNT', 1000);
        if ($subtotal >= $freeThreshold) return 0;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $coupon->type === 'free_shipping') return 0;
        }

        return 50;
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
