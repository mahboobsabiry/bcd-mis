@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'تعرفه ترجیحی - جایداد در حال برداشت')
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
                    <li class="breadcrumb-item active" aria-current="page">تعرفه ترجیحی - جایداد در حال برداشت</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Add New -->
                @can('examination_pt_create')
                    <a class="btn ripple btn-primary" href="{{ route('admin.examination.preferential_tariffs.create') }}">
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
                        مجموع جایداد در حال برداشت ({{ $tariffs->count() }})
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
                                        <th>تعداد اقلام</th>
                                        <th>مقدار مجموعی جنس (Kg)</th>
                                        <th>مقدار برداشت</th>
                                        <th>مدت اعتبار</th>
                                        <th>@lang('form.status')</th>
                                        <th>تاریخ ثبت</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($tariffs as $tariff)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $tariff->user->name }}</td>
                                            <td>{{ $tariff->company->name . ' - ' . $tariff->company->tin }}</td>
                                            <td>
                                                <a href="{{ route('admin.examination.preferential_tariffs.show', $tariff->id ) }}">{{ $tariff->doc_number }}</a>
                                            </td>
                                            <td>{{ $tariff->doc_date }}</td>
                                            <td>{{ $tariff->pt_items->count() }}</td>
                                            <td>{{ $tariff->pt_items->sum('weight') }}<sup>{{ app()->getLocale() == 'en' ? 'Kg' : 'کیلوگرام' }}</sup></td>
                                            <!-- Valid Days -->
                                            <td>
                                                @php
                                                    $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->start_date)->toCarbon();
                                                    $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->end_date)->toCarbon();
                                                    $valid_days = $start_date->diffInDays($end_date);
                                                    echo $valid_days . ' روز';
                                                @endphp
                                                &nbsp;
                                                (@php
                                                    $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->end_date)->toCarbon();
                                                    $remaining_days = now()->diffInDays($end_date);
                                                @endphp
                                                @if($remaining_days > 10)
                                                    {{ $remaining_days }} روز باقیمانده
                                                @else
                                                    <span class="text-danger">{{ $remaining_days }} روز باقیمانده</span>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <span class="fas fa-dollar-sign fa-pulse text-danger"></span>
                                                @endif)
                                            </td>
                                            <!-- Status -->
                                            <td>...</td>
                                            <td>
                                                @if($tariff->status == 0)
                                                    <span class="badge badge-danger">{{ __('برداشت ناشده') }}</span>
                                                @elseif($tariff->status == 1)
                                                    <span class="badge badge-warning">{{ __('در حال برداشت') }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ __('برداشت شده') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime($tariff->created_at)) }}</td>
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
