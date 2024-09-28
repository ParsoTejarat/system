@extends('panel.layouts.master')
@section('title', 'سفارشات مشتری')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">سفارشات مشتری</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">

                            @cannot('accountant')
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle font-size-20 align-middle"></i>
                                    <strong>توجه!</strong>
                                    درصورت نیاز به تایید پیش فاکتور توسط شما، دکمه اقدام فعال خواهد شد
                                </div>
                            @endcannot
                            <div class="card-title d-flex justify-content-end">
                                <div>
                                    <form action="{{ route('orders.excel') }}" method="post" id="excel_form">
                                        @csrf
                                    </form>

                                    <button class="btn btn-success" form="excel_form">
                                        <i class="fa fa-file-excel mr-2"></i>
                                        دریافت اکسل
                                    </button>

                                    @can('customer-order-list')
                                        @cannot('accountant')
                                            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                                                <i class="fa fa-plus mr-2"></i>
                                                ایجاد سفارش
                                            </a>
                                        @endcannot
                                    @endcan
                                </div>
                            </div>
                            <form action="{{ route('orders.search') }}" method="get" id="search_form"></form>
                            <div class="row mb-3 mt-5">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="customer_id" form="search_form" class="form-control"
                                            data-toggle="select2">
                                        <option value="all">خریدار (همه)</option>
                                        @foreach($customers as $customer)
                                            <option
                                                value="{{ $customer->id }}" {{ request()->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="province" form="search_form" class="form-control"
                                            data-toggle="select2">
                                        <option value="all">استان (همه)</option>
                                        @foreach(\App\Models\Province::all('name') as $province)
                                            <option
                                                value="{{ $province->name }}" {{ request()->province == $province->name ? 'selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="status" form="search_form" class="form-control" data-toggle="select2">
                                        <option value="all">وضعیت (همه)</option>
                                        @foreach(\App\Models\Invoice::STATUS as $key => $value)
                                            <option
                                                value="{{ $key }}" {{ request()->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @can('accountant')
                                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                        <select name="user" form="search_form" class="form-control"
                                                data-toggle="select2">
                                            <option value="all">همکار (همه)</option>
                                            @foreach(\App\Models\User::whereIn('role_id', $roles_id)->get() as $user)
                                                <option
                                                    value="{{ $user->id }}" {{ request()->user == $user->id ? 'selected' : '' }}>{{ $user->fullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <input type="text" form="search_form" name="need_no" class="form-control"
                                           value="{{ request()->need_no ?? null }}" placeholder="شماره نیاز">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>خریدار</th>
                                        <th>درخواست جهت</th>
                                        <th>استان</th>
                                        <th>شهر</th>
                                        <th>شماره تماس</th>
                                        <th>وضعیت</th>
                                        @canany(['accountant', 'sales-manager'])
                                            <th>همکار</th>
                                        @endcanany
                                        <th>تاریخ ایجاد</th>
                                        {{--                        @canany(['accountant','admin','ceo'])--}}
                                        <th>مشاهده سفارش</th>
                                        {{--                        @endcanany--}}
                                        {{--                                        <th>وضعیت سفارش</th>--}}

                                        @canany(['sales-manager','accountant'])
                                            <th>اقدام</th>
                                        @endcanany

                                        @cannot('accountant')
                                            @can('customer-order-edit')
                                                <th>ویرایش</th>
                                            @endcan
                                            @can('customer-order-delete')
                                                <th>حذف</th>
                                            @endcan
                                        @endcannot
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $key => $order)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $order->customer->name }}</td>
                                            <td>{{ \App\Models\Invoice::REQ_FOR[$order->req_for] }}</td>
                                            <td>{{ $order->customer->province }}</td>
                                            <td>{{ $order->customer->city }}</td>
                                            <td>{{ $order->customer->phone1 }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-primary d-block">{{ \App\Models\Invoice::STATUS[$order->status] }}</span>
                                            </td>
                                            @canany(['accountant', 'sales-manager'])
                                                <td>{{ $order->user->fullName() }}</td>
                                            @endcanany
                                            <td>{{ verta($order->created_at)->format('H:i - Y/m/d') }}</td>
                                            {{--                            @canany(['accountant','admin','ceo'])--}}
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('orders.show', $order->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>

                                            {{--                            @endcanany--}}
                                            {{--                                            <td>--}}
                                            {{--                                                --}}{{-- invoices before 2024-02-03 orders-status disabled --}}
                                            {{--                                                <a href="{{ route('orders-status.index', $order->id) }}"--}}
                                            {{--                                                   class="btn btn-purple btn-floating {{ $order->created_at < verta('2024-02-03 00:00:00') ? 'disabled' : '' }}"--}}
                                            {{--                                                   target="_blank">--}}
                                            {{--                                                    <i class="fa fa-truck"></i>--}}
                                            {{--                                                </a>--}}
                                            {{--                                            </td>--}}
                                            @can('warehouse-keeper')

                                                <td>
                                                    <a href="{{ $order->action ? $order->action->factor_file ?? '#' : '#' }}"
                                                       class="btn btn-primary btn-floating {{ $order->action ? $order->action->factor_file ? '' : 'disabled' : 'disabled' }}"
                                                       target="_blank">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                            @else
                                                @canany(['sales-manager','accountant'])
                                                    <td>
                                                        <a class="btn btn-primary btn-floating @cannot('accountant') {{ $order->action ? '' : 'disabled' }} @endcannot"
                                                           href="{{ route('order.action', $order->id) }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </td>
                                                @endcanany
                                            @endcan
                                            @cannot('accountant')
                                                @can('sales-manager')
                                                    @can('customer-order-edit')
                                                        <td>
                                                            <a class="btn btn-warning btn-floating {{ $order->created_in == 'website' ? 'disabled' : '' }}"
                                                               href="{{ route('orders.edit', $order->id) }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    @endcan
                                                    @can('customer-order-delete')
                                                        <td>
                                                            <button class="btn btn-danger btn-floating trashRow"
                                                                    data-url="{{ route('orders.destroy',$order->id) }}"
                                                                    data-id="{{ $order->id }}" {{ $order->created_in == 'website' ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    @endcan
                                                @else
                                                    @can('customer-order-edit')
                                                        <td>
                                                            <a class="btn btn-warning btn-floating {{ $order->created_in == 'website' || ($order->status == 'invoiced' && $order->req_for != 'amani-invoice') ? 'disabled' : '' }}"
                                                               href="{{ route('orders.edit', $order->id) }}">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    @endcan
                                                    @can('customer-order-delete')
                                                        <td>
                                                            <button class="btn btn-danger btn-floating trashRow"
                                                                    data-url="{{ route('orders.destroy',$order->id) }}"
                                                                    data-id="{{ $order->id }}" {{ $order->created_in == 'website' || $order->status == 'invoiced' ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    @endcan
                                                @endcan
                                            @endcannot
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">{{ $orders->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
