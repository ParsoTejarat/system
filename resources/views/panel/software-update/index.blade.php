@extends('panel.layouts.master')
@section('title', 'تغییرات نرم افزار')
@section('content')
    {{--  items Modal  --}}
    <div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">تغییرات نرم افزار</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <ol></ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    {{--  end items Modal  --}}
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">تغییرات نرم افزار</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                @can('software-update-create')
                                    <a href="{{ route('software-update.create') }}" class="btn btn-primary">
                                        <i class="fa fa-plus mr-2"></i>
                                        ثبت تغییرات
                                    </a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ورژن</th>
                                        <th>تاریخ ثبت</th>
                                        @can('software-update-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('software-update-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($software_updates as $key => $software)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $software->version }}</td>
                                            <td>{{ verta($software->created_at)->format('H:i - Y/m/d') }}</td>
                                            @can('software-update-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating"
                                                       href="{{ route('software-update.edit', $software->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('software-update-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('software-update.destroy',$software->id) }}"
                                                            data-id="{{ $software->id }}">
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
                            <div class="d-flex justify-content-center">{{ $software_updates->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
