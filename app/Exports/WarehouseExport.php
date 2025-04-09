<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehouseExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::with(['category', 'brand'])
            ->withCount(['trackingCodes' => fn($q) => $q->whereNull('exit_time')])
            ->orderBy('tracking_codes_count', 'desc')
            ->get();
    }

    public function map($product): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $product->sku,
            $product->title,
            $product->category->name ?? 'بدون دسته‌بندی',
            $product->brand ? ($product->brand->name . ' (' . ($product->brand->name_en ?? '') . ')') : 'بدون برند',
            number_format($product->tracking_codes_count) ?? 0,
            verta($product->created_at)->format('H:i - Y/m/d'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->setRightToLeft(true)
                    ->getStyle('A1:XFD1048576')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A1:XFD1048576')->getFont()->setName('B Nazanin');

//                $event->sheet->mergeCells('B1:C1');
            },
        ];
    }

    public function headings(): array
    {
        return [
            'A' => 'ردیف',
            'B' => 'کد محصول (SKU)',
            'C' => 'عنوان محصول',
            'D' => 'دسته‌بندی',
            'E' => 'برند',
            'F' => 'تعداد موجودی فعلی',
            'G' => 'تاریخ ثبت',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0096d6']
            ]
        ])->getFont()->setColor(Color::indexedColor(2));

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
