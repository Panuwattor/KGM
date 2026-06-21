{{-- jQuery + Select2 + สคริปต์ cascade จังหวัด/อำเภอ/ตำบล (ค้นหาได้) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/kgm-location.js') }}?v=2"></script>
<style>
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--single {
        height: 42px; border: 2px solid #e8ecef; border-radius: 14px; background: #fafafa;
        display: flex; align-items: center; padding: 0 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal; color: #2c3e50; padding-left: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #aaa; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #40916c; background: #fff; box-shadow: 0 0 0 3px rgba(64,145,108,.1);
    }
    .select2-dropdown { border: 2px solid #e0e8e0; border-radius: 14px; overflow: hidden; }
    .select2-search--dropdown .select2-search__field { border: 2px solid #e8ecef; border-radius: 10px; padding: 6px 10px; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background: #40916c; }
    .select2-container--default .select2-results__option[aria-selected=true] { background: #d8f3dc; color: #1e4d35; }
</style>
