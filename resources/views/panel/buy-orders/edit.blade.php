@extends('panel.layouts.master')
@section('title', 'ویرایش سفارش خرید')
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
                        <h4 class="page-title">ویرایش سفارش خرید</h4>
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
                            <form action="{{ route('buy-orders.update', $buyOrder->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="order">شماره سفارش<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control text-start" id="order"
                                               value="{{$buyOrder->order->code}}" name="order" readonly>
                                        <div class="invalid-feedback text-info d-block" id="process_desc"></div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="customer_id">مشتری <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               value="{{ $buyOrder->customer->code.' - '.$buyOrder->customer->name }}"
                                               readonly>
                                        <input type="hidden" name="customer_id" class="form-control"
                                               value="{{ $buyOrder->customer_id}}">
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="seller">فروشنده <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="seller" value="{{ $buyOrder->seller }}">
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="seller">پیش فاکتور <span class="text-danger">(PDF)</span></label>
                                        <br>
                                        @if($buyOrder->invoice)
                                            <a href="{{ $buyOrder->invoice }}" class="btn btn-primary mb-2" download>دانلود فایل پیش فاکتور</a>
                                            <br>
                                            <a href="#factorResetModal" class="text-danger" data-bs-toggle="modal" >حذف فایل پیش فاکتور</a>
                                        @else
                                            <input type="file" name="pre_invoice" id="pre_invoice" class="form-control" accept="application/pdf">
                                        @endif

                                        @error('pre_invoice')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-success mb-3" id="btn_add">
                                            <i class="fa fa-plus mr-2"></i>
                                            افزودن کالا
                                        </button>
                                    </div>
                                    @error('products')
                                    <h6 class="text-danger text-center d-block">{{ $message }}</h6>
                                    @enderror
                                    <table class="table table-striped table-bordered text-center">
                                        <thead class="table-primary">
                                        <tr>
                                            <th>عنوان کالا</th>
                                            <th>تعداد</th>
                                            <th>قیمت</th>
                                            <th>حذف</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($buyOrder->items)
                                            @foreach(json_decode($buyOrder->items) as $item)
                                                <tr>
                                                    <td><input type="text" class="form-control" name="products[]"
                                                               value="{{ $item->product }}" required readonly></td>
                                                    <td><input type="number" class="form-control" name="counts[]"
                                                               min="1"
                                                               value="{{ $item->count }}" required></td>
                                                    <td><input type="number" class="form-control" name="prices[]"
                                                               min="1"
                                                               value="{{ $item->price }}" required></td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-danger btn-floating btn_remove"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" class="form-control" name="products[]"
                                                           placeholder="HP 05A"
                                                           required></td>
                                                <td><input type="number" class="form-control" name="counts[]" min="1"
                                                           value="1"
                                                           required></td>
                                                <td>
                                                    <button type="button"
                                                            class="btn btn-danger btn-floating btn_remove"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr></tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label" for="description">توضیحات</label>
                                    <textarea name="description" id="description" class="form-control"
                                              rows="5">{{ $buyOrder->description }}</textarea>
                                </div>

                                <button class="btn btn-primary mt-5" type="submit">ثبت فرم</button>
                            </form>
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
                        <h6>می خواهید فایل پیش فاکتور را حذف و مجدد بارگذاری کنید؟</h6>
                        <form action="{{ route('buy-orders.delete.invoice', $buyOrder) }}" method="post" id="deleteFactorAction">
                            @csrf
                            @method('post')
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
                        <button type="submit" class="btn btn-danger" form="deleteFactorAction">حذف</button>
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
                        <td><input type="number" class="form-control" name="counts[]" value="0" required></td>
                        <td><input type="number" class="form-control" name="prices[]"  value="0" required></td>
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
