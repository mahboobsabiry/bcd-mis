@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'ویرایش جایداد')
<!-- Extra Styles -->
@section('extra_css')
    <!---Fileupload css-->
    <link href="{{ asset('backend/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet">
    <!---Fancy uploader css-->
    <link href="{{ asset('backend/assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet">
    <!--Sumoselect css-->
    <link href="{{ asset('backend/assets/plugins/sumoselect/sumoselect.css') }}" rel="stylesheet">
@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('global.new')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.examination.properties.index') }}">تعرفه ترجیحی - جایداد اموال</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.examination.properties.show', $property->id) }}">@lang('global.details')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ویرایش جایداد</li>
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
                        <h6 class="card-title tx-15 tx-bold mb-1">ویرایش جایداد {{ $property->property_name }}</h6>
                        <p class="text-muted card-sub-title">در این قسمت جایداد اموال ویرایش میشود.</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.examination.properties.update', $property->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Company -->
                                        <div class="form-group @error('company_id') has-danger @enderror">
                                            <p class="mb-2"> شرکت: <span class="tx-danger">*</span></p>
                                            <select class="form-control @error('company_id') has-danger @enderror select2" name="company_id">
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ $property->company->id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>

                                            @error('company_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Company -->

                                        <!-- Document Number && Date -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Document Number -->
                                                <div class="form-group @error('doc_number') has-danger @enderror">
                                                    <p class="mb-2">نمبر مکتوب: <span class="tx-danger">*</span></p>
                                                    <input type="text" id="doc_number" class="form-control @error('doc_number') form-control-danger @enderror" name="doc_number" value="{{ $property->doc_number ?? old('doc_number') }}" required>

                                                    @error('doc_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--/==/ End of Document Number -->
                                            </div>

                                            <div class="col-md-6">
                                                <!-- Document Date -->
                                                <div class="form-group @error('doc_date') has-danger @enderror">
                                                    <p class="mb-2">تاریخ مکتوب: <span class="tx-danger">*</span></p>
                                                    <input data-jdp type="text" id="doc_date" class="form-control @error('doc_date') form-control-danger @enderror" name="doc_date" value="{{ $property->doc_date ?? old('doc_date') }}" required>

                                                    @error('doc_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--/==/ End of Document Date -->
                                            </div>
                                        </div>
                                        <!--/==/ End of Document Number && Date -->

                                        <!-- Property Name -->
                                        <div class="form-group @error('property_name') has-danger @enderror">
                                            <p class="mb-2">نوع جنس: <span class="tx-danger">*</span></p>
                                            <input type="text" id="property_name" class="form-control @error('property_name') form-control-danger @enderror" name="property_name" value="{{ $property->property_name ?? old('property_name') }}" required>

                                            @error('property_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Property Name -->

                                        <!-- Property and TS Code -->
                                        <div class="row">
                                            <div class="col-md-7">
                                                <!-- Property Code -->
                                                <div class="form-group @error('property_code') has-danger @enderror">
                                                    <p class="mb-2">کد جنس: <span class="tx-danger">*</span></p>
                                                    <input type="number" id="property_code" class="form-control @error('property_code') form-control-danger @enderror" name="property_code" value="{{ $property->property_code ?? old('property_code') }}" required>

                                                    @error('property_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--/==/ End of Property Code -->
                                            </div>
                                            <div class="col-md-5">
                                                <!-- TS Code -->
                                                <div class="form-group @error('ts_code') has-danger @enderror">
                                                    <p class="mb-2">TSC: <span class="tx-danger">*</span></p>
                                                    <input type="number" id="ts_code" class="form-control @error('ts_code') form-control-danger @enderror" name="ts_code" value="{{ $property->ts_code ?? old('ts_code') }}" required>

                                                    @error('ts_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--/==/ End of TS Code -->
                                            </div>
                                        </div>
                                        <!--/==/ End of Property and TS Code -->

                                        <!-- Total Weight -->
                                        <div class="form-group @error('weight') has-danger @enderror">
                                            <p class="mb-2">مقدار جنس به کیلوگرام: <span class="tx-danger">*</span></p>
                                            <input type="number" id="weight" class="form-control @error('weight') form-control-danger @enderror" name="weight" value="{{ $property->weight ?? old('weight') }}" required>

                                            @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Total Weight -->

                                        <!-- Start Date -->
                                        <div class="form-group @error('start_date') has-danger @enderror">
                                            <p class="mb-2">از تاریخ: <span class="tx-danger">*</span></p>
                                            <input data-jdp data-jdp-max-date="today" type="text" id="start_date" class="form-control @error('start_date') form-control-danger @enderror" name="start_date" value="{{ $property->start_date ?? old('start_date') }}" required>

                                            @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Start Date -->
                                    </div>

                                    <div class="col-md-6">
                                        <!-- End Date -->
                                        <div class="form-group @error('end_date') has-danger @enderror">
                                            <p class="mb-2">الی تاریخ: <span class="tx-danger">*</span></p>
                                            <input data-jdp type="text" id="end_date" class="form-control @error('end_date') form-control-danger @enderror" name="end_date" value="{{ $property->end_date ?? old('end_date') }}" required>

                                            @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of End Date -->

                                        <!-- File -->
                                        <div class="form-group @error('photo') has-danger @enderror" id="avatar_div">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="mb-2">اسکن مکتوب:</p>
                                                </div>
                                                <div class="col-md-4 text-left">
                                                    <a href="{{ $property->image }}" target="_blank">
                                                        <img src="{{ $property->image }}" alt="" width="50">
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="file" class="dropify" name="photo" accept="image/*" data-height="200" />
                                            @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of File -->

                                        <!-- Extra Info -->
                                        <div class="form-group @error('info') has-danger @enderror">
                                            <p class="mb-2">@lang('global.extraInfo'): </p>
                                            <textarea id="info" class="form-control @error('info') form-control-danger @enderror" name="info">{{ $property->info ?? old('info') }}</textarea>

                                            @error('info')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Extra Info -->

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
    <!--Fileuploads js-->
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Fancy uploader js-->
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/advanced-form-elements.js') }}"></script>

    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
