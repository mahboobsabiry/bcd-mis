@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'تعرفه ترجیحی - جایداد اموال')
<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Select 2 -->
    <link href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Sumoselect css-->
    <link href="{{ asset('backend/assets/plugins/sumoselect/sumoselect.css') }}" rel="stylesheet">

    @if(app()->getLocale() == 'en')
        <link href="{{ asset('assets/css/treeview.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/css/treeview.css') }}" rel="stylesheet">
    @endif

    <style>
        table thead tr .tblBorder {
            border: 1px solid #ddd;
        }
    </style>
@endsection
<!--/==/ End of Extra Styles -->

<!-- Page Content -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">مدیریت عمومی تشریح اموال</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">تعرفه ترجیحی - جایداد اموال</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Add New -->
                @can('examination_property_create')
                    <a class="btn ripple btn-primary" href="{{ route('admin.examination.properties.create') }}">
                        <i class="fe fe-plus-circle"></i> @lang('global.new')
                    </a>
                @endcan
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Data Table -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header tx-15 tx-bold">
                        مجموع جایداد اموال ({{ $properties->count() }})
                    </div>

                    <div class="card-body">
                        <!-- All -->
                        <div class="">
                            <!-- Table -->
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>کاربر ثبت کننده</th>
                                        <th>اسم و نمبر تشخیصیه شرکت</th>
                                        <th>نمبر مکتوب</th>
                                        <th>تاریخ مکتوب</th>
                                        <th>نوع جنس</th>
                                        <th>کد اموال</th>
                                        <th>مقدار جنس (Kg)</th>
                                        <th>تاریخ شروع</th>
                                        <th>تاریخ ختم</th>
                                        <th>مدت اعتبار</th>
                                        <th>مدت باقیمانده</th>
                                        <th>اسکن مکتوب</th>
                                        <th>معلومات اضافی</th>
                                        <th>تاریخ ثبت</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($properties as $property)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $property->user->name }}</td>
                                            <td>{{ $property->company->name . ' - ' . $property->company->tin }}</td>
                                            <td>{{ $property->doc_number }}</td>
                                            <td>{{ $property->doc_date }}</td>
                                            <td>
                                                <a href="{{ route('admin.examination.properties.show', $property->id ) }}">{{ $property->property_name }}</a>
                                            </td>
                                            <td>{{ $property->property_code . ' TSC-'. $property->ts_code }}</td>
                                            <td>{{ $property->weight }}<sup>{{ app()->getLocale() == 'en' ? 'kg' : 'کیلوگرام' }}</sup></td>
                                            <td>{{ $property->start_date }}</td>
                                            <td>{{ $property->end_date }}</td>
                                            <!-- Valid Days -->
                                            <td>
                                                @php
                                                    $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $property->start_date)->toCarbon();
                                                    $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $property->end_date)->toCarbon();
                                                    $valid_days = $start_date->diffInDays($end_date);
                                                    echo $valid_days;
                                                @endphp
                                            </td>
                                            <!-- Remaining Days -->
                                            <td>
                                                @php
                                                    $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $property->end_date)->toCarbon();
                                                    $remaining_days = now()->diffInDays($end_date);
                                                @endphp
                                                @if($remaining_days > 10)
                                                    {{ $remaining_days }} روز
                                                @else
                                                    <span class="text-danger">{{ $remaining_days }} روز</span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <span class="fas fa-dollar-sign fa-pulse text-danger"></span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ $property->image }}" target="_blank">
                                                    <img src="{{ $property->image }}" alt="" width="70">
                                                </a>
                                            </td>

                                            <td>{{ $property->info }}</td>
                                            <td>{{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime($property->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Table -->
                        </div>
                        <!--/==/ End of All Record -->
                    </div>
                </div>
                <!--/==/ End of Table Card -->
            </div>
        </div>
        <!--/==/ End of Data Table -->
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.colVis.min.js') }}"></script>

    <!-- Custom Scripts -->
    {{--    <script src="{{ asset('backend/assets/js/datatable.js') }}"></script>--}}
    <script src="{{ asset('assets/js/datatable.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
