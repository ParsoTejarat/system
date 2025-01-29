@extends('panel.layouts.master')
@section('title', 'پیش فاکتور ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">پیش فاکتور ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">

                            <div class="card-title d-flex justify-content-end">
                                @can('order-create')
                                    <a href="{{ route('pre-invoices.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد پیش فاکتور
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <form action="" method="get" class="d-flex align-items-center col-3 p-2">
                            <input type="number" name="invoice_number" placeholder="شناسه پیش فاکتور " class="form-control text-start me-2">
                            <input type="submit" class="btn btn-primary" value="جستجو">
                        </form>
                        <div class="table-responsive p-2">
                            <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                   style="width: 100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>شناسه پیش فاکتور</th>
                                    <th>خریدار</th>
                                    <th>استان</th>
                                    <th>شهر</th>
                                    <th>شماره تماس</th>
                                    <th>همکار</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>مشاهده سفارش</th>
                                    @can('customer-order-edit')
                                        <th>ویرایش</th>
                                    @endcan
                                    @can('customer-order-delete')
                                        <th>حذف</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pre_invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{$invoice->invoice_number??'-'}}</td>
                                        <td>{{ $invoice->customer_name }}</td>
                                        <td>{{ $invoice->province }}</td>
                                        <td>{{ $invoice->city }}</td>
                                        <td>{{ $invoice->phone_number }}</td>
                                        <td>{{ $invoice->user->fullName() }}</td>
                                        <td>{{ verta($invoice->created_at)->format('H:i - Y/m/d') }}</td>
                                        {{--                            @canany(['accountant','admin','ceo'])--}}
                                        <td>
                                            <a class="btn btn-info btn-floating"
                                               href="{{ route('pre-invoices.show', $invoice->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        @can('customer-order-edit')
                                            <td>
                                                <a class="btn btn-warning btn-floating"
                                                   href="{{ route('pre-invoices.edit', $invoice->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('customer-order-delete')
                                            <td>
                                                <button class="btn btn-danger btn-floating trashRow"
                                                        data-url="{{ route('pre-invoices.destroy',$invoice->id) }}"
                                                        data-id="{{ $invoice->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div
                            class="d-flex justify-content-center">{{ $pre_invoices->appends(request()->all())->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
