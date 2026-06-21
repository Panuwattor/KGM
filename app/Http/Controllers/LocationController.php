<?php

namespace App\Http\Controllers;

use App\Models\Districts;
use App\Models\Provinces;

class LocationController extends Controller
{
    /** รายชื่อจังหวัดทั้งหมด */
    public function provinces()
    {
        $provinces = Provinces::orderBy('name_in_thai')->get(['id', 'name_in_thai']);

        return response()->json(
            $provinces->map(fn($p) => ['id' => $p->id, 'name' => $p->name_in_thai])
        );
    }

    /** อำเภอ/เขต ภายในจังหวัด */
    public function districts(Provinces $province)
    {
        $districts = $province->districts()->orderBy('name_in_thai')->get(['id', 'name_in_thai']);

        return response()->json(
            $districts->map(fn($d) => ['id' => $d->id, 'name' => $d->name_in_thai])
        );
    }

    /** ตำบล/แขวง ภายในอำเภอ (พร้อมรหัสไปรษณีย์) */
    public function subdistricts(Districts $district)
    {
        $subdistricts = $district->subdistricts()->orderBy('name_in_thai')->get(['id', 'name_in_thai', 'zip_code']);

        return response()->json(
            $subdistricts->map(fn($s) => ['id' => $s->id, 'name' => $s->name_in_thai, 'zip' => $s->zip_code])
        );
    }
}
