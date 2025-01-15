@extends('panel.layouts.master')
@section('title', 'ایجاد برند')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ایجاد برند</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('brands.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="name" class="form-label">نام برند<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                               value="{{ old('name') }}"
                                               placeholder=" مثال : اچ پی">
                                        @error('name')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">نام انگلیسی<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name_en" class="form-control" id="name_en"
                                               value="{{ old('name_en') }}"
                                               placeholder="مثال :HP ">
                                        @error('name_en')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                                        <label for="slug" class="form-label">دسته بندی ها<span class="text-danger">*</span></label>
                                        <select name="categories[]" id="categories" class="select2" multiple="true">
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('categories')
                                        <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#categories').select2({
            placeholder: "انتخاب دسته‌بندی ‌ها",
            allowClear: true
        });
    </script>
@endsection


