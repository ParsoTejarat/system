@extends('panel.layouts.master')
@section('title', 'مهندسی خرید')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">مهندسی خرید</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان</th>
                                        <th>انباردار</th>
                                        <th>تاریخ ثبت</th>
                                        <th>تعیین وضعیت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($purchases as $key => $purchase)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $purchase->inventory->title  }}</td>
                                            <td>{{ $purchase->user->name .' '. $purchase->user->family }}</td>

                                            <td>{{ verta($purchase->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                {{--                                                <a href="{{url('/purchases/status/'.$purchase->id)}}"--}}
                                                {{--                                                   class="btn btn-warning">{{$purchase->status =='pending_purchase'?'در انتظار خرید':'خریداری شده'}}</a>--}}

                                                <a class="btn btn-warning btn-floating"
                                                   href="{{ route('purchase.status', $purchase->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
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
                                class="d-flex justify-content-center">{{ $purchases->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
