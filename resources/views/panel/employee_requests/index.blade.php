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
                        <h4 class="page-title">درخواست {{$type_request}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                <div>
                                    <form action="{{ route('products.excel') }}" method="post" id="excel_form">
                                        @csrf
                                    </form>
                                    @can('employee-request-create')
                                        <a href="{{ route('employee-requests.create', ['type' => $type]) }}"
                                           class="btn btn-primary">
                                            <i class="fa fa-plus mr-2"></i>
                                            ثبت درخواست {{\App\Models\EmployeeRequest::REQUEST_FOR[$type]}}
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            <form action="{{ url()->current() }}" method="get" id="search_form">
                                <div class="row mb-3">
                                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                        <label for="code">شناسه درخواست</label>
                                        <input type="text" name="code" class="form-control" placeholder="مثال : 25698"
                                               value="{{ request()->code ?? '' }}" form="search_form">
                                    </div>
                                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                        <label for="title">عنوان درخواست</label>
                                        <input type="text" name="title" class="form-control" placeholder=""
                                               value="{{ request()->title ?? '' }}" form="search_form">
                                    </div>
                                    <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12 mt-2">
                                        <label for="status">وضعیت درخواست</label>
                                        <select name="status" class="form-control" id="status">
                                            <option selected disabled>انتخاب کنید..</option>
                                            @foreach(\App\Models\EmployeeRequest::STATUS as $key => $status)
                                                <option
                                                    value="{{ $key }}" {{ request()->status == $key ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="type" value="{{$type}}">
                                    <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12">
                                        <button type="submit" class="btn btn-primary mt-4" form="search_form">جستجو
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه درخواست</th>
                                        @can('employee-request-action')
                                            <th>همکار درخواست دهنده</th>
                                        @endcan
                                        <th>موضوع درخواست</th>
                                        <th>الویت درخواست</th>
                                        <th>وضعیت</th>
                                        <th>پاسخ دهنده</th>
                                        <th>تاریخ ثبت درخواست</th>
                                        <th>تاریخ پاسخ</th>

                                        <th>نمایش درخواست</th>
                                        {{--                                        @can('employee-request-action')--}}
                                        {{--                                            <th>اقدام</th>--}}
                                        {{--                                        @endcan--}}
                                        @can('employee-request-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('employee-request-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employeeRequests as $employeeRequest)
                                        <tr>
                                            <td>{{$loop->index + 1 }}</td>
                                            <td>{{ $employeeRequest->code }}</td>
                                            @can('employee-request-action')
                                                <td>
                                                    @if($employeeRequest->employee)
                                                        {{$employeeRequest->employee->fullname()}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endcan
                                            <td>{{ $employeeRequest->title }}</td>
                                            <td>{{  \App\Models\EmployeeRequest::PRIORITY[$employeeRequest->priority] }}</td>
                                            <td>
                                                @if($employeeRequest->status == 'pending')
                                                    <span
                                                        class="badge bg-warning">
                                                        {{\App\Models\EmployeeRequest::STATUS[$employeeRequest->status]}}
                                                    </span>
                                                @elseif($employeeRequest->status == 'approved')
                                                    <span class="badge bg-success">
                                                        {{\App\Models\EmployeeRequest::STATUS[$employeeRequest->status]}}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        {{\App\Models\EmployeeRequest::STATUS[$employeeRequest->status]}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($employeeRequest->approver)
                                                    {{$employeeRequest->approver->fullname()}}
                                                @else
                                                    -
                                                @endif
                                            </td>


                                            <td>
                                                @if($employeeRequest->answered_at)
                                                    {{verta($employeeRequest->created_at)->format('H:i Y/m/d')}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{verta($employeeRequest->answered_at)->format('H:i Y/m/d')}}

                                            </td>


                                            <td>
                                                <a href="{{ route('employee-requests.show', ['employee_request' => $employeeRequest->id, 'type' => $employeeRequest->type]) }}"
                                                   class="btn btn-info btn-floating">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>

                                            @can('employee-request-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating @if($employeeRequest->answered_at) disabled @endif"
                                                       href="{{ $employeeRequest->answered_at ? '#' : route('employee-requests.edit', $employeeRequest->id) }}"
                                                       @if($employeeRequest->answered_at)
                                                           disabled
                                                        @endif>
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan

                                            @can('employee-request-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ $employeeRequest->answered_at ? '#' : route('employee-requests.edit',$employeeRequest->id) }}"
                                                            data-id="{{ $employeeRequest->id }}"
                                                            @if($employeeRequest->answered_at) disabled @endif>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                        @endcan
                                        {{--                                        </tr>--}}
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div
                                class="d-flex justify-content-center">{{ $employeeRequests->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



