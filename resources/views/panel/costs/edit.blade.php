@extends('panel.layouts.master')
@section('title', 'ویرایش بهای تمام شده')
@section('styles')
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .processing {
            display: none;
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
                        <h4 class="page-title">ویرایش بهای تمام شده</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="{{ route('costs.update',$cost->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="product">
                                            نام کالا<span class="text-danger">*</span></label>
                                        <input type="text" name="product" id="product" class="form-control text-start"
                                               value="{{old('product',$cost->product)}}">
                                        @error('product')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="count">
                                            تعداد<span class="text-danger">*</span></label>
                                        <input type="text" name="count" class="form-control"
                                               value="{{old('count',$cost->count)}}">
                                        @error('count')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="price">
                                            مبلغ بدون مالیات و ارزش افزوده (ریال)<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" name="price" id="price" class="form-control"
                                               value="{{old('price',$cost->price)}}">
                                        <div class="text-center text-info d-block"
                                             id="price">{{old('price',$cost->price)?number_format(old('price',$cost->price)):''}}</div>
                                        @error('price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="Logistic_price">
                                            هزینه حمل و نقل (ریال)<span class="text-danger">*</span></label>
                                        <input type="number" name="Logistic_price" id="Logistic_price"
                                               class="form-control"
                                               value="{{old('Logistic_price',$cost->Logistic_price)}}">
                                        <div class="text-center text-info d-block"
                                             id="Logistic_price">{{old('Logistic_price',$cost->Logistic_price)?number_format(old('Logistic_price',$cost->Logistic_price)):''}}</div>
                                        @error('Logistic_price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="other_price">
                                            سایر هزینه ها (ریال)<span class="text-danger">*</span></label>
                                        <input type="number" name="other_price" id="other_price" class="form-control"
                                               value="{{old('other_price',$cost->other_price)}}">
                                        <div class="text-center text-info d-block"
                                             id="other_price">{{old('other_price',$cost->other_price)?number_format(old('other_price',$cost->other_price)):''}}</div>
                                        @error('price')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>


                                <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#priceInput').on('input', function () {
            let value = $(this).val().replace(/,/g, '');
            let formattedValue = '';

            if (value) {

                formattedValue = Number(value).toLocaleString();
                $('#price').text(formattedValue);
            } else {
                $('#price').text('');
            }
        });
    </script>
@endsection




