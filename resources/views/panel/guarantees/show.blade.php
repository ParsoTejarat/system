@extends('panel.layouts.master')
@section('title', 'نمایش گارانتی')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">نمایش گارانتی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
{{--                            <form action="{{ route('guarantees.update', $guarantee->id) }}" method="post">--}}
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="product">محصول<span class="text-danger">*</span></label>
                                        <select name="product" id="product_id" class="form-control"
                                                 readonly>
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Product::all() as $product)
                                                <option
                                                    value="{{$product->id}}" {{ $product->id == old('product', $guarantee->product_id) ? 'selected' : '' }}>
                                                    {{$product->title}}
                                                </option>
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
                                               value="{{ old('serial_number', $guarantee->serial_number) }}" readonly>
                                        @error('serial_number')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="product_identifier">شناسه کالا<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="product_identifier" class="form-control"
                                               id="product_identifier"
                                               value="{{ old('product_identifier', $guarantee->product_identifier) }}"
                                               readonly>
                                        @error('product_identifier')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="tracking_code">شناسه رهگیری کالا</label>
                                        <input type="text" name="tracking_code" class="form-control" id="tracking_code"
                                               value="{{ old('tracking_code', $guarantee->tracking_code) }}" readonly>
                                        @error('tracking_code')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="tracking_code">شرکت وارد کننده</label>
                                        <input type="text" name="importing_company" class="form-control"
                                               id="importing_company" value="{{$guarantee->importing_company}}"
                                               readonly>
                                        @error('importing_company')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="period">مدت گارانتی<span
                                                class="text-danger">*</span></label>
                                        <select name="period" class="form-control" id="period"
                                                readonly>
                                            @foreach(\App\Models\Guarantee::PERIOD as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ old('period', $guarantee->period) == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('period')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="status">وضعیت<span
                                                class="text-danger">*</span></label>
                                        <select name="status" class="form-control" id="status"
                                                readonly>
                                            @foreach(\App\Models\Guarantee::STATUS as $key => $value)

                                                <option
                                                    value="{{ $key }}" {{ old('status', $guarantee->status) == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label class="form-label" for="status">تاریخ انقضاء
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control date-picker-shamsi-list"
                                               name="expire_time" id="expire_time"
                                               value="{{ verta($guarantee->expire_time)->format('Y/m/d') }}" required
                                               readonly>
                                        @error('expire_time')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <a href="{{ url()->previous()}}" class="btn btn-secondary">بازگشت</a>
                                <a href="{{ route('guarantees.print', $guarantee->id)}}" class="btn btn-primary">چاپ
                                    <i class="fa fa-print"></i>
                                </a>
{{--                            </form>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

