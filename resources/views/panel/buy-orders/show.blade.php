@extends('panel.layouts.master')
@section('title', 'مشاهده سفارش خرید')
@section('styles')
    <style>
        table tbody tr td input {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مشاهده سفارش خرید</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            @if(auth()->user()->isAccountant())
                                <div class="alert alert-info mb-5">
                                    <i class="fa fa-info-circle font-size-20 align-middle"></i>
                                    <strong>توجه!</strong>
                                    قیمت محصولات با احتساب (مالیات ،ارزش افزوده و...) وارد شده است.
                                </div>
                            @else
                                <div class="alert alert-info mb-5">
                                    <i class="fa fa-info-circle font-size-20 align-middle"></i>
                                    <strong>توجه!</strong>
                                    قیمت محصولات مورد نظر باید با احتساب (مالیات ،ارزش افزوده و...) وارد شوند.
                                </div>
                            @endif
                            <div class="card-title d-flex justify-content-end mb-4">
                                <div>
                                    @if($buyOrder->status == 'bought')
                                        <span
                                            class="badge badge-success">{{ \App\Models\BuyOrder::STATUS['bought'] }}</span>
                                    @else
                                        <span
                                            class="badge badge-warning">{{ \App\Models\BuyOrder::STATUS['orders'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                    <label class="form-label" for="order">شماره سفارش<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control text-start" id="order"
                                           value="{{$buyOrder->order->code}}" name="order" readonly>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class="form-label" for="customer_id">مشتری</label>
                                    <input type="text" class="form-control"
                                           value="{{ $buyOrder->customer->code.' - '.$buyOrder->customer->name }}"
                                           readonly>

                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class="form-label" for="customer_id">فروشنده</label>
                                    <input type="text" class="form-control"
                                           value="{{$buyOrder->seller}}"
                                           readonly>

                                </div>

                                @if($buyOrder->invoice != null)
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <br>
                                        <a href="{{ $buyOrder->invoice }}" class="btn btn-primary"
                                           style="margin-top: 8px" download>دانلود فایل پیش فاکتور</a>
                                    </div>
                                @endif


                                <div class="col-12 mb-3">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead class="table-primary">
                                        <tr>
                                            <th>عنوان کالا</th>
                                            <th>تعداد</th>
                                            <th>قیمت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(json_decode($buyOrder->items) as $item)
                                            <tr>
                                                <td>{{ $item->product }}</td>
                                                <td>{{ number_format($item->price) }}</td>
                                                <td>{{ number_format($item->count) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr></tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label" for="description">توضیحات</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"
                                              disabled>{{ $buyOrder->description }}</textarea>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">

                                    @if($buyOrder->receipt == null)
                                        @if(auth()->user()->isAccountant())
                                            <label class="form-label" for="description">آپلود رسید</label>
                                            <form action="{{route('buy-orders.upload.receipt',$buyOrder->id)}}"
                                                  method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" class="form-control" name="receipt"
                                                       accept="application/pdf">
                                                <input type="submit" class="btn btn-primary mt-2" value="آپلود">
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{$buyOrder->receipt}}" class="btn btn-primary mt-4" download>دانلود
                                            فایل رسید</a>
                                        <br>
                                        @if(auth()->user()->isAccountant())
                                            <a href="#factorResetModal" class="btn btn-danger mt-2"
                                               data-bs-toggle="modal">حذف فایل رسید</a>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="factorResetModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="factorResetModalLabel">تایید حذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>می خواهید فایل رسید سفارش خرید را حذف و مجدد بارگذاری کنید؟</h6>
                    <form action="{{ route('buy-orders.delete.receipt', $buyOrder) }}" method="post"
                          id="deleteReceiptAction">
                        @csrf
                        @method('post')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                    <button type="submit" class="btn btn-danger" form="deleteReceiptAction">حذف</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            // add item
            $(document).on('click', '#btn_add', function () {
                $('table tbody').append(`
                    <tr>
                        <td><input type="text" class="form-control" name="products[]" required></td>
                        <td><input type="number" class="form-control" name="counts[]" min="1" value="1" required></td>
                        <td><button type="button" class="btn btn-danger btn-floating btn_remove"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `)
            })

            // remove item
            $(document).on('click', '.btn_remove', function () {
                $(this).parent().parent().remove()
            })
        })
    </script>
@endsection
