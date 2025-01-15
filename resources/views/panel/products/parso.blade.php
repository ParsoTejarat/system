@extends('panel.layouts.master')
@section('title', 'محصولات سایت پرسو تجارت')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h4>محصولات پرسو تجارت ایرانیان</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center" id="products_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان محصول</th>
                        <th>کد محصول</th>
                        <th>کد حسابداری</th>
                        <th>موجودی</th>
                        <th>قیمت</th>
                        <th>وضعیت</th>
                        <th>تاریخ ایجاد</th>
                        <th>ویرایش قیمت</th>

                    </thead>
                    <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $product->post_title }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->code_accounting ?? 'نامشخص' }}</td>
                            <td>{{ $product->stock_quantity ?? '0' }}</td>
                            <td>{{ number_format($product->min_price) . ' تومان' }}</td>
                            <td>
                                @if($product->post_status == 'publish')
                                    <span class="badge bg-success">منتشر شده</span>
                                @elseif($product->post_status == 'draft')
                                    <span class="badge bg-warning">پیش نویس</span>
                                @else
                                    <span class="badge bg-warning">نامشخص</span>
                                @endif
                            </td>
                            <td>{{ verta($product->post_date)->format('H:i - Y/m/d') }}</td>
                            {{--                            @can('artin-products-edit')--}}
                            <td>
                                <button class="btn btn-warning btn-floating btn_edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#timelineModal"
                                        data-id="{{ $product->ID }}"
                                        data-title="{{ $product->post_title }}"
                                        data-price="{{ $product->min_price }}">
                                    <i class="fa fa-edit"></i>
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
            @if($page > 1)
                <a href="{{ route('barman.index', ['page' => $page - 1]) }}" class="btn btn-primary">صفحه قبلی</a>
                <a class="btn btn-primary">{{$page}}</a>
            @endif

            <a href="{{ route('parso.index', ['page' => $page + 1]) }}" class="btn btn-primary">صفحه بعدی</a>
        </div>
    </div>

    <div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timelineModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column position-relative">
                        <form action="{{route('parsoUpdatePrice.edit')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="mb-2">قیمت (تومان)</label>
                                <input type="text" name="price" id="price" class="form-control">
                                <input type="hidden" name="product_id" id="product_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary mt-2" value="ثبت">
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
            $('.btn_edit').on('click', function () {
                let productId = $(this).data('id');
                let productTitle = $(this).data('title');
                let productPrice = $(this).data('price');

                $('#timelineModalLabel').text(productTitle);
                $('#product_id').val(productId);
                $('#price').val(isNaN(parseInt(productPrice)) ? 0 : parseInt(productPrice));
            });
        });
    </script>
@endsection



