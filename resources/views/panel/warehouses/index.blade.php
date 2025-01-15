@extends('panel.layouts.master')
@section('title', 'انبار')
@section('styles')
    <style>
        .supply-less {
            background-color: #ffda346e;
        }

        .supply-zero {
            background-color: #ff000033;
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
                        <h4 class="page-title">انبار</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">

                                <a href="#" class="btn btn-success mr-2">
                                    <i class="fa fa-file-excel mr-2"></i>
                                    خروجی اکسل انبار
                                </a>
                                <a href="{{route('wareHouseStockPrinter.downloadPDF')}}" class="btn btn-danger mr-2">
                                    <i class="fa fa-file-excel mr-2"></i>
                                    پرینت انبار
                                </a>

                            </div>

                            <form action="{{ route('warehouses.index') }}" method="get" class="mt-2 mb-2">
                                <div class="row">
                                    <div class="col-2">
                                        <label for="order">شناسه کالا</label>
                                        <input type="text" name="sku"
                                               value="{{old('sku',request()->get('sku'))}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <label for="order">دسته بندی</label>
                                        <select name="category_id" id="category_id" data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="order">برند</label>
                                        <select name="brand_id" id="brand_id" data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Brand::all() as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>
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
                                        <th>شناسه کالا</th>
                                        <th>شرح کالا</th>
                                        <th>دسته بندی کالا</th>
                                        <th>برند کالا</th>
                                        <th>موجودی کالا</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('import-products-id')
                                            <th>افزودن شناسه رهگیری</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr class="{{ $product->tracking_codes_count == 0 ? 'supply-zero' : ($product->tracking_codes_count < 10 && $product->tracking_codes_count > 0 ? 'supply-less' : '') }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->category->name??'بدون دسته بندی!' }}</td>
                                            <td>{{ $product->brand->name??'بدون برند' }}</td>
                                            <td>{{ $product->tracking_codes_count }}</td>
                                            <td>{{ verta($product->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('import-products-id')
                                                <td>
                                                    <a href="#"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#importExcel"
                                                       data-id="{{$product->id}}"
                                                       class="btn importExcel btn-success fa fa-file-excel mr-2 btn-floating"></a>
                                                </td>
                                            @endcan

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
                                class="d-flex justify-content-center">{{ $products->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="importExcel" tabindex="-1" aria-labelledby="importExcelLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelLabel">افزودن فایل اکسل
                        <a href="{{asset('excel-sample.xlsx')}}" download>دانلود نمونه فایل اکسل</a>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadExcelForm" action="/panel/import-tracking-excel-file" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <label for="file">فایل اکسل</label>
                        <input type="file" class="form-control mb-2" name="file" id="file" accept=".xlsx, .xls">
                        <input type="hidden" class="form-control mb-2" id="product_id" value="" name="product_id">
                        <button type="submit" class="btn btn-success mt2" id="uploadExcelBtn">آپلود</button>
                    </form>
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
            $(document).on('click', '.importExcel', function () {
                var id = $(this).data('id');
                console.log(id);
                $('#product_id').val(id);
            });
        });
    </script>
@endsection

