<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>لیبل کالا</title>
    <style>
        body {
            direction: rtl;
        }

        .page {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2px;
            justify-content: center;
            margin: 0 auto;
            width: 103mm;
        }

        .label {
            width: 51mm;
            height: auto;
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            display: grid;
            justify-content: center;
        }

        .barcode {
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="page">
    @foreach($barcodes as $barcode)
        <div class="label test">

            <div>کیس هیوا</div>
            <div class="company-name">بارمان سیستم سرزمین پارس</div>
            <div>{{ $barcode }}</div>
            <div class="barcode">
                @php
                    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                @endphp
                {!! $generator->getBarcode($barcode, $generator::TYPE_CODE_128); !!}
            </div>

        </div>
    @endforeach
</div>
</body>
</html>
