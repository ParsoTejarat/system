<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<style>
    #products_table input, #products_table select {
        width: auto;
    }

    .title-sec {
        background: #ececec;
    }

    .main-content {
        margin: 0 !important;
    }

    body {
        padding: 0;
        text-align: center !important;
    }

    main {
        padding: 0 !important;
    }

    table {
        width: 100% !important;
        /*border-collapse: separate !important;*/
    }

    .table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table th, .table td {
        padding: 4px !important;
        border: 2px solid #000 !important;
        font-size: 14px !important;
        text-align: center !important;
    }

    .table tr {
        padding: 0 !important;
        border: 2px solid #000 !important;
        text-align: center !important;
    }

    #printable_sec {
        padding: 0;
    }

    .card {
        margin: 0;
    }

    .guide_box {
        text-align: center;
    }

    * {
        color: #000 !important;
    }

    .btn, .fa {
        color: #fff !important
    }

    .table:not(.table-bordered) td {
        line-height: 1;
    }

    .content-page {
        height: 100% !important
    }
</style>
<div style="border: 2px solid black">
    <div style="font-size: 20px;width: 100%; margin-bottom: 20px">
        <table>
            <tr>
                <td style="width: 30%">
                    <img src="{{ asset('/assets/images/header-logo.png') }}"
                         style="width: 10rem;margin-top: 10px">
                </td>
                <td>
                    <span
                        style="font-size: 25px; margin-right: 25px !important;font-weight: bold">حواله خروج از انبار</span>
                </td>
                <td style="width: 30%;text-align: left!important">

                </td>
            </tr>
        </table>
    </div>

    <table>
        <tbody>
        <tr>
            <td style="text-align: right !important;padding-right: 10px;width: 30%">کد انبار : 1</td>
            <td style="width: 40%"> کد تحویل گیرنده : {{$exitRemittance->customer->code??'-'}} </td>
            <td style="width: 30%">شناسه حواله : {{$exitRemittance->code}}</td>
        </tr>
        <tr>
            <td style="text-align: right !important;padding-right: 10px;width: 30%">عنوان انبار: مرکزی</td>
            <td style="width: 40%">عنوان تحویل گیرنده : {{$exitRemittance->customer->name}}</td>
            <td style="width: 30%"> تاریخ ثبت : {{verta($exitRemittance->created_at)->format('H:i   %Y/%m/%d')}}</td>
        </tr>
        </tbody>
    </table>
    <br>
</div>


<div class="col-12 mb-3">
    <div class="overflow-x-auto">
        <table class="table text-center">
            <thead>
            <tr class="title-sec">
                <th>ردیف</th>
                <th>شناسه کالا</th>
                <th>شرح کالا</th>
                <th>دسته بندی کالا</th>
                <th>برند</th>
                <th>رنگ</th>
                <th>مقدار اصلی</th>
                <th>واحد اصلی</th>
            </tr>
            </thead>
            <tbody>
            @php $sum = 0; @endphp
            @foreach(json_decode($exitRemittance->products) as $product)
                @php $sum += $product->count; @endphp
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $product->sku??'-' }}</td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->category??'-' }}</td>
                    <td>{{ $product->brand??'-' }}</td>
                    <td>{{ $product->color??'-' }}</td>
                    <td>{{ $product->count }}</td>
                    <td>{{ $product->unit??'-' }}</td>

                </tr>
            @endforeach
            <tr class="title-sec">
                <td colspan="8" style="text-align: right !important;"><span> مجموع : {{$sum}}</span></td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
