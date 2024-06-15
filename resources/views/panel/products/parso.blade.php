@extends('panel.layouts.master')
@section('title', 'محصولات سایت پرسو تجارت')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">محصولات سایت پرسو تجارت</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('parso.index') }}" method="post" id="search_form">
                                @csrf
                            </form>
                            <div class="row mb-3">
                                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                    <input type="text" name="sku" class="form-control" placeholder="کد محصول (sku)" value="{{ request()->sku ?? null }}" form="search_form">
                                </div>
                                <div class="col-xl-3 xl-lg-3 col-md-4 col-sm-12 mt-2">
                                    <input type="text" name="title" class="form-control" placeholder="عنوان محصول" value="{{ request()->title ?? null }}" form="search_form">
                                </div>
                                <div class="col-xl-2 xl-lg-2 col-md-3 col-sm-12 mt-2">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



