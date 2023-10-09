@extends('panel.layouts.master')
@section('title', 'پیش فاکتور ها')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>پیش فاکتور ها</h6>
                @can('invoices-create')
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>
                        ایجاد پیش فاکتور
                    </a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>خریدار</th>
                        <th>شماره اقتصادی</th>
                        <th>استان</th>
                        <th>شهر</th>
                        <th>شماره تماس</th>
                        <th>تاریخ ایجاد</th>
                        @can('invoices-edit')
                            <th>ویرایش</th>
                        @endcan
                        @can('invoices-delete')
                            <th>حذف</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $key => $invoice)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $invoice->buyer_name }}</td>
                            <td>{{ $invoice->economical_number }}</td>
                            <td>{{ $invoice->province }}</td>
                            <td>{{ $invoice->city }}</td>
                            <td>{{ $invoice->phone }}</td>
                            <td>{{ verta($invoice->created_at)->format('H:i - Y/m/d') }}</td>
                            @can('invoices-edit')
                                <td>
                                    <a class="btn btn-warning btn-floating" href="{{ route('invoices.edit', $invoice->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('invoices-delete')
                                <td>
                                    <button class="btn btn-danger btn-floating trashRow" data-url="{{ route('invoices.destroy',$invoice->id) }}" data-id="{{ $invoice->id }}">
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
            <div class="d-flex justify-content-center">{{ $invoices->links() }}</div>
        </div>
    </div>
@endsection


