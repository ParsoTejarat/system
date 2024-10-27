@extends('panel.layouts.master')
@section('title', 'ثبت سفارش خرید')
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
                        <h4 class="page-title">ثبت سفارش خرید</h4>
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
                            <form action="{{ route('buy-orders.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="order">شماره سفارش<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="order" name="order">
                                        <div class="invalid-feedback text-info d-block" id="process_desc"></div>

                                        @error('order')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="customer_id">مشتری</label>
                                        <input type="text" name="customer" id="customer" class="form-control">
                                        <input type="hidden" name="customer_id" id="customer_id">
                                        @error('customer_id')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="seller">فروشنده</label>
                                        <input type="text" name="seller" id="seller" class="form-control">
                                        @error('seller')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="seller">پیش فاکتور<span class="text-danger">(فایل PDF) *</span></label>
                                        <input type="file" name="pre_invoice" id="pre_invoice" class="form-control" accept="application/pdf">
                                        @error('pre_invoice')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
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
                                            @if(old('products'))
                                                @foreach(old('products') as $key => $product)
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="products[]"
                                                                   value="{{ $product }}" required></td>
                                                        <td><input type="number" class="form-control" name="counts[]"
                                                                   min="1"
                                                                   value="{{ old('counts')[$key] }}" required></td>
                                                        <td><input type="number" class="form-control" name="prices[]"
                                                                   value="{{ old('prices')[$key] }}" required></td>
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
                                                    <td><input type="number" class="form-control" name="counts[]"
                                                               min="1" value="1"
                                                               required></td>
                                                    <td><input type="number" class="form-control" name="prices[]"
                                                               value="0"
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
                                                  rows="5">{{ old('description') }}</textarea>
                                        <span class="text-info fst-italic">خط بعد Enter</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-5" type="submit">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
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
                        <td><input type="number" class="form-control" name="prices[]" value="0" required></td>
                        <td><button type="button" class="btn btn-danger btn-floating btn_remove"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `)
            })


            $(document).on('click', '.btn_remove', function () {
                $(this).parent().parent().remove()
            });

            $(document).on('input', '#order', function () {
                var inputVal = $(this).val().trim();
                var processDesc = $('#process_desc');
                if (inputVal === '') {
                    $('#customer, #customer_id').val('');
                    $('table tbody').empty();
                    processDesc.empty();
                    return;
                }
                $.ajax({
                    url: '/panel/get-customer-order/' + $(this).val(),
                    method: 'GET',
                    beforeSend: function () {
                        processDesc.empty();
                        processDesc.html('در حال پردازش');
                    },
                    success: function (response) {
                        handleResponse(response);
                    },
                    error: function (xhr, status, error) {
                        processDesc.hide();
                        processDesc.empty();
                        console.error('خطا در ارسال درخواست:', error);
                    }
                });


            });


            function handleResponse(response) {
                var processDesc = $('#process_desc');
                if (response.status === 'success') {
                    $('#customer').val(response.data.customer.name)
                    $('#customer_id').val(response.data.customer.id)
                    $('table tbody').empty();
                    add_products(response.data.order);
                    processDesc.html("<span class='text-success'>تایید ✓</span>");
                } else {
                    $('#customer, #customer_id').val('');
                    $('table tbody').empty();
                    processDesc.html("<span class='text-danger'>شناسه پیگیری یافت نشد</span>");
                }

            }

            function add_products($data) {
                $data.forEach(item => {
                    $('table tbody').append(`
                    <tr>
                        <td><input type="text" class="form-control" name="products[]" value="${item.title}" required readonly></td>
                        <td><input type="number" class="form-control" name="counts[]" value="0" required></td>
                        <td><input type="number" class="form-control" name="prices[]" value="0" required></td>
                        <td><button type="button" class="btn btn-danger btn-floating btn_remove"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `);
                });
            }

        });
    </script>
@endsection
