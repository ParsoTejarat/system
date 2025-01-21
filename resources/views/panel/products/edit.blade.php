@extends('panel.layouts.master')
@section('title', 'ویرایش محصول')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش محصول</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('products.update', $product->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان محصول <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               value="{{ $product->title }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="sku" class="form-label">کد محصول (sku)<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sku" id="sku"
                                               value="{{ $product->sku }}">
                                        @error('sku')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="code" class="form-label">کد حسابداری <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="code" id="code"
                                               value="{{ $product->code }}">
                                        @error('code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="category" class="form-label">دسته بندی <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="category" id="category"
                                                data-toggle="select2">
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option
                                                    value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                               value="{{ $product->single_price }}">
                                        <small id="single_price_words" class="text-primary"></small>
                                        @error('single_price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="brand_id" class="form-label">برند <span class="text-danger">*</span></label>
                                        <select class="form-control" name="brand_id" id="brand_id"
                                                data-toggle="select2">
                                            @foreach(\App\Models\Brand::all() as $brand)
                                                <option
                                                    value="{{ $brand->id }}" {{ old('brand_id',$product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

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

            // when document was ready
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
                console.log(categoryId)
                if (categoryId) {
                    $.ajax({
                        url: `/panel/categories/${categoryId}/brands`,
                        type: 'GET',
                        success: function (data) {
                            $('#brand_id').empty();
                            $('#brand_id').append('<option selected disabled>برند را انتخاب کنید</option>');
                            $.each(data, function (index, brand) {
                                $('#brand_id').append(`<option value="${brand.id}">${brand.name}</option>`);
                            });
                        }
                    });
                } else {
                    $('#brand_id').empty().append('<option value="">ابتدا دسته‌بندی را انتخاب کنید</option>');
                }
            });




        });
    </script>
@endsection
