@extends('panel.layouts.master')
@section('title', 'حواله خروج')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">کالاهای خارج شده</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>کد انبار : 1</th>
                                        <th> کد تحویل گیرنده : {{$exitRemittance->customer->code??'-'}} </th>
                                        <th>شناسه خروج : {{$exitRemittance->code}}</th>
                                        <th>مسئول ثبت خروج : {{auth()->user()->fullName()}}</th>
                                    </tr>
                                    <tr>
                                        <th>عنوان انبار: مرکزی</th>
                                        <th>عنوان تحویل گیرنده : {{$exitRemittance->customer->name}}</th>
                                        <th> زمان ثبت حواله
                                            : {{verta($exitRemittance->created_at)->format('H:i   %Y/%m/%d')}}</th>
                                        <th>
                                            زمان خروج از
                                            انبار: {{verta($exitRemittance->exit_time)->format('H:i   %Y/%m/%d')}}
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
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
                                    <tr>
                                        <td colspan="8" class="text-start"><span
                                                class="text-start fw-bold"> مجموع : {{$sum}}</span></td>
                                    </tr>
                                    </tbody>

                                </table>

                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-bordered dataTable dtr-inline text-center">
                                            <thead>
                                            <tr>
                                                <th>ردیف</th>
                                                <th>شرح کالا</th>
                                                <th>شناسه رهگیری</th>
                                                <th>اقدام</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(json_decode($exitRemittance->tracking_codes) as $code)
                                                <tr>
                                                    <td>{{$loop->index + 1}}</td>
                                                    <td>{{\App\Models\Product::whereId($code->product_id)->first()->title??'-'}}</td>
                                                    <td>{{$code->code}}</td>
                                                    <td>
                                                        <button class="btn btn-danger show-return-dialog"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#returnModal"
                                                                data-tracking_code="{{$code->code}}"
                                                                data-order_id="{{$exitRemittance->order_id}}">مرجوع
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <a href="{{route('ExitFromWarehouse.downloadPDF',$exitRemittance->id)}}" class="btn btn-danger">
                                        <span class="fa fa-file-pdf"></span>
                                        پرینت برگه خروج
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <a href="/panel/out-of-warehouse" class="btn btn-secondary">بازگشت</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">
                        ثبت مرجوعی کالا به شناسه رهگیری <span id="tracking_code_section"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="بستن"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column position-relative">
                        <form action="{{route('storeReturnBackProduct.index')}}" method="post">
                            @csrf
                            <input type="hidden" name="tracking_code" id="tracking_code_input">
                            <input type="hidden" name="order_id" id="order_id_input">
                            <input type="hidden" name="exit_remittance" id="exit_remittance_input" value="{{$exitRemittance->id}}">
                            <div class="mt-2">
                                <label for="description">علت مرجوعی:</label>
                                <textarea name="description" class="form-control" required></textarea>

                            </div>
                            <div class="mt-2">
                                <input type="submit" class="btn btn-success" value="ثبت">

                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            const textarea = document.querySelector('textarea[name="description"]');

            textarea.addEventListener('invalid', function (event) {
                event.target.setCustomValidity('علت مرجوعی این کالا را بنویسید.');
            });

            textarea.addEventListener('input', function (event) {
                event.target.setCustomValidity('');
            });

            $(document).on('click', '.show-return-dialog', function () {
                var tracking_code = $(this).data('tracking_code');
                var order_id = $(this).data('order_id');
                $('#tracking_code_input').val(tracking_code);
                $('#order_id_input').val(order_id);
                $('#tracking_code_section').html(tracking_code);


            });
        });
    </script>
@endsection

