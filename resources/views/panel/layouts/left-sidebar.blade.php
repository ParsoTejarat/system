<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <!-- LOGO -->
    <div class="logo-box">
        <a href="javascript:void(0)" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm-dark.png" alt="" height="24">
                <!-- <span class="logo-lg-text-light">Minton</span> -->
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-dark.png" alt="" height="20">
                <!-- <span class="logo-lg-text-light">M</span> -->
            </span>
        </a>
        <a href="javascript:void(0)" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="54">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-light.png" alt="" height="50">
            </span>
        </a>
    </div>

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="/assets/images/users/avatar.png" alt="user-img" title="Mat Helme"
                 class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="#" class="text-reset dropdown-toggle h5 mt-2 mb-1 d-block"
                   data-bs-toggle="dropdown">Nik Patel</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-reset">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">پنل مدیریت</li>
                {{-- Dashboard --}}
                @canany(['users-list','roles-list','tasks-list','notes-list','leaves-list','reports-list'])
                    @php $active_side = active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit', 'tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}', 'notes','notes/create','notes/{note}/edit','leaves','leaves/create','leaves/{leave}/edit','reports','reports/create','reports/{report}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dashboard">
                            <i class="ri-dashboard-line"></i>
                            <span> داشبورد </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="dashboard">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="/panel">پنل</a>
                                </li>
                                @can('users-list')
                                    @php $active_item = active_sidebar(['users','users/create','users/{user}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('users.index') }}" class="{{ $active_item ? 'active' : '' }}">کاربران</a>
                                    </li>
                                @endcan
                                @can('roles-list')
                                    @php $active_item = active_sidebar(['roles','roles/create','roles/{role}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('roles.index') }}" {{ $active_item ? 'active' : '' }}>نقش ها</a>
                                    </li>
                                @endcan
                                @can('tasks-list')
                                    @php $active_item = active_sidebar(['tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('tasks.index') }}" {{ $active_item ? 'active' : '' }}>وظایف</a>
                                    </li>
                                @endcan
                                @can('notes-list')
                                    <li>
                                        <a href="{{ route('notes.index') }}">یادداشت ها</a>
                                    </li>
                                @endcan
                                @can('leaves-list')
                                    @php $active_item = active_sidebar(['leaves','leaves/create','leaves/{leave}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('leaves.index') }}" {{ $active_item ? 'active' : '' }}>درخواست مرخصی</a>
                                    </li>
                                @endcan
                                @can('reports-list')
                                    @php $active_item = active_sidebar(['reports','reports/create','reports/{report}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('reports.index') }}" {{ $active_item ? 'active' : '' }}>گزارشات روزانه</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Products --}}
                @canany(['products-list','price-history','coupons-list'])
                    @php $active_side = active_sidebar(['products','products/create','products/{product}/edit','search/products','coupons','coupons/create','coupons/{coupon}/edit','price-history']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#products" data-bs-toggle="collapse" aria-expanded="false" aria-controls="products">
                            <i class="ri-list-unordered"></i>
                            <span> محصولات </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="products">
                            <ul class="nav-second-level">
                                @can('products-list')
                                    @php $active_item = active_sidebar(['products','products/create','products/{product}/edit','search/products']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('products.index') }}" {{ $active_item ? 'active' : '' }}>لیست محصولات</a>
                                    </li>
                                @endcan
                                @can('price-history')
                                    <li>
                                        <a href="{{ route('price-history') }}">تاریخچه قیمت</a>
                                    </li>
                                @endcan
                                @can('coupons-list')
                                    @php $active_item = active_sidebar(['coupons','coupons/create','coupons/{coupon}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('coupons.index') }}" {{ $active_item ? 'active' : '' }}>کد تخفیف</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Orders --}}
                @canany(['invoices-list','buy-orders-list','sale-reports-list','price-requests-list'])
                    @php $active_side = active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit','search/invoices','sale-reports','sale-reports/create','sale-reports/{sale_report}/edit','search/sale-reports','invoice-action/{invoice}','orders-status/{invoice}','price-requests','price-requests/create','price-requests/{price_request}/edit','price-requests/{price_request}','buy-orders','buy-orders/create','buy-orders/{buy_order}/edit','buy-orders/{buy_order}','search/buy-orders']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#orders" data-bs-toggle="collapse" aria-expanded="false" aria-controls="orders">
                            <i class="ri-shopping-cart-line"></i>
                            <span> سفارشات </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="orders">
                            <ul class="nav-second-level">
                                @can('invoices-list')
                                    @php $active_item = active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit','search/invoices','invoice-action/{invoice}','orders-status/{invoice}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('invoices.index') }}" {{ $active_item ? 'active' : '' }}>سفارشات فروش</a>
                                    </li>
                                @endcan
                                @can('buy-orders-list')
                                    @php $active_item = active_sidebar(['buy-orders','buy-orders/create','buy-orders/{buy_order}/edit','buy-orders/{buy_order}','search/buy-orders']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('buy-orders.index') }}" {{ $active_item ? 'active' : '' }}>سفارشات خرید</a>
                                    </li>
                                @endcan
                                @can('sale-reports-list')
                                    @php $active_item = active_sidebar(['sale-reports','sale-reports/create','sale-reports/{sale_report}/edit','search/sale-reports']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('sale-reports.index') }}" {{ $active_item ? 'active' : '' }}>گزارشات فروش</a>
                                    </li>
                                @endcan
                                @can('price-requests-list')
                                    @php $active_item = active_sidebar(['price-requests','price-requests/create','price-requests/{price_request}/edit','price-requests/{price_request}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('price-requests.index') }}" {{ $active_item ? 'active' : '' }}>درخواست قیمت</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Packets --}}
                @can('packets-list')
                    <li>
                        <a href="#packets" data-bs-toggle="collapse" aria-expanded="false" aria-controls="packets">
                            <i class="ri-truck-line"></i>
                            <span> بسته های ارسالی </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="packets">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('packets.index') }}">لیست بسته ها</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Customers --}}
                @can('customers-list')
                    <li>
                        <a href="#customers" data-bs-toggle="collapse" aria-expanded="false" aria-controls="customers">
                            <i class="ri-group-line"></i>
                            <span> مشتریان </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="customers">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('customers.index') }}">لیست مشتریان</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Shops --}}
                @can('shops')
                    <li>
                        <a href="#shops" data-bs-toggle="collapse" aria-expanded="false" aria-controls="shops">
                            <i class="ri-store-3-line"></i>
                            <span> فروشگاه ها </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="shops">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('off-site-products.index', 'torob') }}">ترب</a>
                                </li>
                                <li>
                                    <a href="{{ route('off-site-products.index', 'emalls') }}">ایمالز</a>
                                </li>
                                <li>
                                    <a href="{{ route('off-site-products.index', 'digikala') }}">دیجیکالا</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Warehouse --}}
                @canany(['guarantees-list','warehouses-list'])
                    <li>
                        <a href="#warehouse" data-bs-toggle="collapse" aria-expanded="false" aria-controls="warehouse">
                            <i class="ri-home-5-line"></i>
                            <span> انبار </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="warehouse">
                            <ul class="nav-second-level">
                                @can('guarantees-list')
                                    <li>
                                        <a href="{{ route('guarantees.index') }}">گارانتی ها</a>
                                    </li>
                                @endcan
                                @can('warehouses-list')
                                    <li>
                                        <a href="{{ route('warehouses.index') }}">انبار ها</a>
                                    </li>
                                @endcan
                                @if(request()->warehouse_id)
                                    @can('inventory-list')
                                        <li>
                                            <a href="{{ route('inventory.index', ['warehouse_id' => request()->warehouse_id]) }}">کالاها</a>
                                        </li>
                                    @endcan
                                    @can('input-reports-list')
                                        <li>
                                            <a href="{{ route('inventory-reports.index', ['type' => 'input', 'warehouse_id' => request()->warehouse_id]) }}">ورود</a>
                                        </li>
                                    @endcan
                                    @can('output-reports-list')
                                        <li>
                                            <a href="{{ route('inventory-reports.index', ['type' => 'output', 'warehouse_id' => request()->warehouse_id]) }}">خروج</a>
                                        </li>
                                    @endcan
                                @endif
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Tickets & Supports --}}
                @canany(['tickets-list','sms-histories'])
                    <li>
                        <a href="#tickets" data-bs-toggle="collapse" aria-expanded="false" aria-controls="tickets">
                            <i class="ri-message-2-line"></i>
                            <span> پشتیبانی و تیکت </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="tickets">
                            <ul class="nav-second-level">
                                @can('tickets-list')
                                    <li>
                                        <a href="{{ route('tickets.index') }}">تیکت ها</a>
                                    </li>
                                @endcan
                                @can('sms-histories')
                                    <li>
                                        <a href="{{ route('sms-histories.index') }}">پیام های ارسال شده</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->
