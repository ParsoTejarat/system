<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\TrackingCode;
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

class ProductsExportTracking implements FromCollection, WithMapping, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    protected $product_id;

    public function __construct($product_id)
    {
        $this->product_id = $product_id;
    }


    public function collection()
    {
        return TrackingCode::where('product_id', $this->product_id)->where('exit_time', Null)->get();
//        dd(TrackingCode::where('product_id', $this->product_id)->where('exit_time', Null)->get());
    }


    public function map($product): array
    {
        return [
            $product->product->title,
            $product->code,
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
            'A' => 'شرح کالا',
            'B' => 'سریال کالا S/N',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
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
