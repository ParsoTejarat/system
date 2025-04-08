<?php

namespace App\Exports;

use App\Models\Guarantee;
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

class GuaranteesExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        return Guarantee::with(['product', 'order'])->whereIn('id', $this->ids)->latest()->get();
    }

    public function map($guarantee): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $guarantee->serial_number,
            $guarantee->order?->code ?? '-',
            $guarantee->product?->title ?? '-',
            \App\Models\Guarantee::PERIOD[$guarantee->period],
            $guarantee->start_time ? verta($guarantee->start_time)->format('Y/m/d') : '---',
            $guarantee->expire_time ? verta($guarantee->expire_time)->format('Y/m/d') : '---',
            \App\Models\Guarantee::STATUS[$guarantee->status] ?? '-',
            verta($guarantee->created_at)->format('Y/m/d - H:i')

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

            },
        ];
    }

    public function headings(): array
    {
        return [
            'ردیف',
            'شماره سریال',
            'کد سفارش',
            'محصول',
            'مدت گارانتی',
            'تاریخ شروع',
            'تاریخ انقضا',
            'وضعیت',
            'تاریخ ثبت',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
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
