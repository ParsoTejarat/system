@include('panel.layouts.head')

{{--@include('panel.layouts.loader')--}}
@include('panel.layouts.sidebar')
@include('panel.layouts.header')

<!-- begin::main content -->
<main class="main-content">
    @include('sweet::alert')
    @yield('content')
</main>
<!-- end::main content -->

@include('panel.layouts.scripts')
@include('panel.layouts.footer')
