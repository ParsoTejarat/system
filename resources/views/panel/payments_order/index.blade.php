@extends('panel.layouts.master')
@section('title', 'دستور پرداخت / دریافت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">دستورات {{$type =='payments'?'پرداخت':'دریافت'}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('indicator')
                                    <a href="{{ route('payments_order.create',['type'=>$type]) }}"
                                       class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        دستور {{$type =='payments'?'پرداخت':'دریافت'}}
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ایجاد کننده</th>
                                        <th>شماره</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                        <th>دانلود</th>
                                        <th>ویرایش</th>
                                        <th>حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments_order as $key => $payment)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $payment->user->name }} {{ $payment->user->family }}</td>
                                            <td>{{ $payment->number}}</td>
                                            <td>@if($payment->status == 'approved')
                                                    <span class="badge bg-success">تایید شد</span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="badge bg-warning">در انتظار تایید</span>
                                                @else
                                                    <span class="badge bg-danger">رد شد</span>
                                                @endif
                                            </td>

                                            <td>{{ verta($payment->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('indicator.download', $payment->id) }}">
                                                    <i class="fa fa-download"></i>
                                                </a></td>
                                            <td>
                                                <a class="btn btn-warning btn-floating"
                                                   href="{{ route('payments_order.edit', $payment->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        {{--                                            @can('coupons-edit')--}}

                                        {{--                                            @endcan--}}
                                        {{--                                            @can('coupons-delete')--}}

                                        {{--                                            <td>--}}
                                        {{--                                                <button class="btn btn-danger btn-floating trashRow"--}}
                                        {{--                                                        data-url="{{ route('indicator.destroy',$indicator->id) }}"--}}
                                        {{--                                                        data-id="{{ $indicator->id }}">--}}
                                        {{--                                                    <i class="fa fa-trash"></i>--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </td>--}}
                                        {{--                                            @endcan--}}

                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div
                                class="d-flex justify-content-center">{{ $payments_order->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



