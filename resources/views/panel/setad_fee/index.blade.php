@extends('panel.layouts.master')
@section('title', 'کارمزد سامانه ستاد')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">کارمزد سامانه ستاد</h4>
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
{{--                                    <form action="{{ route('orders.excel') }}" method="post" id="excel_form">--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}

{{--                                    <button class="btn btn-success" form="excel_form">--}}
{{--                                        <i class="fa fa-file-excel mr-2"></i>--}}
{{--                                        دریافت اکسل--}}
{{--                                    </button>--}}

                                    @can('setad-fee-create')
                                        @cannot('accountant')
                                            <a href="{{ route('setad-fee.create') }}" class="btn btn-primary">
                                                <i class="fa fa-plus mr-2"></i>
                                                ایجاد کارمزد ستاد
                                            </a>
                                        @endcannot
                                    @endcan
                                </div>
                            </div>
                            <form action="{{ route('setad-fee.index') }}" method="get" id="search_form"></form>
                            <div class="row mb-3 mt-5">
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <input type="text" form="code" name="code" class="form-control"
                                           value="{{ request()->code ?? null }}" placeholder="شماره سفارش یا کد رهگیری">
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <select name="status" form="search_form" class="form-control" data-toggle="select2">
                                        <option value="all">وضعیت (همه)</option>
                                        @foreach(\App\Models\SetadFee::STATUS as $key => $value)
                                            <option
                                                value="{{ $key }}" {{ request()->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary" form="search_form">جستجو</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه سفارش</th>
                                        <th>کد رهگیری</th>
                                        <th>مبلغ (ریال)</th>
                                        <th>وضعیت</th>
                                        @canany(['accountant', 'sales-manager','admin','ceo'])
                                            <th>همکار</th>
                                        @endcanany
                                        <th>تاریخ ایجاد</th>

                                        @canany(['accountant','sales-manager'])
                                            <th>اقدام</th>
                                        @endcanany

                                        @cannot('accountant')
                                            @can('setad-fee-edit')
                                                <th>ویرایش</th>
                                            @endcan
                                            @can('setad-fee-delete')
                                                <th>حذف</th>
                                            @endcan
                                        @endcannot
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($setadFees as $key => $setad)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $setad->order->code }}</td>
                                            <td>{{ $setad->tracking_number }}</td>
                                            <td>{{ number_format($setad->price) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-primary d-block">{{ \App\Models\SetadFee::STATUS[$setad->status] }}</span>
                                            </td>
                                            @canany(['accountant', 'sales-manager','admin','ceo'])
                                                <td>{{ $setad->user->fullName() }}</td>
                                            @endcanany
                                            <td>{{ verta($setad->created_at)->format('H:i - Y/m/d') }}</td>
                                            @canany(['accountant','sales-manager'])
                                                <td>
                                                    <a class="btn btn-primary btn-floating"
                                                       href="{{ route('setad-fee.action', $setad->id) }}" >
                                                        <i class="fa fa-edit"></i></a>
                                                </td>
                                            @endcanany
                                            @cannot('accountant')
                                                @can('sales-manager')
                                                    @can('setad-fee-edit')
                                                        <td>
                                                            <a class="btn btn-warning btn-floating {{ $setad->status == 'approved' ? 'disabled' : '' }}"
                                                               href="{{ route('setad-fee.edit', $setad->id) }}" >
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    @endcan
                                                    @can('setad-fee-delete')
                                                        <td>
                                                            <button class="btn btn-danger btn-floating trashRow"
                                                                    data-url="{{ route('setad-fee.destroy',$setad->id) }}"
                                                                    data-id="{{ $setad->id }}" {{ $setad->status == 'approved' ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    @endcan
                                                @else
                                                    @can('setad-fee-edit')
                                                        <td>
                                                            <a class="btn btn-warning btn-floating"
                                                               href="{{ route('setad-fee.edit', $setad->id) }}" {{ $setad->status == 'approved' ? 'disabled' : '' }}>
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </td>
                                                    @endcan
                                                    @can('setad-fee-delete')
                                                        <td>
                                                            <button class="btn btn-danger btn-floating trashRow"
                                                                    data-url="{{ route('setad-fee.destroy',$setad->id) }}"
                                                                    data-id="{{ $setad->id }}" {{ $setad->status == 'approved' ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    @endcan
                                                @endcan
                                            @endcannot
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
                                class="d-flex justify-content-center">{{ $setadFees->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timelineModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <!-- تایم‌لاین عمودی -->
                    <div class="d-flex flex-column position-relative">

                        <!-- مرحله 1 (متن در چپ) -->
                        <div class="timeline-content" style="display: none;">
                        </div>


                        <div class="loading">
                            <div class="lds-roller">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.show-status', function () {
                var id = $(this).data('id');
                var code = $(this).data('code');
                $('#timelineModalLabel').text(`وضعیت سفارش ${code}`)
                var loading = $('.loading');
                var timelineContent = $('.timeline-content');
                timelineContent.empty();
                loading.show();
                $.ajax({
                    url: '/panel/get-customer-order-status/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        loading.hide();
                        console.log('Response:', response);
                        response.forEach((stage, index) => {
                            const hasDate = stage.date !== '';
                            const stageClass = stage.pending ? 'bg-warning' : hasDate ? 'bg-success' : 'bg-secondary';
                            const icon = stage.pending ? '<i class="fa fa-undo rotate-icon"></i>' : hasDate ? '✓' : '✖';
                            const date = stage.pending ? 'در حال بررسی' : hasDate ? stage.date : '';

                            const progressBar = index === 0 ? '' : `
                            <div class="progress progress-vertical ${stageClass}">
                                <div class="progress-bar progress-bar-striped progress-bar-animated ${stageClass}" role="progressbar" style="height: 100%;"></div>
                            </div>
                        `;

                           const stageTemplate = `
                            ${progressBar}
                            <div class="timeline-stage stage-left d-flex align-items-center">
                                <div class="rounded-circle ${stageClass} text-white stage-circle me-2">${icon}</div>
                                <div>
                                    <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">${stage.status_label}</h6>
                                    <small class="stage-text">${date}</small>
                                </div>
                            </div>
                        `;

                            timelineContent.append(stageTemplate);
                        });

                        timelineContent.show();
                    }
                    ,

                    error: function (xhr, status, error) {
                        console.log('Error:', error);
                        loading.hide();
                    }
                });

            });
        });
    </script>
@endsection


