<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersPackingExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    public function __construct(private array $filters)
    {
    }

    public function collection(): Collection
    {
        $query = Order::with(['items.product', 'items.variant', 'customer'])
            ->orderBy('created_at');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('ship_name', 'like', "%{$search}%")
                  ->orWhere('ship_phone', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $rows = collect();
        foreach ($query->get() as $order) {
            foreach ($order->items as $item) {
                $rows->push([
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->status_label,
                    $order->ship_name,
                    $order->ship_phone,
                    $order->ship_address,
                    $order->ship_district,
                    $order->ship_amphoe,
                    $order->ship_province,
                    $order->ship_postcode,
                    $order->delivery_method,
                    $order->shipping_provider,
                    $item->product_name,
                    $item->variant_label,
                    $item->quantity,
                    $item->embroidery ? 'ใช่' : '',
                    $item->embroidery_text,
                    '',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'เลขที่ออเดอร์',
            'วันที่สั่ง',
            'สถานะ',
            'ชื่อลูกค้า',
            'เบอร์โทร',
            'ที่อยู่จัดส่ง',
            'ตำบล/แขวง',
            'อำเภอ/เขต',
            'จังหวัด',
            'รหัสไปรษณีย์',
            'วิธีจัดส่ง',
            'ขนส่ง',
            'สินค้า',
            'ไซซ์',
            'จำนวน',
            'ปักชื่อ',
            'ข้อความปัก',
            'แพ็กแล้ว',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16, 'B' => 16, 'C' => 14, 'D' => 20, 'E' => 14,
            'F' => 28, 'G' => 14, 'H' => 14, 'I' => 14, 'J' => 10,
            'K' => 12, 'L' => 14, 'M' => 24, 'N' => 8, 'O' => 8,
            'P' => 8, 'Q' => 18, 'R' => 10,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
