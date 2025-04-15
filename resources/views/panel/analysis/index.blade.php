@extends('panel.layouts.master')
@section('title', 'مدیریت آنالیز فروش')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">آنالیز فروش</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($analysises as $key => $analysis)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $analysis->product->name ?? '---' }}</td>
                                            <td>{{ $analysis->category->name ?? '---' }}</td>
                                            <td>{{ $analysis->brand->name ?? '---' }}</td>
                                            <td>{{ $analysis->total_count }}</td>
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
