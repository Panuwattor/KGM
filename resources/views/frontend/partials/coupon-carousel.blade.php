{{--
    Coupon carousel แบบ slide เหมือนหน้า home — เก็บคูปองได้เลย
    ใช้: @include('frontend.partials.coupon-carousel', ['coupons' => $coupons, 'collectedIds' => $collectedIds])
--}}
@php $cqId = $cqId ?? 'cqtrack-'.\Illuminate\Support\Str::random(6); @endphp

@once
@push('styles')
<style>
.cq-wrap { position:relative; }
.cq-viewport { overflow:hidden; padding:10px 2px 6px; cursor:grab; user-select:none; }
.cq-track    { display:flex; gap:14px; transition:transform .45s cubic-bezier(.4,0,.2,1); will-change: transform; }
.cq-ticket { flex:0 0 272px; height:92px; display:flex; border-radius:12px; background:white; box-shadow:0 2px 10px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04); position:relative; overflow:visible; }
.cq-left { width:82px; flex-shrink:0; border-radius:12px 0 0 12px; background:linear-gradient(150deg, var(--kgm-green-700), var(--kgm-green-500)); background-size:cover; background-position:center; overflow:hidden; position:relative; }
.cq-left-inner { position:absolute; inset:0; background:linear-gradient(150deg, rgba(10,55,30,.80), rgba(20,80,45,.65)); display:flex; flex-direction:column; align-items:center; justify-content:center; color:white; text-align:center; gap:1px; padding:6px; }
.cq-num  { font-size:22px; font-weight:900; line-height:1; letter-spacing:-.5px; }
.cq-lbl  { font-size:10px; font-weight:600; opacity:.85; }
.cq-notch { position:absolute; left:74px; width:16px; height:16px; border-radius:50%; background:#f3f5f3; z-index:3; display:block; }
.cq-nt { top:-8px; } .cq-nb { bottom:-8px; }
.cq-right { flex:1; min-width:0; padding:10px 14px; border-left:1.5px dashed #d4d9d4; border-radius:0 12px 12px 0; display:flex; flex-direction:column; justify-content:center; gap:3px; overflow:hidden; }
.cq-name { font-size:13px; font-weight:700; color:#1a2e1f; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1.3; }
.cq-cond { font-size:11px; color:#999; line-height:1.3; }
.cq-btn { display:inline-block; margin-top:5px; align-self:flex-start; font-size:11px; font-weight:700; color:white; background:var(--kgm-green-600); border-radius:20px; padding:3px 12px; text-decoration:none; line-height:1.6; transition:background .15s; border:none; cursor:pointer; font-family:inherit; }
.cq-btn:hover { background:var(--kgm-green-700); color:white; }
.cq-btn.cq-got { background:var(--kgm-green-100); color:var(--kgm-green-700); cursor:default; }
.cq-btn.cq-got:hover { background:var(--kgm-green-100); }
.cq-arrow { position:absolute; top:50%; transform:translateY(calc(-50% - 10px)); z-index:10; width:30px; height:30px; border-radius:50%; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:13px; background:white; box-shadow:0 2px 8px rgba(0,0,0,.12); color:var(--kgm-green-700); transition:all .2s; }
.cq-arrow:hover { background:var(--kgm-green-600); color:white; }
.cq-prev { left:-16px; } .cq-next { right:-16px; }
@media (min-width:641px) { .cq-arrow { display:none; } }
@media (max-width:600px) { .cq-prev { left:0; } .cq-next { right:0; } }
</style>
@endpush
@push('scripts')
<script>
window.KGMCouponCarousel = function (trackId) {
    const track = document.getElementById(trackId);
    if (!track) return;
    const origCards = Array.from(track.querySelectorAll('.cq-ticket'));
    const total = origCards.length;
    if (total === 0) return;
    const clones = origCards.map(c => { const cl = c.cloneNode(true); track.appendChild(cl); return cl; });
    const GAP = 14, CARD = 272, STEP = CARD + GAP;
    let cur = 0, tmr = null;
    function vis(){ const w = track.parentElement.offsetWidth; return w < 360 ? 1 : w < 640 ? 2 : Math.min(total, Math.floor((w + GAP) / STEP)); }
    function maxI(){ return Math.max(0, total - vis()); }
    function updateClones(){ const show = maxI() > 0; clones.forEach(c => c.style.display = show ? '' : 'none'); }
    function snapTo(i){ track.style.transition='none'; cur=i; track.style.transform=`translateX(-${cur*STEP}px)`; requestAnimationFrame(()=>{ track.style.transition=''; }); }
    function goTo(i){ updateClones(); const vw=track.parentElement.offsetWidth; const tw=total*CARD+Math.max(0,total-1)*GAP; track.style.marginLeft = tw<vw ? `${(vw-tw)/2}px` : '0'; if (maxI()===0){ snapTo(0); return; } cur=i; track.style.transform=`translateX(-${cur*STEP}px)`; }
    function afterSlide(){ if (cur>=total) snapTo(cur-total); else if (cur<0) snapTo(cur+total); }
    function slide(d){ reset(); goTo(cur+d); setTimeout(afterSlide,460); }
    function reset(){ clearInterval(tmr); tmr=setInterval(()=>{ goTo(cur+1); setTimeout(afterSlide,460); },4500); }
    track.__cqSlide = d => slide(d);
    goTo(0); reset();
    let rt; window.addEventListener('resize', ()=>{ clearTimeout(rt); rt=setTimeout(()=>{ afterSlide(); goTo(cur); },150); });
    let startX=0, startY=0, horizLock=false; const vp=track.parentElement;
    vp.addEventListener('mousedown', e=>{ startX=e.clientX; vp.style.cursor='grabbing'; });
    vp.addEventListener('mouseup', e=>{ vp.style.cursor=''; const dx=startX-e.clientX; if (Math.abs(dx)>30) slide(dx>0?1:-1); });
    vp.addEventListener('mouseleave', ()=>{ vp.style.cursor=''; });
    vp.addEventListener('touchstart', e=>{ startX=e.touches[0].clientX; startY=e.touches[0].clientY; horizLock=false; }, { passive:true });
    vp.addEventListener('touchmove', e=>{ const dx=Math.abs(startX-e.touches[0].clientX), dy=Math.abs(startY-e.touches[0].clientY); if (!horizLock && dx>dy) horizLock=true; if (horizLock) e.preventDefault(); }, { passive:false });
    vp.addEventListener('touchend', e=>{ const dx=startX-e.changedTouches[0].clientX; if (Math.abs(dx)>30) slide(dx>0?1:-1); });
};
</script>
@endpush
@endonce

@if($coupons->isNotEmpty())
<div class="cq-wrap">
    @if($arrows ?? true)
    <button class="cq-arrow cq-prev" type="button" onclick="document.getElementById('{{ $cqId }}').__cqSlide(-1)" aria-label="ก่อนหน้า"><i class="bi bi-chevron-left"></i></button>
    <button class="cq-arrow cq-next" type="button" onclick="document.getElementById('{{ $cqId }}').__cqSlide(1)" aria-label="ถัดไป"><i class="bi bi-chevron-right"></i></button>
    @endif
    <div class="cq-viewport">
        <div class="cq-track" id="{{ $cqId }}">
            @foreach($coupons as $coupon)
            @php $isCollected = in_array($coupon->id, $collectedIds ?? []); @endphp
            <div class="cq-ticket">
                <div class="cq-left" @if($coupon->image) style="background-image:url('{{ media_url($coupon->image) }}');" @endif>
                    @if(!$coupon->image)
                    <div class="cq-left-inner">
                        @if($coupon->type === 'free_shipping')
                            <i class="bi bi-truck" style="font-size:20px;"></i>
                            <span class="cq-lbl">ส่งฟรี</span>
                        @elseif($coupon->type === 'percent')
                            <span class="cq-num">{{ (int) $coupon->value }}%</span>
                            <span class="cq-lbl">ส่วนลด</span>
                        @else
                            <span class="cq-num" style="font-size:17px;">฿{{ number_format((float) $coupon->value, 0) }}</span>
                            <span class="cq-lbl">ส่วนลด</span>
                        @endif
                    </div>
                    @endif
                </div>
                <span class="cq-notch cq-nt"></span>
                <span class="cq-notch cq-nb"></span>
                <div class="cq-right">
                    <div class="cq-name">{{ $coupon->name }}</div>
                    @if($coupon->minimum_order > 0)
                    <div class="cq-cond">ซื้อขั้นต่ำ ฿{{ number_format($coupon->minimum_order, 0) }}</div>
                    @endif
                    @if($isCollected)
                    <span class="cq-btn cq-got"><i class="bi bi-check-circle-fill"></i> เก็บแล้ว</span>
                    @elseif(auth('customer')->check())
                    <form method="POST" action="{{ route('coupons.collect', $coupon) }}">
                        @csrf
                        <button type="submit" class="cq-btn">เก็บคูปอง</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="cq-btn">เข้าสู่ระบบ</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => KGMCouponCarousel('{{ $cqId }}'));</script>
@endif
