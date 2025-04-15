@extends('panel.layouts.master')
@section('title', 'ایجاد محصول')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد محصول</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('products.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان محصول <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               value="{{ old('title') }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="sku" class="form-label">شناسه (sku)<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sku" id="sku"
                                               value="{{ old('sku') }}">
                                        @error('sku')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="code" class="form-label">کد حسابداری <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" id="code"
                                               value="{{ old('code',0) }}">
                                        @error('code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="code" class="form-label">شناسه کالا <i>(اختیاری)</i></label>
                                        <input type="text" class="form-control" name="product_barcode" id="product_barcode"
                                               value="{{ old('product_barcode') }}">
                                        @error('product_barcode')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="category" class="form-label">دسته بندی <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category"
                                                data-toggle="select2">
                                            <option selected disabled>انتخاب کنید</option>
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option
                                                    value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="single_price" class="form-label">قیمت (ریال) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="single_price" id="single_price"
                                               value="{{ old('single_price') }}">
                                        <small id="single_price_words" class="text-primary"></small>
                                        @error('single_price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="brand_id" class="form-label">برند <span class="text-danger">*</span></label>
                                        <select class="form-control" name="brand_id" id="brand_id" data-toggle="select2">
                                            <option value="">ابتدا دسته‌بندی را انتخاب کنید</option>
                                            @if($oldBrand)
                                                <option value="{{ $oldBrand->id }}" selected>{{ $oldBrand->name }}</option>
                                            @endif
                                        </select>
                                        @error('brand_id')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <input type="hidden" id="old_brand_id" value="{{ old('brand_id') }}">

                                </div>
                                <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('/assets/js/number2word.js') }}" type="text/javascript"></script>
    <script>
        var number2Word = new Number2Word();

        $(document).ready(function () {
            // Number To Words

            let single_price = number2Word.numberToWords($('#single_price').val()) + ' ریال '
            $('#single_price_words').text(single_price)

            // when change the inputs

            $(document).on('keyup', '#single_price', function () {
                let price = number2Word.numberToWords(this.value) + ' ریال '
                $('#single_price_words').text(price)
            })
            // end Number To Words

            $('#category').change(function () {
                let categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: `/panel/categories/${categoryId}/brands`,
                        type: 'GET',
                        success: function (data) {
                            let oldBrandId = $('#old_brand_id').val();
                            let oldBrandName = $('#brand_id option:selected').text();

                            $('#brand_id').empty();
                            $('#brand_id').append('<option selected disabled>برند را انتخاب کنید</option>');

                            let brandExists = false;

                            $.each(data, function (index, brand) {
                                if (brand.id == oldBrandId) {
                                    brandExists = true;
                                }
                                $('#brand_id').append(`<option value="${brand.id}">${brand.name}-${brand.name_en}</option>`);
                            });

                            if (oldBrandId && !brandExists) {
                                $('#brand_id').append(`<option value="${oldBrandId}" selected>${oldBrandName}</option>`);
                            }

                            if (oldBrandId) {
                                $('#brand_id').val(oldBrandId);
                            }
                        }
                    });
                } else {
                    $('#brand_id').empty().append('<option value="">ابتدا دسته‌بندی را انتخاب کنید</option>');
                }
            });


            let oldBrandId = $('#old_brand_id').val();
            if (oldBrandId) {
                $('#brand_id').val(oldBrandId);
            }

        })
    </script>
@endsection
