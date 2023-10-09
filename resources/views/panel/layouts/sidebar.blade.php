<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-icon-menu">
        <ul>
            <li class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="داشبورد">
                <a href="#navigationDashboards" title="داشبوردها">
                    <i class="icon ti-pie-chart"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit','products','products/create','products/{product}/edit','printers','printers/create','printers/{printer}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="محصولات">
                <a href="#navigationProducts" title="محصولات">
                    <i class="icon ti-view-list"></i>
                </a>
            </li>
            <li class="{{ active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit']) ? 'active' : '' }}" data-toggle="tooltip" title="پیش فاکتور">
                <a href="#navigationInvoices" title="پیش فاکتور">
                    <i class="icon ti-shopping-cart"></i>
                </a>
            </li>
        </ul>
        <ul>
            <li data-toggle="tooltip" title="ویرایش پروفایل">
                <a href="{{ route('users.edit', auth()->id()) }}" class="go-to-page">
                    <i class="icon ti-settings"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="خروج">
                <a href="{{ route('logout') }}" class="go-to-page" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="icon ti-power-off"></i>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
    <div class="navigation-menu-body">
        <ul id="navigationDashboards" class="{{ active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">داشبورد</li>
            <li>
                <a class="{{ active_sidebar(['panel']) ? 'active' : '' }}" href="{{ route('panel') }}">پنل</a>
            </li>
            @can('users-list')
                <li>
                    <a class="{{ active_sidebar(['users','users/create','users/{user}/edit']) ? 'active' : '' }}" href="{{ route('users.index') }}">کاربران</a>
                </li>
            @endcan
            @can('roles-list')
                <li>
                    <a class="{{ active_sidebar(['roles','roles/create','roles/{role}/edit']) ? 'active' : '' }}" href="{{ route('roles.index') }}">نقش ها</a>
                </li>
            @endcan
        </ul>
        <ul id="navigationProducts" class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit','products','products/create','products/{product}/edit','printers','printers/create','printers/{printer}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">محصولات</li>
{{--            @can('categories-list')--}}
{{--                <li>--}}
{{--                    <a class="{{ active_sidebar(['categories','categories/create','categories/{category}/edit']) ? 'active' : '' }}" href="{{ route('categories.index') }}">دسته بندی ها</a>--}}
{{--                </li>--}}
{{--            @endcan--}}
            @can('products-list')
                <li>
                    <a class="{{ active_sidebar(['products','products/create','products/{product}/edit']) ? 'active' : '' }}" href="{{ route('products.index') }}">محصولات</a>
                </li>
            @endcan
            @can('printers-list')
                <li>
                    <a class="{{ active_sidebar(['printers','printers/create','printers/{printer}/edit']) ? 'active' : '' }}" href="{{ route('printers.index') }}">پرینتر ها</a>
                </li>
            @endcan
        </ul>
        <ul id="navigationInvoices" class="{{ active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit']) ? 'navigation-active' : '' }}">
            <li class="navigation-divider">پیش فاکتور</li>
            @can('invoices-list')
                <li>
                    <a class="{{ active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit']) ? 'active' : '' }}" href="{{ route('invoices.index') }}">پیش فاکتور</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
<!-- end::navigation -->
