@extends('panel.layouts.master')
@section('title', 'برند ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">برند ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end align-items-center">
                                @can('brands-create')
                                    <a href="{{ route('brands.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ایجاد برند
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>برند</th>
                                        <th>نام لاتین</th>
                                        <th>تاریخ ایجاد</th>
                                        @can('brands-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('brands-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($brands as $key => $brand)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $brand->name }}</td>
                                            <td>{{ $brand->name_en }}</td>
                                            <td>{{ verta($brand->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('brands-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating" href="{{ route('brands.edit', $brand->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('brands-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('brands.destroy', $brand->id) }}"
                                                            data-id="{{ $brand->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">{{ $brands->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
