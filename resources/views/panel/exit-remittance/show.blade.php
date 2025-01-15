@extends('panel.layouts.master')
@section('title', 'حواله خروج')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">حواله خروج</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>کد انبار : 1</th>
                                        <th> کد تحویل گیرنده : {{$exitRemittance->customer->code??'-'}} </th>
                                        <th>شناسه حواله : {{$exitRemittance->code}}</th>
                                    </tr>
                                    <tr>
                                        <th>عنوان انبار: مرکزی</th>
                                        <th>عنوان تحویل گیرنده : {{$exitRemittance->customer->name}}</th>
                                        <th> تاریخ ثبت : {{verta($exitRemittance->created_at)->format('H:i   %Y/%m/%d')}}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>شناسه کالا</th>
                                        <th>شرح کالا</th>
                                        <th>دسته بندی کالا</th>
                                        <th>برند</th>
                                        <th>رنگ</th>
                                        <th>مقدار اصلی</th>
                                        <th>واحد اصلی</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $sum = 0; @endphp
                                    @foreach(json_decode($exitRemittance->products) as $product)
                                        @php $sum += $product->count; @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $product->sku??'-' }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->category??'-' }}</td>
                                            <td>{{ $product->brand??'-' }}</td>
                                            <td>{{ $product->color }}</td>
                                            <td>{{ $product->count }}</td>
                                            <td>{{ $product->unit??'-' }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="8" class="text-start"><span
                                                class="text-start fw-bold"> مجموع : {{$sum}}</span></td>
                                    </tr>
                                    </tbody>

                                </table>

                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <form action="{{route('exitRemittances.approvedExit')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mt-2">
                                            <label>
                                                فایل اکسل شناسه های رهگیری
                                                <span class="text-danger">*</span>
                                                <a href="{{asset('/excel-sample.xlsx')}}" download>نمونه فایل اکسل</a>
                                            </label>
                                            <input type="file" name="file_excel" accept=".xlsx, .xls"
                                                   class="form-control mt-2">
                                            <input type="hidden" name="sum_count" value="{{$sum}}">
                                            <input type="hidden" name="exit_remittance_id" value="{{$exitRemittance->id}}">
                                            @error('file_excel')
                                            <span class="text-danger">{{$message}}</span>
                                            <br>
                                            @enderror
                                            @error('sum_count')
                                            <span class="text-danger">{{$message}}</span>
                                            <br>
                                            @enderror
                                            @error('exit_remittance_id')
                                            <span class="text-danger">{{$message}}</span>
                                            <br>
                                            @enderror
                                            <button type="submit" class="btn btn-success mt-2">ارسال فایل اکسل و ثبت
                                                خروج
                                            </button>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-8">
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <a href="{{route('exitRemittances.downloadPDF',$exitRemittance->id)}}" class="btn btn-outline-danger mt-2 float-end"> <span class=" fa fa-file-pdf"></span> پرینت حواله</a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

