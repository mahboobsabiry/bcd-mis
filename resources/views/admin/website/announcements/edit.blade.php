@extends('layouts.admin.master')
<!-- Title -->
@section('title', trans('global.edit') . ' - ' . $announcement->title)
<!-- Extra Styles -->
@section('extra_css')

@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">ویرایش اعلامیه</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.website.announcements.index') }}">@lang('pages.website.announcements')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.website.announcements.show', $announcement->id) }}">@lang('global.details')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('global.edit')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Back -->
                <a class="btn btn-orange btn-sm btn-with-icon" href="{{ url()->previous() }}">
                    @lang('global.back')
                    <i class="fe fe-arrow-left"></i>
                </a>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Errors Message -->
                @include('admin.inc.alerts')

                <!-- Card -->
                <div class="card">
                    <!-- Form Title -->
                    <div class="card-header">
                        <h6 class="card-title mb-1">ویرایش اعلامیه</h6>
                        <p class="text-muted card-sub-title">.</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.website.announcements.update', $announcement->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Title -->
                                        <div class="form-group @error('title') has-danger @enderror">
                                            <p class="mb-2">@lang('form.title'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="title" class="form-control @error('title') form-control-danger @enderror" name="title" value="{{ $announcement->title ?? old('title') }}" required>

                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Title -->

                                        <!-- Body Text -->
                                        <div class="form-group @error('body') has-danger @enderror">
                                            <p class="mb-2">{{ __('متن') }}:</p>
                                            <textarea name="body" id="body" class="form-control @error('body') form-control-danger @enderror" placeholder="{{ __('متن') }}">{{ $announcement->body ?? old('body') }}</textarea>

                                            @error('body')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Body Text -->
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Image -->
                                        <div class="form-group @error('img') has-danger @enderror">
                                            <p class="mb-2">@lang('pages.hostel.roomSection'): </p>
                                            <input type="file" id="img" class="form-control @error('img') form-control-danger @enderror" name="img">

                                            @if($announcement->img)
                                                <a href="{{ asset('website/images/announcements/' . $announcement->img) }}" target="_blank">
                                                    <img class="img-fluid" src="{{ asset('website/images/announcements/' . $announcement->img) }}" alt="" width="50">
                                                </a>
                                                <!-- Delete Image -->
                                                <a onclick="return confirm('{{ trans('global.areYouSure') }}');" href="{{ asset('website/images/announcements/' . $announcement->img) }}" target="_blank">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            @endif

                                            @error('img')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Image -->

                                        <div class="form-group float-left">
                                            <button class="btn ripple btn-primary rounded-2" type="submit">@lang('global.save')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--/==/ End of Form -->
                        </div>
                    </div>
                    <!--/==/ End of Card Body -->
                </div>
                <!--/==/ End of Card -->
            </div>
        </div>
        <!--/==/ End of Main Row -->
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/advanced-form-elements.js') }}"></script>

    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
