@extends('panel.layouts.master')
@section('title', 'مرجوعی ها')
@section('styles')

@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            کالاهای مرجوع شده
                        </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">

                                @can('exit-remittance-excel')
                                    <a href="#" class="btn btn-success">
                                        <i class="fa fa-file-excel mr-2"></i>
                                        مرجوع شده ها
                                    </a>
                                @endcan


                            </div>
                            <form action="{{ route('showAllReturnBackProduct.index') }}" method="get" class="mt-2 mb-2">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="order">شناسه سفارش</label>
                                        <input type="text" name="order_code"
                                               value="{{old('order_code',request()->get('order_code'))}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="order">شناسه رهگیری</label>
                                        <input type="text" name="tracking_code"
                                               value="{{old('tracking_code',request()->get('tracking_code'))}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <div>&nbsp;</div>
                                        <input type="submit" class="btn btn-info" value="جستجو">
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه سفارش</th>
                                        <th>شناسه رهگیری کالا</th>
                                        <th>شرح کالا</th>
                                        <th>همکار ثبت مرجوعی</th>
                                        <th>تاریخ مرجوعی</th>
                                        <th>علت مرجوعی</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($returnedProducts as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                <a href="/panel/orders?code={{$item->order->code}}">{{ $item->order->code }}</a>
                                            </td>
                                            <td>{{ $item->tracking_code }}</td>
                                            <td>{{ $item->product->title }}</td>
                                            <td>{{ $item->user->fullname()??'-' }}</td>


                                            <td>{{verta($item->created_at)->format('H:i - Y/m/d')}}</td>
                                            <td>
                                                <button class="btn btn-info show-return-dialog" data-bs-toggle="modal"
                                                        data-bs-target="#returnModal" data-desc="{{$item->description}}"
                                                        data-tracking_code="{{$item->tracking_code}}">مشاهده
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div
                                    class="d-flex justify-content-center">{{ $returnedProducts->appends(request()->all())->links() }}</div>
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
                        علت مرجوعی کالا به شناسه رهگیری <span id="tracking_code_section"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="بستن"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column position-relative">
                        <div class="mt-2">
                            <label for="description">علت مرجوعی:</label>
                            <textarea name="description" rows="10" class="form-control" id="description_input"
                                      readonly></textarea>

                        </div>
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
            $(document).on('click', '.show-return-dialog', function () {
                var tracking_code = $(this).data('tracking_code');
                var desc = $(this).data('desc');
                $('#description_input').val(desc);
                $('#tracking_code_section').html(tracking_code);


            });
        });
    </script>
@endsection
