@extends('panel.layouts.master')
@php
    $type_request = \App\Models\EmployeeRequest::REQUEST_FOR[$type]
@endphp
@section('title', 'درخواست ' . $type_request)

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ثبت درخواست {{$type_request}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('employee-requests.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="{{$type}}">
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">موضوع درخواست<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               value="{{ old('title') }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="priority" class="form-label">
                                            الویت
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="priority" id="priority" class="form-control">
                                            @foreach(\App\Models\EmployeeRequest::PRIORITY as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="file" class="form-label">فایل پیوست</label>
                                        <input type="file" class="form-control" name="file" id="file"
                                               value="{{ old('file') }}">
                                        @error('file')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                        <label for="employee_description" class="form-label">

                                            متن درخواست
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="employee_description" id="employee_description" class="form-control"></textarea>
                                        @error('employee_description')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                        <input type="submit" class="btn btn-success" value="ثبت درخواست">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
