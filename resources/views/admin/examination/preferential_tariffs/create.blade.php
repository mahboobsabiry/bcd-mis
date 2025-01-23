@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'ثبت تعرفه ترجیحی')
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
                    <li class="breadcrumb-item active" aria-current="page">ثبت تعرفه ترجیحی</li>
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
                        <h6 class="card-title tx-15 tx-bold mb-1">ثبت تعرفه ترجیحی</h6>
                        <p class="text-muted card-sub-title">در این قسمت تعرفه ترجیحی با مشخصات آن ثبت میشود.</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.examination.preferential_tariffs.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Company -->
                                        <div class="form-group @error('company_id') has-danger @enderror">
                                            <p class="mb-2 font-weight-bold"> شرکت: <span class="tx-danger">*</span></p>
                                            <select class="form-control @error('company_id') has-danger @enderror select2" name="company_id">
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->tin . ' - ' .$company->name }}</option>
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
                                                    <p class="mb-2 font-weight-bold">نمبر مکتوب: <span class="tx-danger">*</span></p>
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
                                                    <p class="mb-2 font-weight-bold">تاریخ مکتوب: <span class="tx-danger">*</span></p>
                                                    <input data-jdp type="text" id="doc_date" class="form-control @error('doc_date') form-control-danger @enderror" name="doc_date" value="{{ old('doc_date') }}" required>

                                                    @error('doc_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!--/==/ End of Document Date -->
                                            </div>
                                        </div>
                                        <!--/==/ End of Document Number && Date -->

                                        <!-- Start Date -->
                                        <div class="form-group @error('start_date') has-danger @enderror">
                                            <p class="mb-2 font-weight-bold">از تاریخ: <span class="tx-danger">*</span></p>
                                            <input data-jdp data-jdp-max-date="today" type="text" id="start_date" class="form-control @error('start_date') form-control-danger @enderror" name="start_date" value="{{ old('start_date') }}" required>

                                            @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Start Date -->

                                        <!-- End Date -->
                                        <div class="form-group @error('end_date') has-danger @enderror">
                                            <p class="mb-2 font-weight-bold">الی تاریخ: <span class="tx-danger">*</span></p>
                                            <input data-jdp type="text" id="end_date" class="form-control @error('end_date') form-control-danger @enderror" name="end_date" value="{{ old('end_date') }}" required>

                                            @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of End Date -->

                                        <!-- File -->
                                        <div class="form-group @error('photo') has-danger @enderror" id="avatar_div">
                                            <p class="mb-2 font-weight-bold">اسکن مکتوب:</p>
                                            <input type="file" class="dropify" name="photo" accept="image/*" data-height="200" />
                                            @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of File -->

                                        <!-- Extra Info -->
                                        <div class="form-group @error('info') has-danger @enderror">
                                            <p class="mb-2 font-weight-bold">@lang('global.extraInfo'): </p>
                                            <textarea id="info" class="form-control @error('info') form-control-danger @enderror" name="info">{{ old('info') }}</textarea>

                                            @error('info')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Extra Info -->
                                    </div>

                                    <div class="col-md-6">
                                        <!-- PT Items -->
                                        <div class="form-group">
                                            <label class=" font-weight-bold" for="">{{ __('اجناس') }}: <span class="text-danger">*</span></label>
                                            <div class="field_wrapper">
                                                <div class="mb-1">
                                                    <input type="text" name="good_name[]" value="" placeholder="نام جنس" style="width: 150px;"/>
                                                    <input type="text" name="hs_code[]" value="" placeholder="کد تعرفه" style="width: 100px;"/>
                                                    <input type="text" name="total_packages[]" value="" placeholder="مجموع بسته ها" style="width: 100px;"/>
                                                    <input type="text" name="weight[]" value="" placeholder="وزن" style="width: 100px;"/>
                                                    <a href="javascript:void(0);" class="add_button btn btn-primary btn-sm" title="Add field">@lang('global.add')</a>
                                                </div>
                                            </div>
                                        </div>

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

    <!-- Custom Scripts -->
    <script>
        var maxField = 100; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="mb-1"><input type="text" name="good_name[]" value="" placeholder="نام جنس" style="width: 150px;"/>&nbsp;<input type="text" name="hs_code[]" value="" placeholder="کد تعرفه" style="width: 100px;"/>&nbsp;<input type="text" name="total_packages[]" value="" placeholder="مجموع بسته ها" style="width: 100px;"/>&nbsp;<input type="text" name="weight[]" value="" placeholder="وزن" style="width: 100px;"/>&nbsp;<a href="javascript:void(0);" class="remove_button btn btn-danger btn-sm">@lang('global.remove')</a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

        // Once add button is clicked
        $(addButton).click(function(){
            //Check maximum number of input fields
            if(x < maxField){
                x++; //Increase field counter
                $(wrapper).append(fieldHTML); //Add field html
            }else{
                alert('A maximum of '+maxField+' fields are allowed to be added. ');
            }
        });

        // Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrease field counter
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
