@extends('panel.layouts.master')
@section('title', 'مدیریت آنالیز فروش')
@section('styles')
    <!-- Clockpicker -->
    <link rel="stylesheet" href="/vendors/clockpicker/bootstrap-clockpicker.min.css" type="text/css">
    <!-- Datepicker -->
    <link rel="stylesheet" href="/vendors/datepicker/daterangepicker.css">
    <link rel="stylesheet" href="/vendors/datepicker-jalali/bootstrap-datepicker.min.css">
    <style>

        canvas {
            font-family: 'primary-font', sans-serif;
        }


        .btn_remove {
            cursor: pointer;
        }

        #btn_add {
            margin-top: 30px
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
                        <h4 class="page-title">مدیریت آنالیز فروش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">

                <div class="col">
                    <div class="card">

                        <div class="card-body">

                            <div class="row" id="stats">
                                @php
                                    $stats = [
                                        [
                                            'count' => \App\Models\Order::count(),
                                            'label' => 'مجموع سفارشات تا امروز',
                                            'icon' => 'fa-clipboard-list',
                                            'color' => 'primary',
                                        ],
                                        [
                                            'count' => \App\Models\Customer::count(),
                                            'label' => 'مجموع مشتریان ما',
                                            'icon' => 'fa-user-friends',
                                            'color' => 'warning',
                                        ],
                                        [
                                            'count' => \App\Models\Order::where('req_for', 'invoice')->count(),
                                            'label' => 'سفارشات تکمیل شده',
                                            'icon' => 'fa-check-circle',
                                            'color' => 'success',
                                        ],
                                        [
                                            'count' => \App\Models\Invoice::where('req_for', 'pre-invoice')->count(),
                                            'label' => 'پیش فاکتور ها',
                                            'icon' => 'fa-file-invoice',
                                            'color' => 'info',
                                        ],
                                    ];
                                @endphp

                                @foreach($stats as $stat)
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card shadow-lg rounded-3 border-0">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div
                                                        class="icon-box bg-{{ $stat['color'] }} bg-opacity-10 text-{{ $stat['color'] }} rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fa {{ $stat['icon'] }} fs-4"></i>
                                                    </div>
                                                    <div class="text-end">
                                                        <h3 class="mb-1 mt-0">
                                                            <span data-plugin="counterup">{{ $stat['count'] }}</span>
                                                        </h3>
                                                        <p class="text-muted mb-0">{{ $stat['label'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">بیشترین ثبت سفارش توسط همکاران</h5>
                                            <canvas id="userChart" height="180"
                                                    style="font-family: 'primary-font'"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">مشتریانی با بیشترین سفارش</h5>
                                            <canvas id="customerChart" height="200"
                                                    style="font-family: 'primary-font'"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <form action="{{ route('analysis.index') }}" class="mb-4" method="get">
                                <div class="row align-items-end mt-4 g-2">
                                    <div class="col-6 col-md-2">
                                        <label for="product_id" class="form-label">شرح کالا</label>
                                        <select name="product_id" id="product_id" class="form-control" data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Product::all() as $product)
                                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="category_id" class="form-label">دسته‌بندی</label>
                                        <select name="category_id" id="category_id" class="form-control" data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Category::all() as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="brand_id" class="form-label">برند</label>
                                        <select name="brand_id" id="brand_id" class="form-control" data-toggle="select2">
                                            <option selected disabled>انتخاب کنید...</option>
                                            @foreach(\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}({{ $brand->name_en }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="from_date" class="form-label">از تاریخ</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control date-picker-shamsi-list"
                                               value="{{ request('from_date') }}" placeholder="مثال : 1404/01/01 ">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label for="to_date" class="form-label">تا تاریخ</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control date-picker-shamsi-list"
                                               value="{{ request('to_date') }}" placeholder="مثال : 1404/01/30 ">
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
                                        <th>شرح کالا</th>
                                        <th>دسته‌بندی</th>
                                        <th>برند</th>
                                        <th>مجموع تعداد سفارش</th>
                                        <th>جزئیات بیشتر</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($analysises as $key => $analysis)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $analysis->product->title ?? '---' }}</td>
                                            <td>{{ $analysis->category->name ?? '---' }}</td>
                                            <td>
                                                @if($analysis->brand)
                                                    {{ $analysis->brand->name}}({{ $analysis->brand->name_en}})
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $analysis->total_count }}</td>
                                            <td>
                                                <a href="{{route('analysis.show',$analysis->product_id)}}"
                                                   class="btn btn-primary">
                                                    <span class="fa fa-chart-bar"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="d-flex justify-content-center">{{ $analysises->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.min.js"></script>
    <script src="/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js"></script>
    <script src="/vendors/datepicker/daterangepicker.js"></script>
    <script src="/assets/js/examples/datepicker.js"></script>
    <script src="/vendors/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="/assets/js/examples/clockpicker.js"></script>
    <script src="/vendors/charts/chartjs/chart.min.js"></script>
    <script>
        $(document).ready(function () {
            const fontFamily = 'primary-font';

            function generateColors(length) {
                const palette = ['#42a5f5', '#66bb6a', '#ffa726', '#ef5350', '#ab47bc'];
                const colors = [];
                for (let i = 0; i < length; i++) {
                    colors.push(palette[i % palette.length]);
                }
                return colors;
            }

            const commonOptions = {
                responsive: true,
                legend: {
                    labels: {
                        fontFamily: fontFamily,
                        fontSize: 14
                    }
                },
                tooltips: {
                    bodyFontFamily: fontFamily,
                    bodyFontSize: 13,
                    titleFontFamily: fontFamily,
                    titleFontSize: 14
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            fontFamily: fontFamily,
                            fontSize: 12
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontFamily: fontFamily,
                            fontSize: 12
                        }
                    }]
                }
            };


            // نمودار همکاران
            const userLabels = {!! json_encode($topUsers->pluck('name')) !!};
            const userData = {!! json_encode($topUsers->pluck('total')) !!};

            new Chart(document.getElementById('userChart'), {
                type: 'bar',
                data: {
                    labels: userLabels,
                    datasets: [{
                        label: 'تعداد سفارشات',
                        data: userData,
                        backgroundColor: generateColors(userLabels.length)
                    }]
                },
                options: commonOptions
            });

            // نمودار مشتریان
            const customerLabels = {!! json_encode($topCustomers->pluck('customer.name')) !!};
            const customerData = {!! json_encode($topCustomers->pluck('total')) !!};

            new Chart(document.getElementById('customerChart'), {
                type: 'bar',
                data: {
                    labels: customerLabels,
                    datasets: [{
                        label: 'تعداد سفارشات',
                        data: customerData,
                        backgroundColor: generateColors(customerLabels.length)
                    }]
                },
                options: commonOptions
            });
        });
    </script>

@endsection

