@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'تعرفه ترجیحی - مکتوب نمبر ' . $tariff->doc_number)
<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
<!--/==/ End of Extra Styles -->

<!-- Page Content -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('global.details')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.examination.preferential_tariffs.index') }}">تعرفه ترجیحی - جایدات اموال</a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">@lang('global.details')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <div class="d-flex">
                    @can('examination_pt_delete')
                        <div class="mr-2">
                            <!-- Delete -->
                            <a class="modal-effect btn btn-sm ripple btn-danger text-white"
                               data-effect="effect-sign" data-toggle="modal"
                               href="#delete_record{{ $tariff->id }}"
                               title="@lang('global.delete')">
                                @lang('global.delete')
                                <i class="fe fe-trash"></i>
                            </a>

                            @include('admin.examination.preferential_tariffs.delete')
                        </div>
                    @endcan

                    @can('examination_pt_edit')
                        <div class="mr-2">
                            <!-- Edit -->
                            <a class="btn ripple bg-dark btn-sm text-white"
                               href="{{ route('admin.examination.preferential_tariffs.edit', $tariff->id) }}">
                                @lang('global.edit')
                                <i class="fe fe-edit"></i>
                            </a>
                        </div>
                    @endcan

                    @can('examination_pt_create')
                        <div class="mr-2">
                            <!-- Add -->
                            <a class="btn ripple btn-primary btn-sm"
                               href="{{ route('admin.examination.preferential_tariffs.create') }}">
                                @lang('global.new')
                                <i class="fe fe-plus-circle"></i>
                            </a>
                        </div>
                    @endcan

                    @can('examination_pt_renewal')
                        <div class="mr-2">
                            <!-- Renewal -->
                            <a class="btn ripple btn-info btn-sm"
                               href="{{ route('admin.examination.preferential_tariffs.renewal', $tariff->id) }}">
                                @lang('global.renewal')
                                <i class="fe fe-refresh-cw"></i>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Row Content -->
        <div class="row">
            <div class="col-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Header Card -->
                <div class="card mb-1">
                    <div class="card-header">
                        <!-- Heading -->
                        <div class="row font-weight-bold">
                            <div class="col-6">
                                {{ __('مکتوب نمبر') }} {{ $tariff->doc_number }}
                            </div>
                            <div class="col-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                <i class="fa fa-bullseye"></i> جایداد اموال - تعرفه ترجیحی
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mr-1 ml-1">
                            <div class="{{ app()->getLocale() == 'en' ? 'pr-2' : 'pl-2' }}"><i class="far fa-clock"></i>
                            </div>
                            <div>
                                تاریخ ثبت
                                <br>
                                <p class="text-muted small">{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d h:i a', strtotime($tariff->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Header Card -->

                <!-- Details Card -->
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="tx-bold">@lang('global.details')</h5>
                            </div>
                            <div class="col-md-6 text-left">
                                @if($tariff->status == 0)
                                    <span class="badge badge-danger">{{ __('برداشت ناشده') }}</span>
                                @elseif($tariff->status == 1)
                                    <span class="badge badge-warning">{{ __('در حال برداشت') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('برداشت شده') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="background-color: #F7F9FCFF">
                        <div class="row">
                            <!-- General Information -->
                            <div class="col-lg col-xxl-5">
                                <h6 class="font-weight-bold ls mb-3 text-uppercase font-weight-bold">معلومات عمومی</h6>
                                <!-- ID -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">ID:</p>
                                    </div>
                                    <div class="col">ID-{{ $tariff->id }}</div>
                                </div>

                                <!-- Company Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">اسم شرکت:</p>
                                    </div>
                                    <div class="col">{{ $tariff->company->name ?? '' }}</div>
                                </div>

                                <!-- Company TIN -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">نمبر تشخیصیه شرکت:</p>
                                    </div>
                                    <div class="col">{{ $tariff->company->tin ?? '' }}</div>
                                </div>

                                <!-- Company Agent Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">نماینده شرکت:</p>
                                    </div>
                                    <div class="col">{{ $tariff->company->agent->name ?? '' }}</div>
                                </div>

                                <!-- Company Agent Number -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">شماره تماس نماینده شرکت:</p>
                                    </div>
                                    <div class="col">{{ $tariff->company->agent->phone ?? '' }}</div>
                                </div>

                                <!-- Company Agent Address -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">آدرس نماینده شرکت:</p>
                                    </div>
                                    <div class="col">{{ $tariff->company->agent->address ?? '' }}</div>
                                </div>

                                <!-- Document Number -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">نمبر مکتوب:</p>
                                    </div>
                                    <div class="col">{{ $tariff->doc_number }}</div>
                                </div>

                                <!-- Document Date -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">تاریخ مکتوب:</p>
                                    </div>
                                    <div class="col">{{ $tariff->doc_date }}</div>
                                </div>

                                <!-- Description -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">معلومات اضافی:</p>
                                    </div>
                                    <div class="col">
                                        <p class="fst-italic text-400 mb-1">{{ $tariff->info ?? '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of General Information -->

                            <!-- Assurance Information -->
                            <div class="col-lg col-xxl-5 mt-4 mt-lg-0 offset-xxl-1">
                                <h6 class="font-weight-bold ls mb-3 text-uppercase font-weight-bold">معلومات جایداد</h6>

                                <!-- PreferentialTariff Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">تعداد اقلام:</p>
                                    </div>
                                    <div class="col">{{ $tariff->pt_items->count() }}</div>
                                </div>

                                <!-- Total Weight -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">مقدار مجموعی جنس:</p>
                                    </div>
                                    <div class="col">{{ $tariff->pt_items->sum('weight') }}
                                        <sup>{{ app()->getLocale() == 'en' ? 'kg' : 'کیلوگرام' }}</sup></div>
                                </div>

                                <!-- Harvesting Weight -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">مقدار جنس برداشت شده:</p>
                                    </div>
                                    <div class="col">{{ $tariff->pt_items->sum('weight') }}
                                        <sup>{{ app()->getLocale() == 'en' ? 'kg' : 'کیلوگرام' }}</sup></div>
                                </div>

                                <!-- Harvested Weight -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">مقدار جنس موجوده:</p>
                                    </div>
                                    <div class="col">{{ $tariff->pt_items->sum('weight') }}
                                        <sup>{{ app()->getLocale() == 'en' ? 'kg' : 'کیلوگرام' }}</sup></div>
                                </div>

                                <!-- Start Date -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">تاریخ شروع:</p>
                                    </div>
                                    <div class="col">{{ $tariff->start_date }}</div>
                                </div>

                                <!-- End Date -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">تاریخ ختم:</p>
                                    </div>
                                    <div class="col">{{ $tariff->end_date }}</div>
                                </div>

                                <!-- Number of Valid Days -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">مدت اعتبار:</p>
                                    </div>
                                    <div class="col">
                                        @php
                                            $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->start_date)->toCarbon();
                                            $end_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $tariff->end_date)->toCarbon();
                                            $valid_days = $start_date->diffInDays($end_date);
                                            echo $valid_days . ' روز';
                                        @endphp
                                    </div>
                                </div>

                                <!-- Number of Remaining Days -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="font-weight-bold mb-1">مقدار روز باقیمانده:</p>
                                    </div>
                                    <div class="col">
                                        @php
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
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Assurance Information -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Details Card -->

                <!-- PT Items Table Card -->
                <div class="card mb-2">
                    <div class="card-header tx-15 tx-bold">
                        مجموع اقلام ({{ $tariff->pt_items->count() }})
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
                                        <th>نام جنس</th>
                                        <th>کد تعرفه (HS Code)</th>
                                        <th>مقدار مجموعی بسته</th>
                                        <th>وزن</th>
                                        <th>برداشت</th>
                                        <th>@lang('form.status')</th>
                                        <th>تاریخ ثبت</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($tariff->pt_items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->good_name }}</td>
                                            <td>{{ $item->hs_code }}</td>
                                            <td>{{ $item->total_packages }}</td>
                                            <td>{{ $item->weight }}</td>
                                            <td>0 ({{ $item->weight }}<sup>Kg</sup> باقیمانده)</td>
                                            <td>
                                                @if($item->status == 0)
                                                    <span class="badge badge-danger">{{ __('برداشت ناشده') }}</span>
                                                @elseif($item->status == 1)
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
                <!--/==/ End of PT Items Table Card -->
            </div>
        </div>
        <!--/==/ End of Row Content -->
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
    <script src="{{ asset('backend/assets/js/datatable.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
