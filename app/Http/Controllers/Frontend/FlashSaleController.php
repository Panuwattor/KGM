<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;

class FlashSaleController extends Controller
{
    public function show(FlashSale $flashSale)
    {
        if (!$flashSale->is_active) {
            abort(404);
        }

        $flashSale->load('items.product.images', 'items.product.category', 'items.product.variants');

        return view('frontend.flash-sales.show', compact('flashSale'));
    }
}
