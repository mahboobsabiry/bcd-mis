@extends('layouts.admin.master')
<!-- Title -->
@section('title', $agent->name)
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
                <h2 class="main-content-title tx-24 mg-b-5">{{ $agent->name }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.agents.index') }}">@lang('pages.companies.agents')</a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">@lang('global.details')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <div class="d-flex">
                    @can('office_agent_delete')
                        <div class="mr-2">
                            <!-- Delete -->
                            <a class="modal-effect btn btn-sm ripple btn-danger text-white"
                               data-effect="effect-sign" data-toggle="modal"
                               href="#delete_record{{ $agent->id }}"
                               title="@lang('global.delete')">
                                @lang('global.delete')
                                <i class="fe fe-trash"></i>
                            </a>

                            @include('admin.office.agents.delete')
                        </div>
                    @endcan

                    @can('office_agent_edit')
                        <div class="mr-2">
                            <!-- Edit -->
                            <a class="btn ripple bg-dark btn-sm text-white"
                               href="{{ route('admin.office.agents.edit', $agent->id) }}">
                                @lang('global.edit')
                                <i class="fe fe-edit"></i>
                            </a>
                        </div>
                    @endcan

                    @can('office_agent_create')
                        <div class="mr-2">
                            <!-- Add -->
                            <a class="btn ripple btn-primary btn-sm" href="{{ route('admin.office.agents.create') }}">
                                @lang('global.new')
                                <i class="fe fe-plus-circle"></i>
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
                                {{ $agent->name }}
                            </div>
                            <div class="col-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                <i class="fa fa-user-tie"></i> نماینده شرکت
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mr-1 ml-1">
                            <div class="{{ app()->getLocale() == 'en' ? 'pr-2' : 'pl-2' }}"><i class="far fa-clock"></i></div>
                            <div>
                                تاریخ ثبت
                                <br>
                                <p class="text-muted small">{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d h:i a', strtotime($agent->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Header Card -->

                <!-- Details Card -->
                <div class="card mb-2">
                    <div class="card-header tx-15 tx-bold">
                        @lang('global.details')
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- User Information Details -->
                        <div class="p-2">
                            <!-- Personal Information Table -->
                            <div class="table-responsive ">
                                <table class="table row table-borderless">
                                    <!-- Photo -->
                                    <tbody class="col-sm-12 col-xl-2 p-0">
                                    <tr>
                                        <td>
                                            <div class="main-profile-overview widget-user-image text-center">
                                                <div class="main-img-user">
                                                    <a href="{{ $agent->image ?? asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                                        <img alt="avatar" src="{{ $agent->image ?? asset('assets/images/avatar-default.jpeg') }}">
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <!-- Right Column -->
                                    <tbody class="col-sm-12 col-xl-4 p-0">
                                    <!-- ID -->
                                    <tr>
                                        <th class="font-weight-bold">نمبر مسلسل:</th>
                                        <td>AG-{{ $agent->id }}</td>
                                    </tr>

                                    <!-- Name -->
                                    <tr>
                                        <th class="font-weight-bold">@lang('form.name'):</th>
                                        <td>{{ $agent->name }}</td>
                                    </tr>

                                    <!-- Companies -->
                                    <tr>
                                        <th class="font-weight-bold">تعداد شرکت ها و تعداد همکاران:</th>
                                        <td>{{ $agent->companies->count() }} - {{ $agent->colleagues->count() }}</td>
                                    </tr>
                                    </tbody>

                                    <!-- Left Column -->
                                    <tbody class="col-sm-12 col-xl-4 p-0">
                                    <!-- Address -->
                                    <tr>
                                        <th class="font-weight-bold">@lang('global.address'):</th>
                                        <td>{{ $agent->address ?? '' }}</td>
                                    </tr>

                                    <!-- Phone -->
                                    <tr>
                                        <th class="font-weight-bold">@lang('form.phone'):</th>
                                        <td>{{ $agent->phone }} {{ $agent->phone2 ? ', ' : '' }} {{ $agent->phone2 }}</td>
                                    </tr>

                                    <!-- ID Card Number -->
                                    <tr>
                                        <th class="font-weight-bold">نمبر تذکره:</th>
                                        <td>{{ $agent->id_number }}</td>
                                    </tr>
                                    </tbody>

                                    <!-- Signature -->
                                    <tbody class="col-sm-12 col-xl-2 p-0">
                                    <tr>
                                        <td>
                                            <div class="main-profile-overview widget-user-image text-center">
                                                <div class="main-img-user">
                                                    @if($agent->signature)
                                                        <a href="{{ asset('storage/agents/signatures/' . $agent->signature) ?? asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                                            <img alt="signature" src="{{ asset('storage/agents/signatures/' . $agent->signature) ?? asset('assets/images/avatar-default.jpeg') }}">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Personal Information Table -->

                            <div class="main-content-label tx-13 mg-b-20 pt-2" style="border-top: 1px solid #ddd;">
                                @lang('global.extraInfo')
                            </div>
                            <p>{!! $agent->background !!}</p>
                            <p>{{ $agent->info ?? '--' }}</p>
                        </div>
                        <!--/==/ End of User Information Details -->
                    </div>
                </div>

                <!-- Companies -->
                <div class="card mb-2">
                    <div class="card-header row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">@lang('admin.sidebar.companies') ({{ $agent->companies->count() }})</h5>
                        </div>
                        <div class="col-md-6">
                            @if($agent->companies->count() != 3)
                                @can('office_agent_create')
                                    <a class="btn btn-primary btn-sm ripple float-left mr-2"
                                       href="{{ route('admin.office.agents.add_company', $agent->id) }}">
                                        @lang('global.new')
                                    </a>
                                @endcan
                            @endif
                            <!-- Refresh Company -->
                            @can('office_agent_edit')
                                <a class="modal-effect btn btn-sm ripple btn-info float-left"
                                   data-effect="effect-sign" data-toggle="modal"
                                   href="#refresh_agent{{ $agent->id }}">
                                    تازه سازی
                                </a>

                                @include('admin.office.agents.refresh_agent')
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Companies Table -->
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>@lang('form.name')</th>
                                    <th>@lang('form.tin')</th>
                                    <th>@lang('form.activity_sector')</th>
                                    <th>@lang('form.fromDate')</th>
                                    <th>@lang('form.toDate')</th>
                                    <th>@lang('pages.employees.docNumber')</th>
                                    <th>مدت اعتبار</th>
                                    <th>@lang('global.validationStatus')</th>
                                    <th>@lang('global.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($agent->companies as $company)
                                        <tr>
                                            <th scope="row">{{ $company->id }}</th>
                                            <td><a href="{{ route('admin.office.companies.show', $company->id) }}">{{ $company->name }}</a></td>
                                            <td>{{ $company->tin }}</td>
                                            <td>@foreach(explode(',', $company->activity_sector) as $t) {{ $t }} - @endforeach</td>
                                            <td>
                                                @if($company->name == $agent->company_name)
                                                    {{ $company->agent->from_date }}
                                                @elseif($company->name == $agent->company_name2)
                                                    {{ $company->agent->from_date2 }}
                                                @else
                                                    {{ $company->agent->from_date3 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->name == $agent->company_name)
                                                    {{ $company->agent->to_date }}
                                                @elseif($company->name == $agent->company_name2)
                                                    {{ $company->agent->to_date2 }}
                                                @else
                                                    {{ $company->agent->to_date3 }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->name == $agent->company_name)
                                                    {{ $company->agent->doc_number }}
                                                @elseif($company->name == $agent->company_name2)
                                                    {{ $company->agent->doc_number2 }}
                                                @else
                                                    {{ $company->agent->doc_number3 }}
                                                @endif
                                            </td>
                                            <td>
                                                <!-- First Company -->
                                                @if($company->name == $agent->company_name)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date)->toCarbon();
                                                        $valid_days = $to_date->diffInDays($from_date);
                                                        echo $valid_days;
                                                    @endphp
                                                @endif

                                                <!-- Second Company -->
                                                @if($company->name == $agent->company_name2)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date2)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date2)->toCarbon();
                                                        $valid_days = $to_date->diffInDays($from_date);
                                                        echo $valid_days;
                                                    @endphp
                                                @endif

                                                <!-- Third Company -->
                                                @if($company->name == $agent->company_name3)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date3)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date3)->toCarbon();
                                                        $valid_days = $to_date->diffInDays($from_date);
                                                        echo $valid_days;
                                                    @endphp
                                                @endif
                                            </td>
                                            <td>
                                                <!-- First Company -->
                                                @if($company->name == $agent->company_name)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date)->toCarbon();

                                                        // $diff_days = $to_date->diffInDays($from_date);
                                                        $valid_days = now()->diffInDays($to_date);
                                                        if ($to_date > today()) {
                                                            echo "<span class='text-secondary'>$valid_days روز باقیمانده</span>";
                                                        } else {
                                                            echo "<span class='text-danger'>تاریخ ختم شده</span>";
                                                        }
                                                    @endphp
                                                @endif

                                                <!-- Second Company -->
                                                @if($company->name == $agent->company_name2)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date2)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date2)->toCarbon();

                                                        // $diff_days = $to_date->diffInDays($from_date);
                                                        $valid_days = now()->diffInDays($to_date);
                                                        if ($to_date > today()) {
                                                            echo "<span class='text-secondary'>$valid_days روز باقیمانده</span>";
                                                        } else {
                                                            echo "<span class='text-danger'>تاریخ ختم شده</span>";
                                                        }
                                                    @endphp
                                                @endif

                                                <!-- Third Company -->
                                                @if($company->name == $agent->company_name3)
                                                    @php
                                                        $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->from_date3)->toCarbon();
                                                        $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $agent->to_date3)->toCarbon();

                                                        // $diff_days = $to_date->diffInDays($from_date);
                                                        $valid_days = now()->diffInDays($to_date);
                                                        if ($to_date > today()) {
                                                            echo "<span class='text-secondary'>$valid_days روز باقیمانده</span>";
                                                        } else {
                                                            echo "<span class='text-danger'>تاریخ ختم شده</span>";
                                                        }
                                                    @endphp
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.office.agents.renewal_company', $company->id) }}" class="btn btn-sm btn-outline-dark">تمدید</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--/==/ End of Companies Table -->
                    </div>
                </div>
                <!--/==/ End of Companies -->

                <!-- Agent Colleagues -->
                <div class="card mb-2">
                    <div class="card-header row mb-2">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold"> همکاران ({{ $agent->colleagues->count() }})</h5>
                        </div>
                        <div class="col-md-6">
                            @can('office_agent_create')
                                <a class="btn btn-primary btn-sm ripple float-left mr-2"
                                   href="{{ route('admin.office.agents.add_colleague', $agent->id) }}">
                                    @lang('global.new')
                                </a>
                            @endcan

                            <!-- Refresh Agent Colleague -->
                            @can('office_agent_edit')
                                <a class="modal-effect btn btn-sm ripple btn-info float-left"
                                   data-effect="effect-sign" data-toggle="modal"
                                   href="#refresh_colleague{{ $agent->id }}">
                                    تازه سازی
                                </a>

                                @include('admin.office.agents.refresh_colleague')
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Colleagues Table -->
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th>@lang('form.name')</th>
                                    <th>@lang('form.phone')</th>
                                    <th>نمبر تذکره</th>
                                    <th>@lang('form.fromDate')</th>
                                    <th>@lang('form.toDate')</th>
                                    <th>@lang('pages.employees.docNumber')</th>
                                    <th>@lang('global.address')</th>
                                    <th>مدت اعتبار</th>
                                    <th>@lang('global.validationStatus')</th>
                                    <th>@lang('global.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($agent->colleagues as $colleague)
                                        <tr>
                                            <th scope="row">{{ $colleague->id }}</th>
                                            <td>
                                                <a href="{{ route('admin.office.agent-colleagues.show', $colleague->id) }}">{{ $colleague->name }}</a>
                                            </td>
                                            <td>{{ $colleague->phone ?? '' }} {{ $colleague->phone2? ', ' : '' }} {{ $colleague->phone2 ?? '' }}</td>
                                            <td>{{ $colleague->id_number }}</td>
                                            <td>{{ $colleague->from_date }}</td>
                                            <td>{{ $colleague->to_date }}</td>
                                            <td>{{ $colleague->doc_number }}</td>
                                            <td>{{ $colleague->address }}</td>
                                            <!-- Validation Duration -->
                                            <td>
                                                @php
                                                    $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $colleague->from_date)->toCarbon();
                                                    $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $colleague->to_date)->toCarbon();
                                                    $valid_days = $to_date->diffInDays($from_date);
                                                    echo $valid_days;
                                                @endphp
                                            </td>
                                            <!-- Validation Status -->
                                            <td>
                                                @php
                                                    $from_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $colleague->from_date)->toCarbon();
                                                    $to_date = \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $colleague->to_date)->toCarbon();

                                                    $valid_days = now()->diffInDays($to_date);
                                                    if ($to_date > today()) {
                                                        echo "<span class='text-secondary'>$valid_days روز باقیمانده</span>";
                                                    } else {
                                                        echo "<span class='text-danger'>تاریخ ختم شده</span>";
                                                    }
                                                @endphp
                                            </td>
                                            <!-- Action -->
                                            <td>
                                                <a href="{{ route('admin.office.agents.renewal_colleague', $colleague->id) }}" class="btn btn-sm btn-outline-dark">تمدید</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--/==/ End of Colleagues Table -->
                    </div>
                </div>
                <!--/==/ End of Agent Colleagues -->
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
    <script src="{{ asset('assets/js/datatable.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
