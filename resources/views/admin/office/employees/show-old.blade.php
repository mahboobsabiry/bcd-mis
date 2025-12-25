@extends('layouts.admin.master')
<!-- Title -->
@section('title', $employee->name . ' ' . $employee->last_name)
<!-- Extra Styles -->
@section('extra_css')
    @if(app()->getLocale() == 'en')
        <link href="{{ asset('assets/css/treeview.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('assets/css/treeview.css') }}" rel="stylesheet">
    @endif
    <style>
        .emp-profile-img {
            width: 190px;
            height: 190px;
            position: absolute;
            z-index: 1;
            margin-top: 80px;
            margin-right: 80px;
            border-radius: 70%;
            padding-left: 8px;
            padding-bottom: 0;
            padding-right: 8px;
        }
        .id-card-img {
            width: 360px; height: 590px;
            -webkit-border-radius: 10px;
            -webkit-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -moz-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -ms-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -o-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
        }
        .id-card-back-img {
            -webkit-border-radius: 10px;
            -webkit-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -moz-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -ms-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            -o-filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
            filter: drop-shadow(0px 16px 10px rgba(0,0,225,0.6));
        }
        .emp-name {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 1;
            margin-top: 386px;
            font-size: xx-large;
            color: blue;
            font-weight: bold;
        }
        .emp-pos-title {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 1;
            margin-top: 437px;
            font-size: large;
            color: black;
            font-weight: bolder;
        }
        .emp-id {
            position: absolute;
            z-index: 1;
            margin-top: 468px;
            margin-right: 108px;
            font-family: "Times New Roman";
            font-size: 23px;
            color: black;
            font-weight: 500;
        }
        .emp-phone {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 1;
            margin-top: 494px;
            font-family: "Times New Roman";
            font-size: 23px;
            color: black;
            font-weight: bolder;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.employees.employeeInfo')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.employees.index') }}">@lang('admin.sidebar.employees')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.employees.employeeInfo')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <div class="d-flex">
                    @if($employee->status == 0 || $employee->status == 4 || $employee->status == 5)
                        @can('office_employee_delete')
                            <div class="mr-2">
                                <!-- Delete -->
                                <a class="modal-effect btn btn-sm ripple btn-danger"
                                   data-effect="effect-sign" data-toggle="modal"
                                   href="#delete_record{{ $employee->id }}"
                                   title="@lang('pages.employees.deleteEmployee')">
                                    <i class="fe fe-trash"></i>
                                    @lang('global.delete')
                                </a>

                                @include('admin.office.employees.delete')
                            </div>
                        @endcan

                        @can('office_employee_edit')
                            <div class="mr-2">
                                <!-- Edit -->
                                <a class="btn ripple bg-dark btn-sm tx-white"
                                   href="{{ route('admin.office.employees.edit', $employee->id) }}">
                                    <i class="fe fe-edit"></i>
                                    @lang('global.edit')
                                </a>
                            </div>
                        @endcan
                    @endif

                    @can('office_employee_create')
                        <div class="mr-2">
                            <!-- Add -->
                            <a class="btn ripple bg-primary btn-sm tx-white"
                               href="{{ route('admin.office.employees.create') }}">
                                <i class="fe fe-plus-circle"></i>
                                @lang('global.add')
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Row Content -->
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <!-- Profile Main Info -->
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="main-profile-overview widget-user-image text-center">
                            <div class="main-img-user">
                                <img alt="avatar"
                                     src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}">
                            </div>
                        </div>

                        <!-- Main Info -->
                        <div class="item-user pro-user">
                            <h4 class="pro-user-username text-dark mt-2 mb-0">
                                <span>{{ $employee->name }} {{ $employee->last_name }}</span>
                            </h4>

                            @if($employee->status == 0)
                                <!-- Position -->
                                @can('office_position_view')
                                    <a href="{{ route('admin.office.positions.show', $employee->position->id) }}" target="_blank" class="pro-user-desc mb-1">{{ $employee->position->title }} ({{ $employee->position->place->name ?? '' }})</a>
                                @else
                                    <p class="pro-user-desc text-muted mb-1">{{ $employee->position->title ?? '' }}</p>
                                @endcan
                                @if($employee->on_duty == 1)
                                    <p class="pro-user-desc text-muted mb-1">{{ $employee->duty_position ?? '' }}</p>
                                @endif

                                <!-- Employee Star -->
                                <p class="user-info-rating">
                                    @for($i=1; $i<=$employee->position->position_number; $i++)
                                        <a href="javascript:void(0);"><i class="fa fa-star text-warning"> </i></a>
                                    @endfor
                                    @if($employee->notices->count() >= 1)
                                        <a href="javascript:void(0);"><i class="fa fa-exclamation-triangle text-danger"> </i></a>
                                    @endif
                                </p>
                                <!--/==/ End of Employee Star -->
                            @else
                                <span class="text-danger">
                                    @if($employee->status == 1)
                                        تقاعد نموده است
                                    @elseif($employee->status == 2)
                                        منفک گردیده است
                                    @elseif($employee->status == 3)
                                        تبدیل گردیده است
                                    @elseif($employee->status == 4)
                                        معلق
                                    @elseif($employee->status == 5)
                                        از اداره/ارگان دیگر طور خدمتی آمده است.
                                        <br>
                                        @if($employee->on_duty == 1)
                                            <p class="pro-user-desc text-muted mb-1">{{ $employee->duty_position ?? '' }}</p>
                                        @endif
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/==/ End of Profile Main Info -->

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <div>
                            <h6 class="card-title tx-15 tx-bold mb-0">
                                اطلاعات لازم
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="main-profile-contact-list main-profile-work-list">
                            <!-- Status -->
                            <div class="media">
                                <div class="media-logo bg-light text-dark">
                                    <i class="fe fe-message-square"></i>
                                </div>
                                <div class="media-body">
                                    <span>وضعیت یوزر</span>
                                    <div>
                                        @if($employee->user)
                                            @can('user_mgmt')
                                                <a href="{{ route('admin.users.show', encrypt($employee->user->id)) }}" target="_blank">حساب کاربری BCD-MIS دارد ({{ $employee->user->status == '1' ? 'فعال' : 'غیرفعال' }})</a>
                                            @else
                                                حساب کاربری BCD-MIS دارد
                                            @endcan
                                        @else
                                            حساب کاربری BCD-MIS ندارد
                                        @endif
                                        |
                                        @if($employee->asycuda_user)
                                            @can('asycuda_view')
                                                <a href="{{ route('admin.asycuda.users.show', $employee->asycuda_user->id) }}" target="_blank">حساب کاربری Asycuda دارد ({{ $employee->asycuda_user->status == '1' ? 'فعال' : 'غیرفعال' }})</a>
                                            @else
                                                حساب کاربری Asycuda دارد
                                            @endcan
                                        @else
                                            حساب کاربری Asycuda ندارد
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Status -->

                            <!-- Phone Number -->
                            <div class="media">
                                <div class="media-logo bg-light text-dark">
                                    <i class="fe fe-smartphone"></i>
                                </div>
                                <div class="media-body">
                                    <span>@lang('form.phone')</span>
                                    <div>
                                        <a href="callto:{{ $employee->phone }}" class="ctd">{{ $employee->phone }}</a>
                                        @if(!empty($employee->phone2))
                                            , <a href="callto:{{ $employee->phone2 }}"
                                                 class="ctd">{{ $employee->phone2 }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Phone Number -->

                            <!-- Email Address -->
                            <div class="media">
                                <div class="media-logo bg-light text-dark">
                                    <i class="fe fe-mail"></i>
                                </div>
                                <div class="media-body">
                                    <span>@lang('form.email')</span>
                                    <div>
                                        <a href="mailto:{{ $employee->email }}" class="ctd">{{ $employee->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Email Address -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Contact Information -->

                <!-- Custom ID Card -->
                <div class="card custom-card" style="display: flex; align-items: center;">
                    <div class="overflow-auto justify-content-center p-2">
                        <!-- Action Buttons -->
                        <div class="tx-15 tx-bold">دکمه های کاربردی</div>
                        <hr>
                        <div class="row m-2">
                            <a href="{{ route('admin.office.employees.resumes', $employee->id) }}" class="btn btn-outline-success m-1">سابقه کاری</a>

                            <a href="{{ route('admin.office.employees.leaves.index', $employee->id) }}" class="btn btn-outline-secondary m-1">رخصتی ها</a>
                        </div>
                        <!--/==/ End of Action Buttons -->

                        <!-- Custom Card -->
                        @if($employee->position)
                            <div style="width: 350px;">
                                <div class="print-id-card" id="printIdCard">
                                    <!-- Employee Profile Picture -->
                                    <div class="emp-profile">
                                        <img class="emp-profile-img pos-absolute" src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}" alt="{{ $employee->name }}">
                                    </div>

                                    <!-- Employee Name & Last Name -->
                                    <div class="emp-name">{{ $employee->name }} {{ $employee->last_name }}</div>
                                    <!-- Employee Position -->
                                    <div class="emp-pos-title">{{ $employee->position->title }}</div>
                                    <!-- Employee ID -->
                                    <div class="emp-id">
                                        @if($employee->id <= 9)
                                            00{{ $employee->id }}
                                        @elseif($employee->id <= 99)
                                            0{{ $employee->id }}
                                        @else
                                            {{ $employee->id }}
                                        @endif
                                    </div>
                                    <!-- Employee Phone Number -->
                                    <div class="emp-phone">{{ $employee->phone }}</div>

                                    <!-- ID Card -->
                                    <img class="id-card-img" src="{{ asset('assets/images/emp-id-card.jpg') }}" alt="">
                                </div>
                                <hr>
                                <!-- ID Card Back -->
                                <div id="printIdCardBack">
                                    <img class="id-card-back-img" src="{{ asset('assets/images/emp-id-card-back.jpg') }}" alt="">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!--/==/ End of Contact Custom ID Card -->
            </div>
            <div class="col-lg-8 col-md-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Header Card -->
                <div class="card mb-1">
                    <div class="card-header">
                        <!-- Heading -->
                        <div class="row font-weight-bold">
                            <div class="col-6">
                                {{ $employee->name . ' «' . $employee->last_name . '»' }}
                            </div>
                            <div class="col-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                <i class="fa fa-user-tie"></i> کارمند
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mr-1 ml-1">
                            <div class="{{ app()->getLocale() == 'en' ? 'pr-2' : 'pl-2' }}"><i class="far fa-clock"></i></div>
                            <div>
                                تاریخ ثبت
                                <br>
                                <p class="text-muted small">{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d h:i a', strtotime($employee->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Header Card -->

                <!-- Score/Notice  Card -->
                <div class="card mb-2">
                    <!-- Personal Information -->
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">امتیاز/اخطاریه</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="background-color: #F7F9FCFF">
                        <!-- Score/Notice -->
                        <div class="col-xxl">
                            <!-- Score -->
                            <div class="row">
                                <div class="col-3 col-sm-2">
                                    <p class="fw-semi-bold mb-1"><strong><i class="fa fa-coins text-success"></i> امتیازات:</strong></p>
                                </div>
                                <div class="col">
                                    <div class="progress bd bd-success mb-1">
                                        <div aria-valuemax="100" aria-valuemin="0"
                                             aria-valuenow="78"
                                             class="progress-bar progress-bar-lg wd-78p bg-success"
                                             role="progressbar" style="width: 78%;">78%</div>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Score -->

                            <!-- Notice -->
                            <div class="row">
                                <div class="col-3 col-sm-2">
                                    <p class="fw-semi-bold mb-1"><strong><i class="fa fa-exclamation-triangle text-danger"></i> اخطاریه:</strong></p>
                                </div>
                                <div class="col">
                                    @php
                                        if (!$employee->notices || $employee->notices->count() == 0) {
                                            $count_number = 1;
                                        } elseif ($employee->notices->count() == 1) {
                                            $count_number = 25;
                                        } elseif ($employee->notices->count() == 2) {
                                            $count_number = 50;
                                        } elseif ($employee->notices->count() == 3) {
                                            $count_number = 75;
                                        } elseif ($employee->notices->count() == 4) {
                                            $count_number = 100;
                                        } else {
                                            $count_number = 1;
                                        }
                                    @endphp
                                    <div class="progress bd bd-danger mb-1">
                                        <div aria-valuemax="100" aria-valuemin="0"
                                             aria-valuenow="{{ $count_number }}"
                                             class="progress-bar progress-bar-lg wd-{{ $count_number }}p bg-danger"
                                             role="progressbar" style="width: {{ $count_number }}%;">{{ count($employee->notices) }}</div>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Notice -->
                        </div>
                        <!--/==/ End of Score/Notice -->

                        <!-- Buttons -->
                        @if($employee->status == 0)
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    @can('office_employee_add_score')
                                        <a class="modal-effect btn btn-sm ripple btn-outline-success"
                                           data-effect="effect-sign" data-toggle="modal"
                                           href="#score_modal">
                                            ثبت امتیاز
                                        </a>
                                        @include('admin.office.employees.inc.score')
                                    @endcan
                                </div>
                                <div class="col-md-6 text-left">
                                    @can('office_employee_add_notice')
                                        <a class="modal-effect btn btn-sm ripple btn-outline-danger"
                                           data-effect="effect-sign" data-toggle="modal"
                                           href="#notice_modal">
                                            ثبت هشدار
                                        </a>
                                        @include('admin.office.employees.inc.notice')
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!--/==/ End of Score/Notice Card -->

                <!-- Notice Card -->
                @if($employee->notices->count() >= 1)
                    <div class="card bg-danger-transparent mb-2">
                        <div class="card-header">
                            <p class="tx-14 tx-bold">سطح دریافت هشدار <i class="far fa-bell text-danger"></i></p>
                        </div>

                        <div class="card-body">
                            <!-- Notice -->
                            <div class="row p-2">
                                <!-- Table -->
                                <table class="table table-responsive table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>علت</th>
                                        <th>سطح هشدار</th>
                                        <th>مدرک</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($employee->notices as $notice)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $notice->reason }}</td>
                                            <td>{{ $notice->notice . ' - ' . $notice->notice_text }}</td>
                                            <td>
                                                @if($notice->image)
                                                    <a href="{{ $notice->image }}" target="_blank">
                                                        <img src="{{ $notice->image }}" alt="{{ $notice->notice_text }}" width="70">
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime($notice->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Notice -->
                        </div>
                    </div>
                @endif
                <!--/==/ End of Notice Card -->

                <!-- Details Card -->
                <div class="card mb-2">
                    <!-- Personal Information -->
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">@lang('global.details')</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="background-color: #F7F9FCFF">
                        <!-- Personal Information Table -->
                        @include('admin.office.employees.inc.tables')
                        <!--/==/ End of Personal Information -->
                    </div>
                </div>
                <!--/==/ End of Details Card -->

                <!-- Files Card -->
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="font-weight-bold tx-16 m-2">اسناد و مدارک</div>
                            </div>

                            <div class="col-md-6">
                                @if($employee->position)
                                    <div class="float-left ml-5">
                                        <!-- New -->
                                        <a class="pos-absolute modal-effect btn btn-sm btn-outline-primary font-weight-bold"
                                           data-effect="effect-sign" data-toggle="modal"
                                           href="#new_file{{ $employee->id }}">
                                            ثبت
                                        </a>

                                        @include('admin.office.employees.inc.new_file')
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="bd m-1 p-1">
                                <a href="{{ asset('storage/signatures/' . $employee->signature) }}"
                                   target="_blank">
                                    <img
                                        src="{{ asset('storage/signatures/' . $employee->signature) }}" alt="{{ $employee->name }}" width="150">
                                </a>
                            </div>

                            @foreach($employee->files as $file)
                                <div class="bd m-1 p-1">
                                    @if($employee->status == 0)
                                        <!-- Delete -->
                                        <a class="pos-absolute modal-effect btn btn-sm btn-danger"
                                           data-effect="effect-sign" data-toggle="modal"
                                           href="#delete_file{{ $file->id }}">
                                            <i class="fe fe-trash"></i>
                                        </a>
                                    @endif

                                    @include('admin.office.employees.inc.delete_file')

                                    <a href="{{ asset('storage/employees/files/' . $file->path) ?? asset('assets/images/id-card-default.png') }}"
                                       target="_blank">
                                        <img
                                            src="{{ asset('storage/employees/files/' . $file->path) ?? asset('assets/images/id-card-default.png') }}" alt="اسناد" width="150">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--/==/ End of Files Card -->
            </div>
        </div>
        <!--/==/ End of Row Content -->
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')
    <script src="{{ asset('backend/assets/js/pages/user-scripts.js') }}"></script>
    <script>
        function printDiv()
        {

            var divToPrint=document.getElementById('printIdCardBack');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }
    </script>

    @include('admin.inc.status_scripts')
@endsection
<!--/==/ End of Extra Scripts -->
