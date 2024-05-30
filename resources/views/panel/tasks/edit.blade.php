@extends('panel.layouts.master')
@section('title', 'ویرایش وظیفه')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">ویرایش وظیفه</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('tasks.update', $task->id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="title" class="form-label">عنوان <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title" value="{{ $task->title }}">
                                        @error('title')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-8"></div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="description" class="form-label">توضیحات <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" name="description" id="description" rows="5">{{ $task->description }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-8"></div>
                                    <div class="mb-2 col-xl-3 col-lg-3 col-md-3">
                                        <label for="users" class="mb-1">تخصیص به </label>
                                        <select class="form-control" data-toggle="select2" name="users[]" id="users" multiple>
                                            @foreach(\App\Models\User::where('id','!=',auth()->id())->get() as $user)
                                                <option value="{{ $user->id }}" {{ $task->users->pluck('id')->toArray() ? (in_array($user->id, $task->users->pluck('id')->toArray()) ? 'selected' : '') : '' }}>{{ $user->fullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



