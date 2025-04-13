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
                        <h4 class="page-title">نمایش درخواست {{$type_request}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                    <label for="title" class="form-label">موضوع درخواست<span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{ old('title', $employeeRequest->title) }}" readonly>
                                    @error('title')
                                    <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                    <label for="priority" class="form-label">اولویت <span
                                                class="text-danger">*</span></label>
                                    <select name="priority" id="priority" class="form-control" readonly>
                                        @foreach(\App\Models\EmployeeRequest::PRIORITY as $key => $value)
                                            <option
                                                    value="{{ $key }}" {{ old('priority', $employeeRequest->priority) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                    <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-2 col-xl-3 col-lg-3 col-md-3">

                                    <label for="file" class="form-label">درخواست دهنده</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{ old('title', $employeeRequest->employee->fullName()) }}" readonly>

                                </div>
                                @if(in_array($employeeRequest->type, ['request_petty_cash','request_transportation_cost', 'request_payment_order']))
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="file" class="form-label">مبلغ مورد نیاز(ریال)</label>
                                        <input type="number" class="form-control text-start" name="amount" id="amount"
                                               value="{{ old('amount',$employeeRequest->amount) }}" readonly>
                                        @error('amount')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                        <span id="amount_process"
                                              class="text-info">{{number_format($employeeRequest->amount)}}</span>
                                    </div>
                                @endif

                            </div>


                            <div class="row">
                                <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                    <label for="employee_description" class="form-label">متن درخواست <span
                                                class="text-danger">*</span></label>
                                    <textarea name="employee_description" id="employee_description" class="form-control"
                                              disabled>{{ old('employee_description', $employeeRequest->employee_description) }}</textarea>
                                    @error('employee_description')
                                    <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="row">
                                @if($employeeRequest->employee_attachment_path)
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3 d-flex align-items-end">
                                        <a href="{{ $employeeRequest->employee_attachment_path }}"
                                           class="btn btn-primary " download>
                                            <i class="fa fa-download me-1"></i> دانلود فایل ضمیمه
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{--                                <div class="row">--}}
                            {{--                                    <div class="mb-2 col-xl-9 col-lg-9 col-md-9">--}}
                            {{--                                        <input type="submit" class="btn btn-primary" value="ویرایش درخواست">--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}

                            @if(!$employeeRequest->answered_at)
                                @can('employee-request-action')
                                    <div class="mb-3 mt-4">
                                        <h5 class="fw-bold">بررسی نتیجه درخواست</h5>
                                    </div>
                                    <form action="{{route('employee-requests.action')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="employee_requests_id"
                                               value="{{$employeeRequest->id}}">
                                        <div class="row">
                                            <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                                <label for="priority" class="form-label">وضعیت: <span
                                                            class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control">
                                                    @foreach(\App\Models\EmployeeRequest::STATUS as $key => $value)
                                                        @if($key !== 'pending')
                                                            <option
                                                                    value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('status')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-xl-6 col-lg-6 col-md-6">
                                                <label for="approver_attachment_path" class="form-label">
                                                    فایل پیوست:
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="file" name="approver_attachment_path" class="form-control">
                                                @error('approver_attachment_path')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                                <label for="priority" class="form-label">
                                                    توضیحات :
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="approver_description" id="approver_description"
                                                          class="form-control"></textarea>
                                                @error('approver_description')
                                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                                @enderror

                                            </div>
                                            <div class="mb-2 col-xl-9 col-lg-9 col-md-9">
                                                <input type="submit" class="btn btn-primary" value="ثبت">
                                            </div>
                                        </div>
                                    </form>
                                @endcan
                            @else
                                <div class="mb-3 mt-4">
                                    <h5 class="fw-bold">نتیجه درخواست</h5>
                                </div>


                                <div class="row">
                                    {{-- وضعیت درخواست --}}
                                    <div class="mb-3 col-xl-3 col-lg-4 col-md-5">
                                        <label for="status" class="form-label">وضعیت</label>
                                        <input type="text" id="status" class="form-control
                                            @if($employeeRequest->status == 'approved')
                                                bg-success text-white
                                            @elseif($employeeRequest->status == 'not_approved')
                                                bg-danger text-white
                                            @elseif($employeeRequest->status == 'pending')
                                                bg-warning text-dark
                                            @endif
                                        "
                                               value="{{ \App\Models\EmployeeRequest::STATUS[$employeeRequest->status] }}" readonly>

                                    </div>

                                    {{-- فایل ضمیمه --}}
                                    @if($employeeRequest->approver_attachment_path)
                                        <div class="mb-3 col-xl-3 col-lg-4 col-md-5 d-flex align-items-end">
                                            <a href="{{ $employeeRequest->approver_attachment_path }}"
                                               class="btn btn-outline-primary w-100" download>
                                                <i class="fa fa-download me-1"></i> دانلود فایل ضمیمه
                                            </a>
                                        </div>
                                    @endif

                                    {{-- توضیحات --}}


                                </div>
                                <div class="row">
                                    <div class="mb-3 col-9">
                                        <label for="approver_description" class="form-label">توضیحات:</label>
                                        <textarea name="approver_description" id="approver_description"
                                                  class="form-control" rows="4"
                                                  readonly>{{ $employeeRequest->approver_description }}</textarea>
                                        @error('approver_description')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

