@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')

@section('styles')
    <style>
        #stats i.fa, i.fab {
            font-size: 15px;
        }


        .progress-vertical {
            width: 10px;
            height: 30px;
            min-height: 45px;
            margin: 0 15px 0 0;
        }

        /* تنظیم مراحل به صورت یکی در میان چپ و راست */
        .timeline-stage {
            display: flex;
            align-items: center;
        }

        .stage-circle {
            width: 40px;
            height: 40px;
            line-height: 45px;
            text-align: center;
            flex-shrink: 0;
        }

        .progress, .progress-stacked {
            border-radius: 0;
        }


        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            margin: 1px 0;
        }
        .modal-body{
            margin-right: 20%;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .rotate-icon {
            display: inline-block;
            animation: spin 1s linear infinite;
        }
    </style>

@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">پنل</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row" id="stats">
                <div class="col-xl-12 col-md-12">

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#timelineModal">
                        نمایش تایم‌لاین
                    </button>

                    <!-- مودال -->
                    <div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="timelineModalLabel"> وضعیت سفارش 6665986</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="بستن"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- تایم‌لاین عمودی -->
                                    <div class="d-flex flex-column position-relative">

                                        <!-- مرحله 1 (متن در چپ) -->
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-success text-white stage-circle me-2">✓</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">ثبت سفارش مشتری</h6>
                                                <small class="stage-text">1403/07/11</small>
                                            </div>
                                        </div>

                                        <!-- خط عمودی -->
                                        <div class="progress progress-vertical bg-success">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>

                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-success text-white stage-circle me-2">✓</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">در انتظار بررسی توسط حسابدار</h6>
                                                <small class="stage-text">1403/07/11</small>
                                            </div>
                                        </div>

                                        <!-- خط عمودی -->
                                        <div class="progress progress-vertical bg-success">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-success text-white stage-circle me-2">✓</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">صدور پیش فاکتور</h6>
                                                <small class="stage-text">1403/07/11</small>
                                            </div>
                                        </div>

                                        <!-- هشدار -->
                                        <div class="progress progress-vertical bg-warning">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-warning text-white stage-circle me-2"><i class="fa fa-undo rotate-icon"></i></div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">در انتظار تایید توسط همکار فروش</h6>
                                                <small class="stage-text">درحال بررسی</small>
                                            </div>
                                        </div>

                                          {{--     مونده--}}
                                        <div class="progress progress-vertical bg-secondary">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white stage-circle me-2">✖</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">ثبت کارمزد ستاد</h6>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical bg-secondary">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white stage-circle me-2">✖</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">در انتظار بررسی توسط حسابدار</h6>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical bg-secondary">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white stage-circle me-2">✖</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">آپلود رسید کارمزد</h6>
                                            </div>
                                        </div>
                                        <div class="progress progress-vertical bg-secondary">
                                            <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
                                                role="progressbar" style="height: 100%;"></div>
                                        </div>
                                        <div class="timeline-stage stage-left d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary text-white stage-circle me-2">✖</div>
                                            <div>
                                                <h6 class="stage-text" style="font-weight: bolder;font-size: medium;">صدور فاکتور</h6>
                                            </div>
                                        </div>
                                        <!-- می‌توانید مراحل بیشتری اضافه کنید -->

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>
@endsection
