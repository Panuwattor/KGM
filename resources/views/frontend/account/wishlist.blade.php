@extends('layouts.app')
@section('title', 'รายการโปรด')
@section('content')
<div class="container" style="padding:32px 0 64px;">
    <h1 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-heart-fill" style="color:#e74c3c;"></i> รายการโปรด</h1>
    @if($wishlists->isEmpty())
    <div style="background:white;border-radius:20px;padding:64px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <i class="bi bi-heart" style="font-size:48px;color:#ddd;display:block;margin-bottom:16px;"></i>
        <p style="color:#aaa;">ยังไม่มีสินค้าในรายการโปรด</p>
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
@endsection
