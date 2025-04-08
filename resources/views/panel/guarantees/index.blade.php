@extends('panel.layouts.master')
@section('title', 'گارانتی ها')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">گارانتی ها</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between align-items-center flex-wrap">

                                {{-- فرم جست‌وجو سمت راست --}}
                                <div class="d-flex" style="gap: 8px;">
                                    <form action="" method="GET" class="d-flex" style="gap: 8px;">
                                        <input type="text" name="serial_number" class="form-control" placeholder="شماره سریال..." value="{{ request('serial_number') ?? '' }}">

                                        <select name="status" class="form-control">
                                            <option value="" disabled selected>وضعیت را انتخاب کنید</option>
                                            @foreach(App\Models\Guarantee::STATUS as $key => $label)
                                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-success">جست‌وجو</button>
                                    </form>
                                </div>

                                {{-- دکمه‌ها سمت چپ --}}
                                <div class="d-flex" style="gap: 8px;">
                                    @can('guarantees-create')
                                        <a href="{{ route('guarantees.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i>
                                            ایجاد گارانتی
                                        </a>
                                    @endcan
                                    <button class="btn btn-success" id="export-excel-btn"  disabled>
                                        <i class="fa fa-file-excel me-2"></i>
                                        خروجی اکسل
                                    </button>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                    <tr>
                                        <th><input type="checkbox" class="form-check-input" id="select-all"></th>
                                        <th>#</th>
                                        <th>شماره سریال</th>
                                        <th>شماره سفارش مشتری</th>
                                        <th>شرح کالا</th>
                                        <th>مدت گارانتی</th>
                                        <th>تاریخ فعالسازی</th>
                                        <th>تاریخ انقضا</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ثبت</th>
                                        <th>نمایش</th>
                                        <th>چاپ</th>
                                        @can('guarantees-edit')
                                            <th>ویرایش</th>
                                        @endcan
                                        @can('guarantees-delete')
                                            <th>حذف</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($guarantees as $key => $guarantee)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="row-checkbox form-check-input" value="{{ $guarantee->id }}">
                                            </td>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $guarantee->serial_number }}</td>
                                            <td>

                                                @if($guarantee->order && $guarantee->order->code)
                                                    <a href="/panel/orders?code={{ $guarantee->order->code }}">{{ $guarantee->order->code }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>{{ $guarantee->product->title }}</td>
                                            <td>{{ \App\Models\Guarantee::PERIOD[$guarantee->period] }}</td>
                                            <td>{{ $guarantee->start_time ? verta($guarantee->start_time)->format('Y/m/d') : '---' }}</td>
                                            <td>{{ $guarantee->expire_time ? verta($guarantee->expire_time)->format('Y/m/d') : '---' }}</td>
                                            <td>
                                                @if($guarantee->status == 'active')
                                                    <span
                                                        class="badge bg-success">{{ \App\Models\Guarantee::STATUS[$guarantee->status] }}</span>
                                                @elseif($guarantee->status == 'pending')
                                                    <span
                                                        class="badge bg-secondary">{{ \App\Models\Guarantee::STATUS[$guarantee->status] }}</span>
                                                @elseif($guarantee->status == 'inactive')
                                                    <span
                                                        class="badge bg-warning">{{ \App\Models\Guarantee::STATUS[$guarantee->status] }}</span>
                                                @elseif($guarantee->status == 'expired')
                                                    <span
                                                        class="badge bg-danger">{{ \App\Models\Guarantee::STATUS[$guarantee->status] }}</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger">{{ \App\Models\Guarantee::STATUS[$guarantee->status] }}</span>
                                                @endif
                                            </td>
                                            <td>{{ verta($guarantee->created_at)->format('H:i - Y/m/d') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('guarantees.show', $guarantee->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-floating"
                                                   href="{{ route('guarantees.print', $guarantee->id) }}">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                            @can('guarantees-edit')
                                                <td>
                                                    <a class="btn btn-warning btn-floating"
                                                       href="{{ route('guarantees.edit', $guarantee->id) }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('guarantees-delete')
                                                <td>
                                                    <button class="btn btn-danger btn-floating trashRow"
                                                            data-url="{{ route('guarantees.destroy',$guarantee->id) }}"
                                                            data-id="{{ $guarantee->id }}">
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
                                class="d-flex justify-content-center">{{ $guarantees->appends(request()->all())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
        <script>
            $(document).ready(function () {
                $('#select-all').on('change', function () {
                    $('.row-checkbox').prop('checked', this.checked).trigger('change');
                });

                $('.row-checkbox').on('change', function () {
                    let selected = $('.row-checkbox:checked').length;
                    $('#export-excel-btn').prop('disabled', selected === 0);
                });

                $('#export-excel-btn').on('click', function (e) {
                    e.preventDefault();

                    let ids = $('.row-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();

                    if (ids.length === 0) return;

                    let form = $('<form>', {
                        action: '{{ route('guarantees.export') }}',
                        method: 'POST'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    ids.forEach(id => {
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });
            });



        </script>

@endsection
