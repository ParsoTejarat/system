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
                                                <td>{{ $analysis->category->name ?? '---' }}</td>
                                                <td>{{ $analysis->count }}</td>
                                                <td>{{ $product->tracking_codes_count}}</td>
                                                <td>{{ $analysis->Inventory }}</td>
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


