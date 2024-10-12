@extends('panel.layouts.master')
@section('title', 'ایجاد بسته ارسالی')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد بسته ارسالی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('transfers.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-3 mb-3">
                                        <label class="form-label" for="recipient_name">گیرنده </label>
                                        <input type="text" name="recipient_name" class="form-control" id="recipient_name">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 mb-3">
                                        <label class="form-label" for="phone">شماره تماس </label>
                                        <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone') }}">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-3 mb-3">
                                        <label class="form-label" for="zip_code">کد پستی </label>
                                        <input type="text" name="zip_code" class="form-control" id="zip_code" value="{{ old('zip_code') }}">
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                        <label class="form-label" for="address">آدرس </label>
                                        <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}">
                                    </div>



                                </div>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary" type="submit">ثبت فرم</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

