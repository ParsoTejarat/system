@extends('panel.layouts.master')
@section('title', 'پنل مدیریت')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>شبکه های فروش</h6>
                <div class="slick-single-arrows">
                    <a class="btn btn-outline-light btn-sm">
                        <i class="ti-angle-right"></i>
                    </a>
                    <a class="btn btn-outline-light btn-sm">
                        <i class="ti-angle-left"></i>
                    </a>
                </div>
            </div>
            <div class="row slick-single-item">
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-danger icon-block-floating mr-2">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">همه</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-danger primary-font line-height-30">2,587</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 71%" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-success icon-block-floating mr-2">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">مستقیم</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-success primary-font line-height-30">562</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 39%" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-info icon-block-floating mr-2">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">تلفن</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-info primary-font line-height-30">256</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-facebook icon-block-floating mr-2">
                                        <i class="fa fa-facebook"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">فیسبوک</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-facebook primary-font line-height-30">147</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-facebook" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-whatsapp icon-block-floating mr-2">
                                        <i class="fa fa-whatsapp"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">واتس اپ</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-whatsapp primary-font line-height-30">874</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-whatsapp" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-instagram icon-block-floating mr-2">
                                        <i class="fa fa-instagram"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">اینستاگرام</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-instagram primary-font line-height-30">1,968</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-instagram" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-google icon-block-floating mr-2">
                                        <i class="fa fa-instagram"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">گوگل</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-google primary-font line-height-30">3654</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-google" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-lg-6 col-sm-12">
                    <div class="card border mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="icon-block icon-block-sm bg-warning icon-block-floating mr-2">
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                                <span class="font-size-13">سایر</span>
                                <h2 class="mb-0 ml-auto font-weight-bold text-warning primary-font line-height-30">206</h2>
                            </div>
                            <div class="progress" style="height: 5px">
                                <div class="progress-bar bg-warning-gradient" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

