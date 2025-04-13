@extends('panel.layouts.master')

@php
    $type_request = \App\Models\EmployeeRequest::REQUEST_FOR[$employeeRequest->type]
@endphp

@section('title', 'ویرایش درخواست ' . $type_request)

@section('content')
    <div class="content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش درخواست {{$type_request}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('employee-requests.update', $employeeRequest->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="type" value="{{ $employeeRequest->type }}">

                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">موضوع درخواست<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               value="{{ old('title', $employeeRequest->title) }}">
                                        @error('title')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="priority" class="form-label">اولویت <span class="text-danger">*</span></label>
                                        <select name="priority" id="priority" class="form-control">
                                            @foreach(\App\Models\EmployeeRequest::PRIORITY as $key => $value)
                                                <option value="{{ $key }}" {{ old('priority', $employeeRequest->priority) == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="file" class="form-label">فایل پیوست</label>
                                        <input type="file" class="form-control" name="file" id="file">
                                        @if($employeeRequest->employee_attachment_path)
                                            <a href="{{$employeeRequest->employee_attachment_path}}" download>دانلود فایل ضمیمه</a>
                                        @endif

                                        @error('file')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    @if(in_array($employeeRequest->type, ['request_petty_cash','request_transportation_cost', 'request_payment_order']))
                                        <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                            <label for="file" class="form-label">مبلغ مورد نیاز(ریال)</label>
                                            <input type="number" class="form-control text-start" name="amount" id="amount"
                                                   value="{{ old('amount',$employeeRequest->amount) }}">
                                            @error('amount')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                            @enderror
                                            <span id="amount_process" class="text-info">{{number_format($employeeRequest->amount)}}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                        <label for="employee_description" class="form-label">متن درخواست <span class="text-danger">*</span></label>
                                        <textarea name="employee_description" id="employee_description" class="form-control">{{ old('employee_description', $employeeRequest->employee_description) }}</textarea>
                                        @error('employee_description')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                        <input type="submit" class="btn btn-primary" value="ویرایش درخواست">
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
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#amount').on('input', function () {
                let amount = $(this).val().replace(/,/g, '');
                if (amount) {
                    let formatted = new Intl.NumberFormat('fa-IR').format(amount);
                    $('#amount_process').text(formatted);
                } else {
                    $('#amount_process').text('');
                }
            });
        });
    </script>
@endsection
