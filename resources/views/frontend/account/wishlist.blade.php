@extends('layouts.app')
@section('title', 'รายการโปรด')
@section('content')
<div class="container acc-wrap">
    <div class="acc-layout">
        @include('frontend.account._sidebar')
        <div>
            <h2 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;"><i class="bi bi-heart-fill" style="color:#e74c3c;"></i> รายการโปรด</h2>
            @if($wishlists->isEmpty())
            <div class="acc-card" style="text-align:center;padding:64px 28px;">
                <i class="bi bi-heart" style="font-size:48px;color:#ddd;display:block;margin-bottom:16px;"></i>
                <p style="color:#aaa;margin-bottom:16px;">ยังไม่มีสินค้าในรายการโปรด</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">เลือกสินค้า</a>
            </div>
            @else
            <div class="grid grid-4">
                @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                @if($product)@include('components.product-card')@endif
                @endforeach
            </div>
            <div class="pagination">{{ $wishlists->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
