@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #stats i.fa, i.fab {
            font-size: 30px;
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
                        <h4 class="page-title">پنل</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row" id="stats">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="knob-chart" dir="ltr">
                                    <i class="fa fa-users text-primary"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-1 mt-0"><span
                                            data-plugin="counterup">{{ \App\Models\User::count() }}</span></h3>
                                    <p class="text-muted mb-0">کاربران</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="knob-chart" dir="ltr">
                                    <i class="fa fa-users text-secondary"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-1 mt-0"><span
                                            data-plugin="counterup">{{ \App\Models\Customer::count() }}</span></h3>
                                    <p class="text-muted mb-0">مشتریان</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="knob-chart" dir="ltr">
                                    <i class="fab fa-product-hunt text-info"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-1 mt-0"><span
                                            data-plugin="counterup">{{ \App\Models\Product::count() }}</span></h3>
                                    <p class="text-muted mb-0">محصولات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="knob-chart" dir="ltr">
                                    <i class="fa fa-shopping-cart text-success"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-1 mt-0"><span
                                            data-plugin="counterup">{{ \App\Models\Invoice::count() }}</span></h3>
                                    <p class="text-muted mb-0">سفارشات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @can('accountant-manager')
                    @php
                        $title = 'فعالیت های اخیر حسابداران (5 تای اخیر)';
                        $activities = \App\Models\ActivityLog::whereHas('user.role', function ($q) {
                            $q->whereHas('permissions', function ($q) {
                                $q->where('name', 'accountant');
                            })->where('name', '!=', 'admin');
                        })->latest()->limit(5)->get();
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        @include('panel.partials.panel.activity-limit', ['activities' => $activities, 'title' => $title, 'permission' => 'accountant-manager'])
                    </div>
                @endcan
                @can('sales-manager')
                    @php
                        $title = 'فعالیت های اخیر کارمندان فروش (5 تای اخیر)';
                        $activities = \App\Models\ActivityLog::whereHas('user.role', function ($q) {
                            $q->whereHas('permissions', function ($q) {
                                $q->whereIn('name', ['free-sales','system-user','partner-tehran-use','partner-other-user','single-price-user']);
                            })->where('name', '!=', 'admin');
                        })->latest()->limit(5)->get();
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        @include('panel.partials.panel.activity-limit', ['activities' => $activities, 'title' => $title, 'permission' => 'sales-manager'])
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
