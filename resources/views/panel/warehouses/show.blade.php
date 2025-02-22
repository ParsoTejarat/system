@extends('panel.layouts.master')
@section('title', 'انبار')
@section('styles')
    <style>
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">جزئیات موجودی کالا {{$product->title}}</h4>

                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">

                <div class="col">

                    <div class="card">
                        <div class="col-4 p-2">
                            <input id="myInput" placeholder="جستجو..." class="col-4 form-control">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable dtr-inline text-center">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شناسه کالا</th>
                                        <th>شناسه رهگیری کالا</th>
                                        <th>شرح کالا</th>
                                        <th>تاریخ ثبت</th>
                                        <th>حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTable">
                                    @foreach($trackingCodes as $trackingCode)
                                        <tr>
                                            <td>{{$loop->index + 1}}</td>
                                            <td>{{$trackingCode->product->product_barcode??'-'}}</td>
                                            <td>{{$trackingCode->code}}</td>
                                            <td>{{$trackingCode->product->title}}</td>
                                            <td>{{verta($trackingCode->created_at)->format('H:i %Y/%m/%d')}}</td>
                                            <td>
                                                <button class="btn btn-danger btn-floating trashRow"
                                                        data-url="{{ route('tracking.deleteCode',$trackingCode->id) }}"
                                                        data-id="{{ $trackingCode->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            {{--                            <div--}}
                            {{--                                    class="d-flex justify-content-center">{{ $products->appends(request()->all())->links() }}</div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endsection


