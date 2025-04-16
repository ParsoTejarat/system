@extends('panel.layouts.master')
@section('title', 'مدیریت آنالیز فروش')
@section('styles')
    <style>
        canvas {
            font-family: 'primary-font', sans-serif;
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
                        <h4 class="page-title">مدیریت آنالیز فروش کالا {{$product->title}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">

                <div class="col">
                    <div class="card">

                        <div class="card-body">

                            <div class="card-title">
                                <h4 class="page-title mb-3">{{$product->title}}</h4>

                                <h4 class="page-title mb-3">در دسته بندی: {{$product->category->name??''}} از
                                    تاریخ {{verta($firstAnalysisTime)->format("Y/m/d")}} تا
                                    {{verta($lastAnalysisTime)->format("Y/m/d")}} </h4>

                                <h4 class="page-title mb-3">
                                    برند: {{$product->brand->name??''}} {{$product->brand->name_en??''}}</h4>
                                <form action="{{ route('analysis.index') }}" class="mb-4 mt-2" method="get">
                                    <div class="row align-items-end mt-4 g-2">
                                        <div class="col-6 col-md-2">
                                            <label for="from_date" class="form-label">از تاریخ</label>
                                            <input type="text" name="from_date" id="from_date" class="form-control date-picker-shamsi-list"
                                                   value="{{ request('from_date') }}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label for="to_date" class="form-label">تا تاریخ</label>
                                            <input type="text" name="to_date" id="to_date" class="form-control date-picker-shamsi-list"
                                                   value="{{ request('to_date') }}">
                                        </div>
                                        <div class="col-6 col-md-1">
                                            <button type="submit" class="btn btn-primary w-100">جست‌وجو</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>شناسه سفارش</th>
                                            <th>شرح کالا</th>
                                            <th>تعداد سفارش</th>
                                            <th>موجودی انبار</th>
                                            <th>موجودی لحظه ای</th>
                                            <th>مشاهده سفارش</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($analysises as $key => $analysis)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <a href="/panel/orders?code={{$analysis->order->code??'-'}}">{{ $analysis->order->code??'-' }}</a>
                                                </td>
                                                @dd($analysis->product->name)
                                                <td>{{ $analysis->product->name }}</td>
                                                <td>{{ $analysis->count }}</td>
                                                <td>{{  number_format($product->Inventory) }}</td>
                                                <td>{{  number_format($product->tracking_codes_count) }}</td>
                                                <td>
                                                    <a class="btn btn-info btn-floating"
                                                       href="{{ route('orders.show', $analysis->order->id) }}"
                                                       target="_blank">
                                                        <i class="fa fa-file-invoice"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-center">{{ $analysises->appends(request()->all())->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection


