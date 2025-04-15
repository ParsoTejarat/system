@extends('panel.layouts.master')
@section('title', 'مدیریت آنالیز فروش')
@section('styles')
    <style>


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
                                                    <div class="icon-box bg-{{ $stat['color'] }} bg-opacity-10 text-{{ $stat['color'] }} rounded-circle d-flex align-items-center justify-content-center"
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
                                            <canvas id="userChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">مشتریانی با بیشترین سفارش</h5>
                                            <canvas id="customerChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-title d-flex justify-content-end align-items-center">
                                {{--                                @can('brands-create')--}}
                                {{--                                    <a href="{{ route('brands.create') }}" class="btn btn-primary">--}}
                                {{--                                        <i class="fa fa-plus mr-2"></i>--}}
                                {{--                                        ایجاد برند--}}
                                {{--                                    </a>--}}
                                {{--                                @endcan--}}
                            </div>
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
                                            <td><a href="#" class="btn btn-primary">
                                                    <span class="fa fa-chart-bar"></span>
                                                </a></td>
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
@section('scripts')
    <script src="/assets/libs/chart.js/Chart.min.js"></script>
    <script>
        $(document).ready(function () {
            const fontFamily = 'IRANYekanFN Medium';

            // همکاران
            new Chart(document.getElementById('userChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topUsers->pluck('name')) !!},
                    datasets: [{
                        label: 'تعداد سفارشات',
                        data: {!! json_encode($topUsers->pluck('total')) !!},
                        backgroundColor: '#42a5f5'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        }
                    }
                }
            });

            // مشتریان
            new Chart(document.getElementById('customerChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topCustomers->pluck('customer.name')) !!},
                    datasets: [{
                        label: 'تعداد سفارشات',
                        data: {!! json_encode($topCustomers->pluck('total')) !!},
                        backgroundColor: '#66bb6a'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    family: fontFamily
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

@endsection

