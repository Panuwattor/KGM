@php $selected = $selected ?? []; @endphp
@foreach($permissions as $group => $items)
    <div style="margin-bottom:18px;">
        <div style="font-weight:700;font-size:13px;color:#666;text-transform:uppercase;margin-bottom:8px;">{{ $group }}</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:4px;">
            @foreach($items as $permission)
            <div class="form-check">
                <input type="checkbox" name="permissions[]" id="perm-{{ $permission->id }}" value="{{ $permission->name }}"
                    {{ in_array($permission->name, $selected) ? 'checked' : '' }}>
                <label for="perm-{{ $permission->id }}">
                    {{ $permission->description ?? $permission->name }}
                    <span style="color:#999;font-size:11px;display:block;font-weight:400;">({{ $permission->name }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
@endforeach
