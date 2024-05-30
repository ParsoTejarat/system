@extends('panel.layouts-copy.master')
@section('title', 'ویرایش دسته بندی')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش دسته بندی</h6>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="name">نام دسته بندی<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}"
                               placeholder="نویسنده">
                        @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="slug">اسلاگ<span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}"
                               placeholder="writer">
                        @error('slug')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
@endsection

