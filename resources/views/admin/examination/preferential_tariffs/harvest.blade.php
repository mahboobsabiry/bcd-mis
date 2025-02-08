@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'برداشت از جایداد مکتوب نمبر ' . $tariff->doc_number)
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.examination.preferential_tariffs.show', $tariff->id) }}">جایداد مکتوب نمبر {{ $tariff->doc_number }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">برداشت</li>
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
                        <h6 class="card-title tx-15 tx-bold mb-1">برداشت جایداد</h6>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.examination.preferential_tariffs.harvest', $tariff->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Document Number -->
                                        <div class="form-group">
                                            <p class="mb-2 font-weight-bold"> مکتوب نمبر:</p>
                                            <select class="form-control select2" disabled>
                                                <option value="{{ $tariff->doc_number }}" selected>{{ $tariff->doc_number }}</option>
                                            </select>
                                        </div>
                                        <!--/==/ End of Document Number -->

                                        <!-- Code -->
                                        <div class="form-group">
                                            <p class="mb-2 font-weight-bold">کد: <span class="tx-danger">*</span></p>
                                            <input type="text" class="form-control" value="{{ \App\Models\Examination\Harvest::code() }}" disabled>
                                        </div>
                                        <!--/==/ End of Start Date -->

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
                                        <!-- Harvest Items -->
                                        <div class="form-group" id="items_list_fg">
                                            <label class=" font-weight-bold" for="items_list_sf">{{ __('اجناس') }}: <span class="text-danger">*</span></label>

                                            <select class="form-control select2" name="item_good_name" id="items_list_sf">
                                                <option value="">@lang('form.chooseOne')</option>
                                                @foreach($tariff->pt_items as $item)
                                                    <option value="{{ $item->good_name }}">{{ $item->good_name }} ({{ $item->hs_code }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Items List -->
                                        <div class="form-group" id="items_list">
                                            <span id="pgn"><input type="text" name="good_name[]" id="good_name" value="" placeholder="نام جنس" style="width: 150px;"/></span>
                                            <span id="phc"><input type="text" name="hs_code[]" id="hs_code" value="" placeholder="کد تعرفه" style="width: 100px;"/></span>
                                            <span id="ptp"><input type="text" name="total_packages[]" id="total_packages" value="" placeholder="مجموع بسته ها" style="width: 100px;"/></span>
                                            <span id="pw"><input type="text" name="weight[]" id="weight" value="" placeholder="وزن" style="width: 100px;"/></span>
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

    <script>
        // Select Goods
        $(document).ready(function() {
            $(document).on('change', '#items_list_sf', function () {
                var item_good_name = $(this).val();
                var a = $("#good_name").parent();
                var b = $("#hs_code").parent();
                var c = $("#total_packages").parent();
                var d = $("#weight").parent();

                if (!item_good_name == '') {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'get',
                        url: '{{ route('admin.examination.preferential_tariffs.select_item') }}',
                        data: { 'item_good_name': item_good_name },
                        dataType: 'json',
                        success: function (data) {
                            a.find('#good_name').attr("value", data.pt_good_name);
                            b.find('#hs_code').attr("value", data.pt_hs_code);
                            c.find('#total_packages').attr("value", data.pt_total_packages);
                            d.find('#weight').attr("value", data.pt_weight);
                        },
                        error: function () {
                            alert("ERROR");
                            $(".errorMsg").html(data.error);
                        }
                    });
                } else {
                    a.find('#good_name').attr("value", "");
                    b.find('#hs_code').attr("value", "");
                    c.find('#total_packages').attr("value", "");
                    d.find('#weight').attr("value", "");
                }
            });
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
