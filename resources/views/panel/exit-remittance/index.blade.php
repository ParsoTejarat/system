@extends('panel.layouts.master')
@section('title', 'انبار')
@section('styles')
    <style>
        .supply-less {
            background-color: #ffda346e;
        }

        .supply-zero {
            background-color: #ff000033;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">
                            @if (Route::currentRouteName() == 'exit-remittances.index')
                                حواله های خروج
                            @else
                                خروجی ها
                            @endif
                        </h4>
                    </div>
                </div>
            </div>

            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @if (Route::currentRouteName() == 'exit-remittances.index')
                                    @can('exit-remittance-excel')
                                        <a href="#" class="btn btn-success">
                                            <i class="fa fa-file-excel mr-2"></i>
                                            خروجی اکسل حواله ها
                                        </a>
                                    @endcan
                                @else
                                    @can('exit-remittance-out-excel')
                                        <a href="#" class="btn btn-success">
                                            <i class="fa fa-file-excel mr-2"></i>
                                            خروجی اکسل کالاهای خارج شده
                                        </a>
                                    @endcan
                                @endif
                            </div>
                            <form action="{{ route('exit-remittances.index') }}" method="get" class="mt-2 mb-2">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="order">شناسه سفارش</label>
                                        <input type="text" name="order_code"
                                               value="{{old('order_code',request()->get('order_code'))}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="order">شناسه حواله خروج</label>
                                        <input type="text" name="exitRemittance_code"
                                               value="{{old('exitRemittance_code',request()->get('exitRemittance_code'))}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <div>&nbsp;</div>
                                        <input type="submit" class="btn btn-info" value="جستجو">
                                    </div>
                                </div>
                            </form
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه حواله</th>
                                        <th>شناسه سفارش</th>
                                        <th>عنوان تحویل گیرنده</th>
                                        <th>کد تحویل گیرنده</th>
                                        <th>تاریخ ثبت</th>
                                        <th>وضعیت</th>
                                        <th>مشاهده</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exitRemittances as $exitRemittance)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $exitRemittance->code }}</td>
                                            <td>
                                                <a href="/panel/orders?code={{$exitRemittance->order->code}}">{{ $exitRemittance->order->code }}</a>
                                            </td>
                                            <td>{{ $exitRemittance->customer->name }}</td>
                                            <td>{{ $exitRemittance->customer->code }}</td>

                                            <td>{{verta($exitRemittance->created_at)->format('H:i - Y/m/d')}}</td>
                                            <td>
                                                <span
                                                    class="badge p-1 {{ $exitRemittance->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                    {{ $exitRemittance->status == 'pending' ? 'در انتظار خروج' : 'خارج شد' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if (Route::currentRouteName() == 'exit-remittances.index')
                                                <a href="{{route('exit-remittances.show',$exitRemittance->id)}}"
                                                   class="btn btn-info fa fa-eye"></a>
                                                @else
                                                    <a href="{{route('show.outOfStock.index',$exitRemittance->id)}}"
                                                       class="btn btn-info fa fa-eye"></a>
                                                @endif
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
                                class="d-flex justify-content-center">{{ $exitRemittances->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
