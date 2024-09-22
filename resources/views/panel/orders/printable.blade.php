<!DOCTYPE html>
<html lang="zxx" dir="rtl">
<head>
    <title>چاپ سفارش مشتری</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <!-- External CSS libraries -->

    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.min-invoice.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/style-invoice.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/font/primary-iran-yekan.css">
</head>
<body>

{{--@dd($order)--}}
<!-- Invoice 1 start -->
<div class="invoice-1 invoice-content" style="font-family: primary-font">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="invoice-inner clearfix">
                    <div class="invoice-info clearfix" id="invoice_wrapper">
                        <div class="invoice-headar">
                            <div class="row g-0">
                                <div class="col-sm-6">
                                    <div class="invoice-logo">
                                        <!-- logo started -->
                                        <div class="logo">
                                            <img src="https://parso.moshrefiholding.com/assets/images/logo-dark.png"
                                                 alt="logo">
                                        </div>
                                        <!-- logo ended -->
                                    </div>
                                </div>
                                <div class="col-sm-6 invoice-id">
                                    <div class="info">
                                        <h1 class="color-white inv-header-1">سفارش مشتری</h1>
                                        <p class="color-white mb-1"> شماره سفارش : {{$order->id}}</p>
                                        <p class="color-white mb-0"> تاریخ
                                            : {{verta($order->created_at)->format('%Y/%m/%d')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <h4 class="inv-title-1 mb-3">مشخصات مشتری</h4>
                                        <h2 class="name mb-10">نام شخص حقیقی/حقوقی : {{$order->customer->name}}</h2>
                                        <p class="invo-addr-1">
                                            {{$order->customer->city}} {{$order->customer->province}} <br/>
                                            شماره ثبت/ملی : {{$order->customer->national_number}} <br/>
                                            کد پستی : {{$order->customer->postal_code}} <br/>
                                            شماره تماس : {{$order->customer->phone1}} <br/>
                                            {{$order->customer->address1}} <br/>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-center">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped invoice-table">
                                    <thead class="bg-active">
                                    <tr class="tr">
                                        <th class="pl0 text-end">ردیف</th>
                                        <th class="pl0 text-end">کالا</th>
                                        <th class="text-center">تعداد</th>
                                        <th class="text-center">قیمت</th>
                                        <th class="text-start">جمع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--                            @dd(json_decode($order->products))--}}
                                    @foreach(array_merge(json_decode($order->products)->products, json_decode($order->products)->other_products) as $product)
                                        <tr class="tr">
                                            <td>
                                                <div class="item-desc-1 text-end">
                                                    <span>{{$loop->index + 1}}</span>
                                                </div>
                                            </td>
                                            <td class="pl0">{{$product->product}}</td>
                                            <!-- Assuming 'name' field exists in product -->
                                            <td class="text-center">{{$product->quantity}}</td>
                                            <td class="text-center">{{$product->price}}</td>
                                            <td class="text-start">{{$product->quantity * $product->price}}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center f-w-600 active-color">Grand Total</td>
                                        <td class="f-w-600 text-start active-color">$795.99</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-bottom">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-sm-7">
                                    <div class="mb-30 dear-client">
                                        <h3 class="inv-title-1">توضیحات</h3>
                                        <p>توضیحات تستی</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>چاپ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Invoice 1 end -->

</body>
</html>
