@extends('layouts.app')
@section('title', $product->name . ' - สินค้าที่รับผลิต | KGM')
@section('meta_description', Str::limit(strip_tags($product->description ?? ('รับผลิต' . $product->name)), 155))

@section('content')

<style>
/* หัวเรื่อง: รูปหลักเล็ก */
.mps-head-img { width:80px; height:80px; object-fit:cover; border:3px solid rgba(255,255,255,.2); background:#fff; }
.mps-head-ph  { width:80px; height:80px; font-size:34px; border:3px solid rgba(255,255,255,.2); }
.mps-head h1  { font-size:clamp(20px,3.6vw,30px); }

/* แกลเลอรี: เห็นรูปเต็มทั้งใบ (ไม่ครอบตัด) แค่ย่อขนาดลง */
.mps-gallery-main { width:100%; max-height:340px; object-fit:contain; background:#f4f6f4; }
.mps-thumb  { width:64px; height:64px; object-fit:contain; background:#f4f6f4; cursor:pointer; border:2px solid transparent; }
.mps-thumb.active, .mps-thumb:hover { border-color:var(--kgm-green-600); }
</style>
{{-- ══ หัวเรื่อง: รูปหลักแสดงเล็กๆ คู่กับชื่อ ══ --}}
<div class="mps-head py-4" style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));">
    <div class="container">
        <div class="d-flex align-items-center flex-column flex-sm-row text-center text-sm-start gap-3">
            @if($product->image)
                <img src="{{ media_url($product->image) }}" alt="{{ $product->name }}" class="mps-head-img rounded-4 flex-shrink-0">
            @else
                <div class="mps-head-ph rounded-4 flex-shrink-0 d-flex align-items-center justify-content-center text-white-50" style="background:rgba(255,255,255,.12);">
                    <i class="bi bi-hammer"></i>
                </div>
            @endif
            <div>
                <div style="color:var(--kgm-gold-300);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:4px;">สินค้าที่รับผลิต</div>
                <h1 class="fw-bold text-white m-0">{{ $product->name }}</h1>
            </div>
        </div>
    </div>
</div>

{{-- ══ BODY ══ --}}
<section class="section bg-white">
    <div class="container">
        <div class="row g-4 g-lg-5">
            {{-- แกลเลอรีรูปเพิ่มเติม (ไม่รวมรูปหลัก) --}}
            <div class="col-12 col-lg-7">
                @if($product->images->count())
                <div x-data="{ current: @js(media_url($product->images->first()->image_path)) }">
                    <img :src="current" alt="{{ $product->name }}" class="mps-gallery-main img-fluid rounded-4 border">
                    @if($product->images->count() > 1)
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach($product->images as $img)
                        @php $src = media_url($img->image_path); @endphp
                        <img src="{{ $src }}" alt="{{ $product->name }}" loading="lazy"
                             class="mps-thumb rounded-3" :class="{ 'active': current === @js($src) }"
                             @click="current = @js($src)">
                        @endforeach
                    </div>
                    @endif
                </div>
                @else
                {{-- ไม่มีรูปเพิ่มเติม --}}
                <div class="text-center text-muted rounded-4 py-5 px-3" style="background:#f8faf8;border:1px dashed #ddd;">
                    <i class="bi bi-images d-block mb-2" style="font-size:40px;"></i>
                    ยังไม่มีรูปเพิ่มเติม
                </div>
                @endif
            </div>

            {{-- รายละเอียด + CTA --}}
            <div class="col-12 col-lg-5">
                <div class="section-subtitle">รายละเอียด</div>
                <h2 class="fw-bold mt-2 mb-3" style="font-size:20px;color:var(--kgm-green-900);">{{ $product->name }}</h2>

                @if($product->description)
                <p class="mb-4" style="font-size:15px;color:#555;line-height:1.9;white-space:pre-line;">{{ $product->description }}</p>
                @else
                <p class="mb-4" style="font-size:15px;color:#999;">รับผลิตตามแบบและสเปกที่ต้องการ ติดต่อทีมงานเพื่อขอรายละเอียดเพิ่มเติม</p>
                @endif

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('quote') }}" class="btn btn-primary btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
                    <a href="tel:0851100010" class="btn btn-lg" style="background:#f1f5f1;color:var(--kgm-green-800);"><i class="bi bi-telephone"></i> 085-110-0010</a>
                </div>

                <div class="mt-4">
                    <a href="{{ route('order') }}" class="fw-semibold text-decoration-none" style="font-size:14px;color:var(--kgm-green-700);">
                        <i class="bi bi-arrow-left"></i> กลับไปดูสินค้าทั้งหมด
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:56px 0;text-align:center;">
    <div class="container">
        <h2 style="font-size:clamp(20px,4vw,30px);font-weight:800;color:white;margin:0 0 12px;line-height:1.5;">สนใจสั่งผลิต {{ $product->name }}?</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.75);margin:0 0 24px;">ส่งรายละเอียดความต้องการ รับใบเสนอราคาภายใน 1-2 วันทำการ</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('contact') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-telephone"></i> ติดต่อเรา</a>
        </div>
    </div>
</section>

@endsection
