@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'تمدید تعرفه ترجیحی')
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.examination.preferential_tariffs.index') }}">تعرفه ترجیحی - جایداد اموال</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.examination.preferential_tariffs.show', $tariff->id) }}">@lang('global.details')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تمدید تعرفه ترجیحی</li>
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
                        <h6 class="card-title tx-15 tx-bold mb-1">تمدید تعرفه ترجیحی مکتوب نمبر {{ $tariff->doc_number }}</h6>
                        <p class="text-muted card-sub-title">در این قسمت مهلت تمدید میشود.</p>
                        <p class="font-weight-bold">
                            تاریخ ختم اعتبار {{ $tariff->end_date }}&nbsp; -
                            مدت باقیمانده @php
                                $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->end_date)->toCarbon();
                                $remaining_days = now()->diffInDays($end_date);
                            @endphp
                            @if($remaining_days > 10)
                                {{ $remaining_days }} روز
                            @else
                                <span class="text-danger">{{ $remaining_days }} روز</span>
                                &nbsp;&nbsp;&nbsp;
                                <span class="fas fa-dollar-sign fa-pulse text-danger"></span>
                            @endif
                        </p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.examination.preferential_tariffs.renewal', $tariff->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Company -->
                                        <div class="form-group">
                                            <p class="mb-2"> شرکت:</p>
                                            <select class="form-control" disabled>
                                                <option>{{ $tariff->company->name }}</option>
                                            </select>
                                        </div>
                                        <!--/==/ End of Company -->

                                        <!-- Document Number -->
                                        <div class="form-group @error('doc_number') has-danger @enderror">
                                            <p class="mb-2">نمبر مکتوب: <span class="tx-danger">*</span></p>
                                            <input type="text" id="doc_number" class="form-control @error('doc_number') form-control-danger @enderror" name="doc_number" value="{{ old('doc_number') }}" required>

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
                                            <input data-jdp type="text" id="doc_date" class="form-control @error('doc_date') form-control-danger @enderror" name="doc_date" value="{{  old('doc_date') }}" placeholder="{{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}" required>

                                            @error('doc_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Document Date -->

                                        <!-- End Date -->
                                        <div class="form-group @error('end_date') has-danger @enderror">
                                            <p class="mb-2"> تاریخ ختم تمدید: <span class="tx-danger">*</span></p>
                                            <input data-jdp type="text" id="end_date" class="form-control @error('end_date') form-control-danger @enderror" name="end_date" value="{{ old('end_date') }}" placeholder="{{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}" required>

                                            @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of End Date -->

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
