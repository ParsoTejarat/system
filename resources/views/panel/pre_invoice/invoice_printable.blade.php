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
        font-size: 16px !important;
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
@php
    $left_sidebar = false;
    $topbar = false;

    $sum_total_price = 0;
    $sum_discount_amount = 0;
    $sum_extra_amount = 0;
    $sum_total_price_with_off = 0;
    $sum_tax = 0;
    $sum_invoice_net = 0;

    $i = 1;
@endphp
<div style="font-size: 20px;width: 100%;">
    <table>
        <tr>
            <td style="width: 700px">
                <img src="{{ public_path('/assets/images/header-logo.png') }}" style="width: 15rem;">
            </td>
            <td>
                <span style="font-size: 25px">پیش فاکتور فروش کالا و خدمات</span>
            </td>
            <td style="width: 200px">
                <p style="font-size: 15px">شماره سریال: {{ $invoice->invoice_number??$invoice->id }}</p>
                <br>
                <p style="font-size: 15px">تاریخ: {{ verta($invoice->created_at)->format('Y/m/d') }}</p>
            </td>
        </tr>
    </table>
</div>
<form action="" method="post">
    <div class="form-row">
        <table class="table table-bordered mb-0">
            <thead>
            <tr>
                <th class="text-center p-0 title-sec">مشخصات فروشنده</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div>
                        <span>نام شخص حقیقی/حقوقی: بازرگانی پرسو تجارت ایرانیان</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره اقتصادی: 10103472930</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره ثبت/شماره ملی: 309754</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شناسه ملی: 10103472930</span>
                    </div>
                    <div style="height: 2rem">&nbsp;</div>
                    <div>
                        <span>نشانی: خیابان کریمخان، خیابان ایرانشهر، پلاک 242، طبقه پنجم</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>کد پستی: 1584745337</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره تلفن: 09102097248 / 02188867100</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table table-bordered mb-5">
            <thead>
            <tr>
                <th class="text-center p-0 title-sec">مشخصات خریدار</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <div class="mb-3" style="width: 100%">
                        <span>نام شخص حقیقی/حقوقی: {{ $invoice->customer_name}}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره اقتصادی: {{ $invoice->commercial_code }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره ثبت/شماره ملی: {{ $invoice->national_code }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>استان: {{ $invoice->province }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div style="height: 2rem">&nbsp;</div>
                    <div>
                        <span>شهر: {{ $invoice->city }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>کد پستی: {{ $invoice->zip_code }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>نشانی: {{ $invoice->address }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span>شماره تلفن: {{ $invoice->phone_number }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="col-12 mb-3">
            <div class="overflow-x-auto">
                <table class="table text-center" border="2">
                    <thead>
                    <tr>
                        <th class="p-0 title-sec" colspan="12">مشخصات کالا یا خدمات مورد معامله</th>
                    </tr>
                    <tr>
                        <th>ردیف</th>
                        <th>کالا</th>
                        <th>رنگ</th>
                        <th>تعداد</th>
                        <th>واحد اندازه گیری</th>
                        <th>مبلغ واحد</th>
                        <th>مبلغ کل</th>
                        <th>مبلغ تخفیف</th>
                        <th>مبلغ اضافات</th>
                        <th>مبلغ کل پس از تخفیف و اضافات</th>
                        <th>جمع مالیات و عوارض</th>
                        <th>خالص فاکتور</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(json_decode($invoice->products) as $key => $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->product}}</td>
                            <td>{{$item->color }}</td>
                            <td>{{ $item->count }}</td>
                            <td>{{ \App\Models\Product::UNITS[$item->unit] }}</td>
                            <td>{{ number_format($item->prices) }}</td>
                            <td>{{ number_format($item->total_prices) }}</td>
                            <td>{{ number_format($item->discount_amounts) }}</td>
                            <td>{{ number_format($item->extra_amounts) }}</td>
                            <td>{{ number_format($item->total_prices - ($item->extra_amounts + $item->discount_amounts)) }}</td>
                            <td>{{ number_format($item->taxes) }}</td>
                            <td>{{ number_format($item->invoice_nets) }}</td>
                        </tr>

                        @php
                            $sum_total_price += $item->total_prices;
                            $sum_discount_amount += $item->discount_amounts;
                            $sum_extra_amount += $item->extra_amounts;
                            $sum_total_price_with_off += $item->total_prices - ($item->extra_amounts + $item->discount_amounts);
                            $sum_tax += $item->taxes;
                            $sum_invoice_net += $item->invoice_nets;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6">جمع کل</td>
                        <td>{{ number_format($sum_total_price) }}</td>
                        <td>{{ number_format($sum_discount_amount) }}</td>
                        <td>{{ number_format($sum_extra_amount) }}</td>
                        <td>{{ number_format($sum_total_price_with_off) }}</td>
                        <td>{{ number_format($sum_tax) }}</td>
                        <td>{{ number_format($sum_invoice_net) }}</td>
                    </tr>
                    <tr>
                        <th class="p-0 title-sec" colspan="6">تخفیف نهایی</th>
                        <th class="p-0 title-sec" colspan="6">مبلغ فاکتور پس از تخفیف نهایی</th>
                    </tr>
                    <tr>
                        <td colspan="6">{{ number_format($invoice->discount) }}</td>
                        <td colspan="6">{{ number_format($sum_invoice_net - $invoice->discount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div style="text-align: right; display: flex">
                                <span class="mr-4">شرایط و نحوه فروش</span>
                                <span>&nbsp;</span>
                                نقدی<input type="checkbox">
                                غیر نقدی<input type="checkbox">
                            </div>
                        </td>
                        <td colspan="8" class="text-start">
                            {{change_number_to_words($sum_invoice_net - $invoice->discount)}} ریال
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><small>توضیحات</small></td>
                        <td colspan="10">{!! nl2br(e($invoice->description)) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="12">
                            خواهشمند است مبلغ فاكتور را به شماره شبا IR19-0150-0001-8878-2299-0698-11 نزد بانك سپه شعبه
                            ميدان منيريه واريز نماييد. با تشكر
                            <br>
                            <br>
                            آدرس سایت https://parsotejarat.com
                        </td>
                    </tr>
                    <tr>
                        {{--                                <td colspan="12"><strong>تمام اجناس ارائه شده دارای 18 ماه گارانتی از سوی شرکت صنایع ماشین های اداری ماندگار پارس می باشد</strong></td>--}}
                    </tr>
                    <tr>
                        <td colspan="6">
                            <small>مهر و امضای فروشنده</small>
                        </td>
                        <td colspan="6"><small>مهر و امضای خریدار</small></td>
                    </tr>
                    </tbody>
                </table>
                <table>
                    <tr>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            @if($invoice->user->sign_image)
                                <img
                                    src="{{ $invoice->user->sign_image ? public_path($invoice->user->sign_image) : '' }}"
                                    style="width: 10rem">
                            @endif
                            {{--                                    <img src="{{ public_path('assets/images/stamp.png') }}" style="width: 13rem">--}}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
</body>
</html>
