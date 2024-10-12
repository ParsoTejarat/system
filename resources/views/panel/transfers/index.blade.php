@extends('panel.layouts.master')
@section('title', 'مشخصات گیرنده / فرستنده')
@section('content')


    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">بسته های ارسالی</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-end">
                                <div>
{{--                                    <form action="{{ route('transfers.excel') }}" method="post" id="excel_form">--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}

{{--                                    <button class="btn btn-success" form="excel_form">--}}
{{--                                        <i class="fa fa-file-excel me-2"></i>--}}
{{--                                        دریافت اکسل--}}
{{--                                    </button>--}}

                                    @can('transfer-create')
                                        <a href="{{ route('transfers.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i>
                                            ایجاد بسته ارسالی
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            <form action="{{ route('transfers.index') }}" method="get" id="search_form"></form>
                            <div class="row mb-3">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <input name="code" form="search_form" placeholder="شناسه سفارش" class="form-control"/>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه سفارش</th>
                                        <th>گیرنده</th>
                                        <th>شماره تماس</th>
                                        <th>آدرس</th>
                                        <th>تاریخ ایجاد</th>
                                        <th>چاپ مشخصات پستی</th>
                                        @can('transfer-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('transfer-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $search = request()->input('code');
                                    @endphp
                                    @foreach($transfers as $key => $transfer)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            @php
                                                $highlightedNumber = $transfer->code ?? '---';
                                                if ($search) {
                                                    $highlightedNumber = str_ireplace($search, "<span class='bg-warning'>" . $search . "</span>", $highlightedNumber);
                                                }
                                            @endphp
                                            <td>{!! $highlightedNumber !!}</td>
                                            <td>{{ $transfer->recipient_name }}</td>
                                            <td>{{ $transfer->phone }}</td>
                                            <td>{{ $transfer->address }}</td>
                                            <td>{{ verta($transfer->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <a href="{{ route('transfers.download', $transfer) }}"
                                                   class="btn btn-info btn-floating" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                            @can('transfer-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating"
                                                       href="{{ route('transfers.edit',$transfer->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('transfer-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow "
                                                            data-url="{{ route('transfers.destroy',$transfer->id) }}">
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
                            <div
                                class="d-flex justify-content-center">{{ $transfers->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
