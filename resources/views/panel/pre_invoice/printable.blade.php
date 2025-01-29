@extends('panel.layouts.master')
@section('title', 'چاپ پیش فاکتور')
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
     $holding = \App\Models\Holding::whereId($invoice->holding_id)->first();
//    dd($holding);
@endphp
@section('styles')
    <style>
        #products_table input, #products_table select {
            width: auto !important;
        }

        .title-sec {
            background: #ececec !important;
        }

        .main-content {
            margin: 0 !important;
        }

        .me-100 {
            margin-right: 100px !important;
        }

        @page {
            size: A4 landscape !important;
        }

        @media print {
            body {
                transform: scale(0.9) !important;
            }
        }

        body {
            padding: 0 !important;
        }

        main {
            padding: 0 !important;
        }

        table th, td {
            padding: 4px !important;
            border: 2px solid #000 !important;
            font-size: 16px !important;
        }

        table th {
            font-weight: bold !important;
        }

        table tr {
            padding: 0 !important;
            border: 2px solid #000 !important;
        }

        #printable_sec {
            padding: 0 !important;
        }

        .card {
            margin: 0 !important;
        }

        .guide_box {
            text-align: center !important;
        }

        #seller_sign_sec {
            position: relative !important;
        }

        #seller_sign_sec small {
            position: absolute !important;
        }

        #seller_sign_sec .sign {
            position: absolute !important;
            top: -60px !important;
            left: 34% !important;
            width: 10rem !important;
        }

        #seller_sign_sec .stamp {
            position: absolute !important;
            top: -18px !important;
            left: 35% !important;
            width: 12rem !important;
        }

        html, body, main {
            height: 100% !important;
        }

        .card {
            min-height: 100% !important;
            max-height: 130% !important;
        }

        .content-page {
            margin-right: 0 !important;
            overflow: unset !important;
            padding: 0 !important;
            min-height: 0 !important;
        }

        *{
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

@endsection
@section('content')
    <div class="card">
        <div class="card-body" id="printable_sec">
            <div class="card-title">
                <div class="row">
                    <div class="col-4">
                        <img src="/assets/images/header-logo.png" style="width: 15rem;">
                    </div>
                    <div class="col-3 text-end">
                        <h3>پیش فاکتور فروش کالا و خدمات</h3>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-2 text-center">
                        <p class="m-0"> شماره سریال: {{ $invoice->invoice_number??$invoice->id }}</p>
                        <hr class="mt-0">
                        <p class="m-0">تاریخ: {{ verta($invoice->created_at)->format('Y/m/d') }}</p>
                        <hr class="mt-0">
                    </div>
                </div>
            </div>
            <form action="" method="post">
                <div class="form-row">
                    <table class="table table-bordered mb-0">
                        <thead>
                        <tr>
                            <th class="text-center py-1 title-sec">مشخصات فروشنده</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <div class="mb-3">
                                        <span class="me-100">نام شخص حقیقی/حقوقی: {{$holding->name}}</span>
                                        <span class="me-100">شماره اقتصادی: {{$holding->commercial_code}}</span>
                                        <span class="me-100">شماره ثبت/شماره ملی: {{$holding->national_code}}</span>
                                        <span class="me-100">شناسه ملی: {{$holding->national_id}}</span>
                                    </div>
                                    <div>
                                        <span class="me-100">نشانی: {{$holding->address}}</span>
                                        <span class="me-100">کد پستی: {{$holding->zip_code}}</span>
                                        <span class="me-100">شماره تلفن:  {{$holding->phone_number2??''}} / {{$holding->phone_number1??''}}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered mb-4">
                        <thead>
                        <tr>
                            <th class="text-center py-1 title-sec">مشخصات خریدار</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">
                                <div class="mb-3">
                                    <span class="me-100">نام شخص حقیقی/حقوقی: {{ $invoice->customer_name }}</span>
                                    <span class="me-100">شماره اقتصادی: {{ $invoice->commercial_code }}</span>
                                    <span class="me-100">شماره ثبت/شماره ملی: {{ $invoice->national_code }}</span>
                                    <span class="me-100">استان: {{ $invoice->province }}</span>
                                </div>
                                <div>
                                    <span class="me-100">شهر: {{ $invoice->city }}</span>
                                    <span class="me-100">کد پستی: {{ $invoice->zip_code }}</span>
                                    <span class="me-100">نشانی: {{ $invoice->address }}</span>
                                    <span class="me-100">شماره تلفن: {{ $invoice->phone_number }}</span>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
{{--                    @dd(json_decode($invoice->products))--}}
                    <div class="col-12 mb-3">
                        <div>
                            <table class="table text-center" border="2">
                                <thead>
                                <tr>
                                    <th class="py-1 title-sec" colspan="12">مشخصات کالا یا خدمات مورد معامله</th>
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
                                {{-- artin products --}}
                                @foreach(json_decode($invoice->products) as $key => $item)
{{--                                    @dd($item)--}}
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
                                    <th class="py-1 title-sec" colspan="6">تخفیف نهایی</th>
                                    <th class="py-1 title-sec" colspan="6">مبلغ فاکتور پس از تخفیف نهایی</th>
                                </tr>
                                <tr>
                                    <td colspan="6">{{ number_format($invoice->discount) }}</td>
                                    <td colspan="6">{{ number_format($sum_invoice_net - $invoice->discount) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div class="d-flex">
                                            <span class="me-4">شرایط و نحوه فروش</span>
                                            <div class="d-flex">
                                                نقدی<input type="checkbox">
                                            </div>
                                            <div class="d-flex ml-5">
                                                غیر نقدی<input type="checkbox">
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="8" class="text-start">
                                         {{change_number_to_words($sum_invoice_net - $invoice->discount)}} ریال
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><small>توضیحات</small></td>
                                    <td colspan="10">{!! nl2br(e($invoice->description )) !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="12">
                                        خواهشمند است مبلغ فاكتور را به شماره شبا {{$holding->account_number}} نزد بانك {{$holding->bank_name}} شعبه {{$holding->branch_name}} واريز نماييد. با تشكر
                                        <br>
                                        <br>
                                        آدرس سایت  {{$holding->site_address}}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="6" id="seller_sign_sec">
                                        <img src="{{ asset('/assets/images/stamp.png') }}" class="stamp">
                                        <small>مهر و امضای فروشنده</small>
                                    </td>
                                    <td colspan="6"><small>مهر و امضای خریدار</small></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="pb-2 d-flex justify-content-between px-3" id="print_sec">
            <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="fa fa-chevron-right me-2"></i>برگشت</a>
{{--            <button class="btn btn-info" id="btn_print"><i class="fa fa-print me-2"></i>چاپ</button>--}}
            <form action="{{ route('pre-invoices.print') }}" method="post">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                <button class="btn btn-danger"><i class="fa fa-file-pdf me-2"></i>دانلود</button>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#btn_print').click(function () {
                $('#print_sec').addClass('d-none').removeClass('d-flex');
                $('.alert-info').addClass('d-none').removeClass('d-flex');
                window.print();
                $('#print_sec').removeClass('d-none').addClass('d-flex');
                $('.alert-info').removeClass('d-none').addClass('d-flex');
            })
        })
    </script>
@endsection

