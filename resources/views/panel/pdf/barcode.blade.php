<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
          type="text/css"/>
    <style>
        body {
            font-family: Vazirmatn, sans-serif;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
        }

        .page {
            width: 103mm;
            height: 30mm;
            display: flex;
            justify-content: space-between;
            page-break-after: always;
        }

        .label {
            width: 52mm;
            height: 30mm;
            border: 1px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            text-align: center;
            margin: 1mm;
        }

        .barcode {
            margin-top: 5px;
        }

        @page {
            size: 214.84166667mm 68.791666667mm;
            margin: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .page {
                width: 214.84166667mm;
                height: 68.791666667mm;
            }

            .label {
                margin: 0;
                width: 214.84166667mm;
                height: 68.791666667mm;
                border: none;

            }

            .page, .page * {
                visibility: visible;
            }

            #print {
                visibility: hidden;
                display: none;
            }

            .product_title {
                font-size: 2rem;
                font-weight: 800;
            }

            .company-name {
                font-weight: 400;
                font-size: 1.5rem;
            }

            .code {
                font-size: 1.7rem;
            }

            .barcode_img {
                width: 20rem;
            }
            .last-label {
                margin-left: auto;
            }


        }
    </style>
</head>
<body>
<button id="print" style="margin-top: 10px">پرینت</button>

@php
    $totalBarcodes = count($barcodes);
    $isOdd = $totalBarcodes % 2 !== 0;
@endphp
@foreach($barcodes->chunk(2) as $row)
    <div class="page">
        @foreach($row as $barcode)
            <div class="label @if($loop->last && $isOdd) last-label @endif">
                <div class="product_title">+ کیس آوا </div>
                <div class="company-name">بارمان سیستم سرزمین پارس</div>
                <div class="code">{{ $barcode }}</div>
                <div class="barcode">
                    @php
                        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                        $barcodeImage = base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128));
                    @endphp
                    <img class="barcode_img" src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode">
                </div>
            </div>
            @if($row->count() % 2 !== 0)
                <div class="label"></div>
            @endif
        @endforeach
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#print').on('click', function () {
            window.print();
        });
    });
</script>
</body>
</html>

