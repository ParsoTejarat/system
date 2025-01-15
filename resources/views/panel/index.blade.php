@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #stats i.fa, i.fab {
            font-size: 30px;
        }

        .card .title {
            background: transparent;
            border: none;
            width: 100%;

        }

        .card .title:focus-visible {
            outline: none !important;
        }

        .card .text {
            background: transparent;
            border: none;
            resize: none;
            width: 100%;
            height: 180px;
            text-align: justify;
        }

        .card .text:focus-visible {
            outline: none;
        }

        .title::placeholder {
            color: white;
        }

        .sticky-note {
            background: linear-gradient(145deg, #fffde7, #fff9c4);
            border: 1px solid #f5e79e;
            border-radius: 5px;
            box-shadow: 3px 5px 15px rgba(0, 0, 0, 0.2), inset 0 -2px 4px rgba(255, 255, 255, 0.6); /* سایه طبیعی */
            padding: 15px;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
            width: 300px;
            height: 300px;
            overflow: hidden;
            transform: rotate(-1.5deg);
        }


        .sticky-note .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
            resize: none;
        }


        .sticky-note .text {
            border: none;
            background: transparent;
            width: 100%;
            height: calc(100% - 30px);
            resize: none;
            outline: none;
            overflow-y: auto;
        }


        .sticky-note::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            opacity: 0.4;
            transform: translate(-15px, -15px);
            z-index: 1;
        }

        .pin {
            --pin-color: #d02627;
            --pin-dark: #9e0608;
            --pin-light: #fc7e7d;

            position: absolute;
            left: 10px;
            top: 6px;
            width: 60px;
            height: 50px;
        }

        .shadow {
            position: absolute;
            top: 18px;
            left: -8px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: radial-gradient(var(--paper-shadow), 20%, rgba(201, 191, 141, 0));
        }

        .metal {
            position: absolute;
            width: 5px;
            height: 20px;
            background: linear-gradient(to right, #808080, 40%, #eae8e8, 50%, #808080);
            border-radius: 0 0 30% 30%;
            transform: rotate(50deg);
            transform-origin: bottom left;
            top: 17px;
            left: 2px;
            border-bottom: 1px solid #808080;
        }

        .bottom-circle {
            position: absolute;
            right: 15px;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--pin-color);
            background: radial-gradient(circle at bottom right, var(--pin-light), 25%, var(--pin-dark), 90%, var(--pin-color));
        }

        /* Barrel */
        .bottom-circle::before {
            content: "";
            position: absolute;
            top: 0;
            left: -2px;
            width: 20px;
            height: 30px;
            transform: rotate(55deg);
            transform-origin: 100% 100%;
            border-radius: 0 0 40% 40%;
            background: linear-gradient(
                to right,
                var(--pin-dark),
                30%,
                var(--pin-color),
                90%,
                var(--pin-light)
            );
        }

        /* Top circle */
        .bottom-circle::after {
            content: "";
            position: absolute;
            right: -10px;
            top: -5px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: radial-gradient(
                circle at right,
                var(--pin-light),
                30%,
                var(--pin-color),
                var(--pin-dark) 80%
            );
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
            @if(!is_null($softWareUpdate))
                @if($softWareUpdate->created_at->addDays(3) > now())
                    <div class="row">
                        <div class="alert alert-success">
                            <h3>تغییرات جدید پنل</h3>
                            <ul>
                                @foreach(json_decode($softWareUpdate->description) as $item)
                                    <li>{{$item}}</li>
                                @endforeach
                            </ul>
                            <i>ورژن {{$softWareUpdate->version}}</i>
                        </div>
                    </div>
                @endif
            @endif
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
                                    <p class="text-muted mb-0">همکاران</p>
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
                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between align-items-center">
                                <h5>یادداشت های اخیر</h5>
                                <a href="/panel/notes" class="btn btn-link">رفتن به همه یادداشت ها</a>
                            </div>

                            @if(count($notes))
                                <div class="row">
                                    @foreach($notes as $note)
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mt-2">

                                            <div class="sticky-note">
                                                <div class="pin">
                                                    <div class="shadow"></div>
                                                    <div class="metal"></div>
                                                    <div class="bottom-circle"></div>
                                                </div>
                                                <input type="text" name="note-title" class="title"
                                                       value="{{ $note->title }}" data-id="{{ $note->id }}"
                                                       maxlength="30" placeholder="عنوان یادداشت" disabled>
                                                <textarea class="text" name="note-text" spellcheck="false"
                                                          placeholder="متن یادداشت..."
                                                          disabled>{{ $note->text }}</textarea>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            @else
                                <div class="row justify-content-lg-center mb-4" id="list">
                                    <p class="text-muted text-center">یادداشتی اضافه نکرده اید!</p>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @can('accountant-manager')
                    @php
                        $title = 'فعالیت های اخیر حسابداران (5 تای اخیر)';
                        $activities = \App\Models\ActivityLog::where('user_id','!=',\auth()->id())->whereHas('user.role', function ($q) {
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
                        $activities = \App\Models\ActivityLog::where('user_id','!=',\auth()->id())->whereHas('user.role', function ($q) {
                            $q->whereHas('permissions', function ($q) {
                                $q->whereIn('name', ['free-sales','system-user','partner-tehran-use','partner-other-user','single-price-user']);
                            })->where('name', '!=', 'admin');
                        })->latest()->limit(5)->get();
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        @include('panel.partials.panel.activity-limit', ['activities' => $activities, 'title' => $title, 'permission' => 'sales-manager'])
                    </div>
                @endcan
                @can('commercial-manager')
                    @php
                        $title = 'فعالیت های اخیر کارمندان بازرگانی (5 تای اخیر)';
                        $activities = \App\Models\ActivityLog::where('user_id','!=',\auth()->id())->whereHas('user.role', function ($q) {
                            $q->whereHas('permissions', function ($q) {
                                $q->whereIn('name', ['internal-commerce','external-commerce']);
                            })->where('name', '!=', 'admin');
                        })->latest()->limit(5)->get();
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        @include('panel.partials.panel.activity-limit', ['activities' => $activities, 'title' => $title, 'permission' => 'commercial-manager'])
                    </div>
                @endcan
                @can('it-manager')
                    @php
                        $title = 'فعالیت های اخیر کارمندان آی تی (5 تای اخیر)';
                        $activities = \App\Models\ActivityLog::where('user_id','!=',\auth()->id())->whereHas('user.role', function ($q) {
                            $q->whereHas('permissions', function ($q) {
                                $q->where('name', 'it-man');
                            })->where('name', '!=', 'admin');
                        })->latest()->limit(5)->get();
                    @endphp
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        @include('panel.partials.panel.activity-limit', ['activities' => $activities, 'title' => $title, 'permission' => 'it-manager'])
                    </div>
                @endcan
                {{--            LastUsers        --}}
                @canany(['ceo','it-manager'])
                    @php
                        $title = 'آخرین کاربران ثبت شده (5 تای اخیر)';
                        $users =\App\Models\User::latest()->limit(5)->get()
                    @endphp

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">{{ $title }}</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام</th>
                                            <th>نام خانوادگی</th>
                                            <th>شماره موبایل</th>
                                            <th>نقش</th>
                                            <th>تاریخ ایجاد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $key => $user)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->family }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->role->label }}</td>
                                                <td>{{ verta($user->created_at)->format('H:i - Y/m/d') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('users.index') }}" class="btn btn-link">نمایش همه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanany
                {{--            EndLastUsers        --}}

                {{--            LastProducts        --}}
                @canany(['ceo','it-manager','online_sale'])
                    @php
                        $title = 'آخرین محصولات ثبت شده (5 تای اخیر)';
                        $products =\App\Models\Product::latest()->limit(5)->get()
                    @endphp

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">{{ $title }}</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان محصول</th>
                                            <th>کد محصول</th>
                                            <th>کد حسابداری</th>
                                            <th>دسته بندی</th>
                                            <th>قیمت تک فروشی</th>
                                            <th>تاریخ ایجاد</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($products as $key => $product)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $product->title }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->code }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>{{ number_format($product->single_price / 10) }} تومان</td>
                                                <td>{{ verta($product->created_at)->format('H:i - Y/m/d') }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="text-center">
                                    <a href="{{ route('products.index') }}" class="btn btn-link">نمایش همه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanany
                {{--            EndLastProducts        --}}

                {{--            LastInvoices        --}}
                @canany(['ceo','online_sale','accountant-manager'])
                    @php
                        $title = 'آخرین سفارشات ثبت شده (5 تای اخیر)';
                        $invoices =\App\Models\Invoice::latest()->limit(5)->get()
                    @endphp

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">{{ $title }}</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>خریدار</th>
                                            <th>درخواست جهت</th>
                                            <th>استان</th>
                                            <th>شهر</th>
                                            <th>شماره تماس</th>
                                            <th>وضعیت</th>
                                            <th>همکار</th>
                                            <th>تاریخ ایجاد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoices as $key => $invoice)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $invoice->customer->name }}</td>
                                                <td>{{ \App\Models\Invoice::REQ_FOR[$invoice->req_for] }}</td>
                                                <td>{{ $invoice->province }}</td>
                                                <td>{{ $invoice->city }}</td>
                                                <td>{{ $invoice->phone }}</td>
                                                <td>
                                                <span
                                                        class="badge bg-primary d-block">{{ \App\Models\Invoice::STATUS[$invoice->status] }}</span>
                                                </td>
                                                <td>{{ $invoice->user->fullName() }}</td>
                                                <td>{{ verta($invoice->created_at)->format('H:i - Y/m/d') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="text-center">
                                    <a href="{{ route('invoices.index') }}" class="btn btn-link">نمایش همه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanany
                {{--            EndLastInvoices        --}}

                {{--            LastCustomer        --}}
                @canany(['ceo','online_sale'])
                    @php
                        $title = 'آخرین مشتریان (5 تای اخیر)';
                        $customers =\App\Models\Customer::latest()->limit(5)->get()
                    @endphp

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">{{ $title }}</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام حقیقی/حقوقی</th>

                                            <th>مشتری</th>
                                            <th>استان</th>
                                            <th>شماره تماس 1</th>
                                            <th>تعداد سفارش</th>
                                            <th>تاریخ ایجاد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($customers as $key => $customer)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $customer->name }}</td>
                                                {{--                                                <td>{{ \App\Models\Customer::TYPE[$customer->type] }}</td>--}}
                                                <td>{{ \App\Models\Customer::CUSTOMER_TYPE[$customer->customer_type] }}</td>
                                                <td>{{ $customer->province }}</td>
                                                <td>{{ $customer->phone1 }}</td>
                                                <td>{{ $customer->invoices()->count() }}</td>
                                                <td>{{ verta($customer->created_at)->format('H:i - Y/m/d') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <div class="text-center">
                                    <a href="{{ route('customers.index') }}" class="btn btn-link">نمایش همه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanany
                {{--            EndLastCustomers        --}}

                {{--            LastLeaves        --}}
                @canany('ceo')
                    @php
                        $title = 'آخرین درخواست های مرخصی (5 تای اخیر)';
                        $leaves =\App\Models\Leave::latest()->limit(5)->get()
                    @endphp

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">{{ $title }}</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان</th>
                                            <th>نوع</th>
                                            @can('ceo')
                                                <th>درخواست دهنده</th>
                                            @endcan
                                            <th>تاریخ مرخصی</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ ایجاد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($leaves as $key => $leave)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $leave->title }}</td>
                                                <td>{{ \App\Models\Leave::TYPE[$leave->type] }}</td>
                                                @can('ceo')
                                                    <td>{{ $leave->user->fullName() }}</td>
                                                @endcan
                                                <td>{{ verta($leave->from_date)->format('Y/m/d') }}</td>
                                                <td>
                                                    @if($leave->status == 'accept')
                                                        <span
                                                                class="badge bg-success">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                    @elseif($leave->status == 'reject')
                                                        <span
                                                                class="badge bg-danger">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                    @else
                                                        <span
                                                                class="badge bg-warning">{{ \App\Models\Leave::STATUS[$leave->status] }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ verta($leave->created_at)->format('H:i - Y/m/d') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('leaves.index') }}" class="btn btn-link">نمایش همه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanany
                {{--            EndLastLeaves       --}}

                {{--            Lastwarehouse        --}}
                {{--                @canany(['ceo','warehouse-keeper','accountant-manager'])--}}
                {{--                    @php--}}
                {{--                        $title = 'انبار ها (5 تای اخیر)';--}}
                {{--                        $warehouses =\App\Models\Warehouse::latest()->limit(5)->get()--}}
                {{--                    @endphp--}}

                {{--                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">--}}
                {{--                        <div class="card">--}}
                {{--                            <div class="card-body">--}}
                {{--                                <div class="card-title">{{ $title }}</div>--}}
                {{--                                <div class="table-responsive">--}}
                {{--                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center">--}}
                {{--                                        <thead>--}}
                {{--                                        <tr>--}}
                {{--                                            <th>#</th>--}}
                {{--                                            <th>نام انبار</th>--}}
                {{--                                            <th>موجودی اولیه</th>--}}
                {{--                                            <th>موجودی فعلی</th>--}}
                {{--                                            <th>ورود</th>--}}
                {{--                                            <th>خروج</th>--}}
                {{--                                            <th>تاریخ ایجاد</th>--}}
                {{--                                        </tr>--}}
                {{--                                        </thead>--}}
                {{--                                        <tbody>--}}
                {{--                                        @foreach($warehouses as $key => $item)--}}
                {{--                                            <tr>--}}
                {{--                                                <td>{{ ++$key }}</td>--}}
                {{--                                                <td>{{ $item->name }}</td>--}}
                {{--                                                <td>{{ number_format($item->getInitialCount()) }}</td>--}}
                {{--                                                <td>{{ number_format($item->getCurrentCount()) }}</td>--}}
                {{--                                                <td>{{ number_format($item->getInputCount()) }}</td>--}}
                {{--                                                <td>{{ number_format($item->getOutputCount()) }}</td>--}}
                {{--                                                <td>{{ verta($item->created_at)->format('H:i - Y/m/d') }}</td>--}}
                {{--                                            </tr>--}}
                {{--                                        @endforeach--}}
                {{--                                        </tbody>--}}
                {{--                                        <tfoot>--}}
                {{--                                        <tr>--}}
                {{--                                        </tr>--}}
                {{--                                        </tfoot>--}}
                {{--                                    </table>--}}
                {{--                                </div>--}}
                {{--                                <div class="text-center">--}}
                {{--                                    <a href="{{ route('warehouses.index') }}" class="btn btn-link">نمایش همه</a>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                @endcanany--}}
                {{--            EndLastWarehouse       --}}

                {{--                --}}{{--            LastOutProduct        --}}
                {{--                @canany(['ceo','warehouse-keeper'])--}}
                {{--                    @php--}}
                {{--                        $title = 'آخرین خروجی ها (5 تای اخیر)';--}}
                {{--                        $reports =\App\Models\InventoryReport::latest()->limit(5)->get()--}}
                {{--                    @endphp--}}

                {{--                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">--}}
                {{--                        <div class="card">--}}
                {{--                            <div class="card-body">--}}
                {{--                                <div class="card-title">{{ $title }}</div>--}}
                {{--                                <div class="table-responsive">--}}
                {{--                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center">--}}
                {{--                                        <thead>--}}
                {{--                                        <tr>--}}
                {{--                                            <th>#</th>--}}
                {{--                                            <th>تحویل گیرنده</th>--}}
                {{--                                            <th>سفارش</th>--}}
                {{--                                            <th>تاریخ خروج</th>--}}
                {{--                                            <th>تاریخ ثبت</th>--}}
                {{--                                            <th>خروج انبار</th>--}}

                {{--                                        </tr>--}}
                {{--                                        </thead>--}}
                {{--                                        <tbody>--}}
                {{--                                        @foreach($reports as $key => $item)--}}
                {{--                                            <tr>--}}
                {{--                                                <td>{{ ++$key }}</td>--}}
                {{--                                                <td><strong>{{ $item->person }}</strong></td>--}}
                {{--                                                <td>--}}
                {{--                                                    @if($item->invoice)--}}
                {{--                                                        <strong><u><a--}}
                {{--                                                                    href="{{ route('invoices.show', [$item->invoice->id]) }}"--}}
                {{--                                                                    class="text-primary"--}}
                {{--                                                                    target="_blank">{{ $item->invoice_id }}</a></u></strong>--}}
                {{--                                                    @else--}}
                {{--                                                        -----}}
                {{--                                                    @endif--}}
                {{--                                                </td>--}}
                {{--                                                <td>{{ verta($item->date)->format('Y/m/d') }}</td>--}}
                {{--                                                <td>{{ verta($item->created_at)->format('H:i - Y/m/d') }}</td>--}}
                {{--                                                <td>--}}
                {{--                                                    <a class="btn btn-info btn-floating"--}}
                {{--                                                       href="{{ route('inventory-reports.show', $item) }}">--}}
                {{--                                                        <i class="fa fa-eye"></i>--}}
                {{--                                                    </a>--}}
                {{--                                                </td>--}}
                {{--                                            </tr>--}}
                {{--                                        @endforeach--}}
                {{--                                        </tbody>--}}
                {{--                                        <tfoot>--}}
                {{--                                        <tr>--}}
                {{--                                        </tr>--}}
                {{--                                        </tfoot>--}}
                {{--                                    </table>--}}
                {{--                                </div>--}}
                {{--                                <div class="text-center">--}}
                {{--                                    <a href="{{ route('warehouses.index') }}" class="btn btn-link">نمایش همه</a>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                @endcanany--}}
                {{--                --}}{{--            EndLastOutProduct       --}}

                {{--            LastPurchases        --}}
{{--                @canany(['ceo','purchase-engineering'])--}}
{{--                    @php--}}
{{--                        $title = 'آخرین لیست نیاز ها (5 تای اخیر)';--}}
{{--                        $purchases =\App\Models\Purchase::latest()->limit(5)->get()--}}
{{--                    @endphp--}}
{{--                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="card-title">{{ $title }}</div>--}}
{{--                                <div class="table-responsive">--}}
{{--                                    <table class="table table-striped table-bordered dataTable dtr-inline text-center"--}}
{{--                                           style="width: 100%">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>#</th>--}}
{{--                                            <th>عنوان</th>--}}
{{--                                            <th>انباردار</th>--}}
{{--                                            <th>وضعیت</th>--}}
{{--                                            <th>تاریخ ثبت</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach($purchases as $key => $purchase)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{ ++$key }}</td>--}}
{{--                                                <td>{{ $purchase->product->title  }}</td>--}}
{{--                                                <td>{{ $purchase->user->name .' '. $purchase->user->family }}</td>--}}
{{--                                                <td>--}}
{{--                                                    <span--}}
{{--                                                            class=" badge {{$purchase->status =='pending_purchase'?'bg-warning':'bg-success'}}">--}}
{{--                                                        {{$purchase->status =='pending_purchase'?'در انتظار خرید':'خریداری شده'}}--}}
{{--                                                    </span>--}}
{{--                                                </td>--}}
{{--                                                <td>{{ verta($purchase->created_at)->format('H:i - Y/m/d') }}</td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                        <tfoot>--}}
{{--                                        <tr>--}}
{{--                                        </tr>--}}
{{--                                        </tfoot>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                                <div class="text-center">--}}
{{--                                    <a href="{{ route('warehouses.index') }}" class="btn btn-link">نمایش همه</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endcanany--}}
                {{--            EndLastPurchases       --}}
            </div>
        </div>
    </div>
@endsection
