/**
 * KGM Location — dropdown จังหวัด → อำเภอ → ตำบล แบบ cascade
 * เก็บค่าเป็น "ชื่อภาษาไทย" (text) ตาม value ของ option — ไม่เก็บ id
 *
 * การใช้งาน:
 *   KGMLocation({
 *       province: el,          // <select> จังหวัด (จำเป็น)
 *       amphoe:   el,          // <select> อำเภอ/เขต (ไม่บังคับ)
 *       district: el,          // <select> ตำบล/แขวง (ไม่บังคับ)
 *       zip:      el,          // <input> รหัสไปรษณีย์ (ไม่บังคับ เติมอัตโนมัติ)
 *       initial:  { province, amphoe, district, zip } // ค่าเริ่มต้น (ตอนแก้ไข)
 *   })
 *   => คืน Promise ที่ resolve เป็น object { set({province,amphoe,district,zip}) }
 */
(function () {
    const cache = { provinces: null, districts: {}, subdistricts: {} };

    async function getJSON(url) {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        return res.json();
    }
    async function loadProvinces() {
        if (!cache.provinces) cache.provinces = await getJSON('/locations/provinces');
        return cache.provinces;
    }
    async function loadDistricts(pid) {
        if (!cache.districts[pid]) cache.districts[pid] = await getJSON('/locations/provinces/' + pid + '/districts');
        return cache.districts[pid];
    }
    async function loadSubdistricts(did) {
        if (!cache.subdistricts[did]) cache.subdistricts[did] = await getJSON('/locations/districts/' + did + '/subdistricts');
        return cache.subdistricts[did];
    }

    function currentId(select) {
        const o = select && select.options[select.selectedIndex];
        return o ? o.dataset.id : null;
    }
    function selectByName(select, name) {
        if (!select) return;
        let found = false;
        for (const o of select.options) {
            o.selected = (o.value === name && name !== '' && name != null);
            if (o.selected) found = true;
        }
        if (!found) select.selectedIndex = 0;
    }
    function setOptions(select, items, placeholder) {
        if (!select) return;
        select.innerHTML = '';
        const ph = document.createElement('option');
        ph.value = '';
        ph.textContent = placeholder;
        select.appendChild(ph);
        (items || []).forEach(it => {
            const o = document.createElement('option');
            o.value = it.name;
            o.dataset.id = it.id;
            if (it.zip) o.dataset.zip = it.zip;
            o.textContent = it.name;
            select.appendChild(o);
        });
    }

    function hasSelect2() {
        return window.jQuery && window.jQuery.fn && window.jQuery.fn.select2;
    }

    window.KGMLocation = async function (opts) {
        const province = opts.province;
        const amphoe = opts.amphoe || null;
        const district = opts.district || null;
        const zip = opts.zip || null;
        const initial = opts.initial || {};
        const useSelect2 = opts.select2 && hasSelect2();
        let building = false;

        function initSelect2(select) {
            if (!useSelect2 || !select) return;
            const cfg = {
                width: '100%',
                language: { noResults: () => 'ไม่พบข้อมูล', searching: () => 'กำลังค้นหา...' },
            };
            if (opts.dropdownParent) cfg.dropdownParent = window.jQuery(opts.dropdownParent);
            window.jQuery(select).select2(cfg);
        }

        function fireChange(select) {
            // แจ้ง Select2 / custom-select enhancer (admin) / Alpine ให้ sync ข้อความที่แสดง
            select.dispatchEvent(new Event('change', { bubbles: true }));
            select.dispatchEvent(new Event('input', { bubbles: true }));
        }

        // ผูก event การเปลี่ยนค่า: ถ้าใช้ Select2 ต้องผูกผ่าน jQuery
        // เพราะ Select2 trigger 'change' ผ่าน jQuery ซึ่ง addEventListener จับไม่ได้
        function onChange(select, handler) {
            if (!select) return;
            if (useSelect2) window.jQuery(select).on('change', handler);
            else select.addEventListener('change', handler);
        }

        function setZip() {
            if (!zip || !district) return;
            const o = district.options[district.selectedIndex];
            if (o && o.dataset.zip) zip.value = o.dataset.zip;
        }

        async function refreshAmphoe(selName) {
            if (!amphoe) return;
            building = true;
            setOptions(amphoe, currentId(province) ? await loadDistricts(currentId(province)) : [], '-- เลือกอำเภอ/เขต --');
            selectByName(amphoe, selName);
            fireChange(amphoe);
            building = false;
        }
        async function refreshDistrict(selName) {
            if (!district) return;
            building = true;
            setOptions(district, currentId(amphoe) ? await loadSubdistricts(currentId(amphoe)) : [], '-- เลือกตำบล/แขวง --');
            selectByName(district, selName);
            fireChange(district);
            building = false;
        }

        // ---- ตั้งค่าจังหวัด ----
        setOptions(province, await loadProvinces(), '-- เลือกจังหวัด --');
        selectByName(province, initial.province);
        fireChange(province);

        // ---- เปิดใช้ Select2 (ค้นหาได้) ----
        initSelect2(province);
        initSelect2(amphoe);
        initSelect2(district);

        // ---- ผูก event ----
        onChange(province, async () => {
            if (building) return;
            await refreshAmphoe();
            await refreshDistrict();
            if (zip) zip.value = '';
        });
        onChange(amphoe, async () => {
            if (building) return;
            await refreshDistrict();
            if (zip) zip.value = '';
        });
        onChange(district, () => {
            if (building) return;
            setZip();
        });

        // ---- โหลดค่าเริ่มต้น (ตอนแก้ไข) ----
        await refreshAmphoe(initial.amphoe);
        await refreshDistrict(initial.district);
        if (zip && initial.zip) zip.value = initial.zip;
        else setZip();

        return {
            async set(v) {
                v = v || {};
                building = true;
                selectByName(province, v.province);
                fireChange(province);
                building = false;
                await refreshAmphoe(v.amphoe);
                await refreshDistrict(v.district);
                if (zip) zip.value = v.zip || '';
            }
        };
    };
})();
