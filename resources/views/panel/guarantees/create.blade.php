@extends('panel.layouts.master')
@section('title', 'ایجاد گارانتی')
@section('styles')
    <style>
        .btn_remove {
            cursor: pointer;
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
                        <h4 class="page-title">ثبت گارانتی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('guarantees.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="product">محصول<span class="text-danger">*</span></label>
                                        <select name="product" id="product_id" class="form-control"
                                                data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Product::all() as $product)
                                                <option value="{{$product->id}}">{{$product->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="serial_number">شماره سریال<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="serial_number" class="form-control" id="serial_number"
                                               value="">
                                        @error('serial_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="serial_number">شناسه کالا<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="product_identifier" class="form-control"
                                               id="product_identifier"
                                               value="">
                                        @error('product_identifier')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="tracking_code">شناسه رهگیری کالا</label>
                                        <input type="text" name="tracking_code" class="form-control" id="tracking_code"
                                               value="">
                                        @error('tracking_code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="period">مدت گارانتی<span
                                                class="text-danger">*</span></label>
                                        <select name="period" class="form-control" id="period" data-toggle="select2">
                                            @foreach(\App\Models\Guarantee::PERIOD as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('period') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('period')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="tracking_code">
                                            شرکت وارد کننده
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="importing_company" class="form-control"
                                               id="importing_company">
                                        @error('importing_company')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                                <button class="btn btn-primary" type="submit">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
