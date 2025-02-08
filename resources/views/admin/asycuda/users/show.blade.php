@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'حساب کاربری اسیکودا - ' . $asycuda_user->employee->name . ' ' . $asycuda_user->employee->last_name)
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
                <h2 class="main-content-title tx-24 mg-b-5">حساب کاربری سیستم اسیکودا</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    @can('office_employee_view')
                        <li class="breadcrumb-item"><a href="{{ route('admin.office.employees.index') }}">@lang('admin.sidebar.employees')</a></li>
                    @else
                        <li class="breadcrumb-item">@lang('admin.sidebar.employees')</li>
                    @endcan
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.asycuda.users.index') }}">حسابات کاربری سیستم اسیکودا</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('global.details')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                @if($asycuda_user->status == 1)
                    <div class="d-flex">
                        @can('asy_user_delete')
                            <div class="mr-2">
                                <!-- Delete -->
                                <a class="modal-effect btn btn-sm ripple btn-danger"
                                   data-effect="effect-sign" data-toggle="modal"
                                   href="#delete_record{{ $asycuda_user->id }}">
                                    <i class="fe fe-trash"></i>
                                    @lang('global.delete')
                                </a>

                                @include('admin.asycuda.users.delete')
                            </div>
                        @endcan

                        @can('asy_user_edit')
                            <div class="mr-2">
                                <!-- Edit -->
                                <a class="btn ripple bg-dark btn-sm tx-white"
                                   href="{{ route('admin.asycuda.users.edit', $asycuda_user->id) }}">
                                    <i class="fe fe-edit"></i>
                                    @lang('global.edit')
                                </a>
                            </div>
                        @endcan

                        @can('asy_user_create')
                            <div class="mr-2">
                                <!-- Add -->
                                <a class="btn ripple bg-primary btn-sm tx-white"
                                   href="{{ route('admin.asycuda.users.create') }}">
                                    <i class="fe fe-plus-circle"></i>
                                    @lang('global.add')
                                </a>
                            </div>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Row Content -->
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <!-- Profile Main Info -->
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="main-profile-overview widget-user-image text-center">
                            <div class="main-img-user">
                                <img alt="avatar"
                                     src="{{ $asycuda_user->employee->image ?? asset('assets/images/avatar-default.jpeg') }}">
                            </div>
                        </div>

                        <!-- Main Info -->
                        <div class="item-user pro-user">
                            <h4 class="pro-user-username text-dark mt-2 mb-0">
                                <span>{{ $asycuda_user->employee->name }} {{ $asycuda_user->employee->last_name }}</span>
                            </h4>

                            <!-- Position -->
                            @can('office_position_view')
                                <a href="{{ route('admin.office.positions.show', $asycuda_user->employee->position->id) }}" target="_blank" class="pro-user-desc mb-1">{{ $asycuda_user->employee->position->title ?? '' }}</a>
                            @else
                                <p class="pro-user-desc text-muted mb-1">{{ $asycuda_user->employee->position->title ?? '' }}</p>
                            @endcan
                            @if($asycuda_user->employee->on_duty == 1)
                                <p class="pro-user-desc text-muted mb-1">{{ $asycuda_user->employee->duty_position ?? '' }}</p>
                            @endif

                            @if($asycuda_user->employee->position->position_number == 2 || $asycuda_user->employee->position->position_number == 3)
                            @else
                                <p class="pro-user-desc text-primary mb-1">({{ $asycuda_user->employee->position->place->name ?? '' }})</p>
                            @endif
                            <!-- Employee Star -->
                            @if($asycuda_user->employee->position)
                                <p class="user-info-rating">
                                    @for($i=1; $i<=$asycuda_user->employee->position->position_number; $i++)
                                        <a href="javascript:void(0);"><i class="fa fa-star text-warning"> </i></a>
                                    @endfor
                                </p>
                            @endif
                            <!--/==/ End of Employee Star -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Profile Main Info -->

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <div>
                            <h6 class="card-title mb-0">
                                اطلاعات
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
                                    <span>@lang('form.status')</span>
                                    @if($asycuda_user->status == 1)
                                        <span class="text-success">فعال</span>
                                    @else
                                        <span class="text-danger">غیرفعال</span>
                                    @endif
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
                                        <a href="callto:{{ $asycuda_user->employee->phone }}" class="ctd">{{ $asycuda_user->employee->phone }}</a>
                                        @if(!empty($employee->phone2))
                                            , <a href="callto:{{ $asycuda_user->employee->phone2 }}"
                                                 class="ctd">{{ $asycuda_user->employee->phone2 }}</a>
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
                                        <a href="mailto:{{ $asycuda_user->employee->email }}" class="ctd">{{ $asycuda_user->employee->email }}</a>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Email Address -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Contact Information -->
            </div>
            <div class="col-lg-9 col-md-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Header Card -->
                <div class="card mb-1">
                    <div class="card-header">
                        <!-- Heading -->
                        <div class="row font-weight-bold">
                            <div class="col-6">
                                {{ $asycuda_user->employee->name . ' «' . $asycuda_user->employee->last_name . '»' }}
                            </div>
                            <div class="col-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                <i class="fa fa-user-tie"></i> حساب کاربری اسیکودا
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mr-1 ml-1">
                            <div class="{{ app()->getLocale() == 'en' ? 'pr-2' : 'pl-2' }}"><i class="far fa-clock"></i></div>
                            <div>
                                تاریخ ثبت
                                <br>
                                <p class="text-muted small">{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d h:i a', strtotime($asycuda_user->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Header Card -->

                <!-- Details -->
                <div class="card mb-2">
                    <!-- Personal Information -->
                    <div class="card-header row">
                        <div class="col-md-6">
                            <div class="tx-15 tx-bold">
                                @lang('global.details')
                            </div>
                        </div>
                        <div class="col-md-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                            <a href="{{ route('admin.asycuda.users.user_form', $asycuda_user->id) }}" class="btn btn-outline-dark btn-sm" target="_blank">@lang('pages.users.userForm')</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body" style="background-color: #F7F9FCFF">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-lg col-xxl-5">
                                <h6 class="fw-semi-bold ls mb-3 text-uppercase font-weight-bold">@lang('pages.employees.personalInfo')</h6>
                                <!-- ID -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>ID:</strong></p>
                                    </div>
                                    <div class="col">ID-{{ $asycuda_user->id }}</div>
                                </div>

                                <!-- Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.name'): </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->employee->name }}</div>
                                </div>

                                <!-- Last Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.lastName'): </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->employee->last_name }}</div>
                                </div>

                                <!-- Father Name -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.fatherName'): </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->employee->father_name }}</div>
                                </div>

                                <!-- Employee Number -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.empNumber'): </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->employee->emp_number }}</div>
                                </div>

                                <!-- Phone Number -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.phone'): </strong></p>
                                    </div>
                                    <div class="col"><a href="callto:{{ $asycuda_user->employee->phone }}">{{ $asycuda_user->employee->phone }}</a></div>
                                </div>

                                <!-- Position -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.position'): </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->employee->position->title }}</div>
                                </div>

                                @if($asycuda_user->employee->on_duty == 1)
                                    <!-- Duty Position -->
                                    <div class="row">
                                        <div class="col-5 col-sm-4">
                                            <p class="fw-semi-bold mb-1"><strong>@lang('form.dutyPosition'): </strong></p>
                                        </div>
                                        <div class="col">{{ $asycuda_user->employee->duty_position }}</div>
                                    </div>
                                @endif

                                <!-- Description -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('global.extraInfo'): </strong></p>
                                    </div>
                                    <div class="col">
                                        <p class="fst-italic text-400 mb-1">{{ $asycuda_user->info ?? '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Position Information -->

                            <!-- User Information -->
                            <div class="col-lg col-xxl-5 mt-4 mt-lg-0 offset-xxl-1">
                                <h6 class="fw-semi-bold ls mb-3 text-uppercase font-weight-bold">معلومات حساب کاربری</h6>
                                <!-- Created Date -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('global.createdDate'):</strong></p>
                                    </div>
                                    <div class="col">{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($asycuda_user->created_at)) }}</div>
                                </div>

                                <!-- User -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>نمبر حساب کاربری:</strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->user }}</div>
                                </div>

                                <!-- Password -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.password'):</strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->password }}</div>
                                </div>

                                <!-- Roles -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('admin.sidebar.roles'):</strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->roles }}</div>
                                </div>

                                <!-- Status -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>@lang('form.status'):</strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->status == 1 ? trans('global.active') : trans('global.inactive') }}</div>
                                </div>

                                <!-- Place -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>موقعیت: </strong></p>
                                    </div>
                                    <div class="col">{{ $asycuda_user->place->name ?? '' }} - {{ $asycuda_user->place->custom_code ?? '' }}</div>
                                </div>
                            </div>
                            <!--/==/ End of General Information -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Details -->

                <!-- Asycuda Account Resumes -->
                <div class="card mb-2">
                    <!-- Table TITLE -->
                    <div class="card-header row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">سابقه فعالیت حساب کاربری اسیکودا</h5>
                        </div>

                        <div class="col-md-6 text-left">
                            <a href="{{ route('admin.asycuda.users.add_user_resume', $asycuda_user->id) }}" class="btn btn-outline-primary">ثبت</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Experiences Table -->
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered export-table border-top key-buttons display text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">بست</th>
                                    <th class="text-center">نوع بست</th>
                                    <th class="text-center">نمبر مکتوب</th>
                                    <th class="text-center">تاریخ مکتوب</th>
                                    <th class="text-center">وضعیت حساب کاربری</th>
                                    <th class="text-center">صلاحیت های حساب کاربری</th>
                                    <th class="text-center">مکتوب</th>
                                    <th class="text-center">تاریخ</th>
                                    <th class="text-center">@lang('global.extraInfo')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($asycuda_user->resumes as $resume)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $resume->position }}</td>
                                        <td>{{ $resume->position_type == 1 ? 'خدمتی' : 'اصل بست' }}</td>
                                        <td>{{ $resume->doc_number }}</td>
                                        <td>{{ $resume->doc_date }}</td>
                                        <td>{{ $resume->user_status == 1 ? 'فعال' : 'غیرفعال' }}</td>
                                        <td>{{ $resume->user_roles }}</td>
                                        <td>
                                            <a href="{{ $resume->image ?? asset('assets/images/id-card-default.png') }}" target="_blank">
                                                <img src="{{ $resume->image ?? asset('assets/images/id-card-default.png') }}" alt="{{ $resume->asy_user->employee->name }}" width="80">
                                            </a>
                                        </td>
                                        <td>{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($resume->created_at)) }}</td>
                                        <td>{{ $resume->info }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--/==/ End of Experiences Table -->
                    </div>
                </div>
                <!--/==/ End of Asycuda Account Resumes -->
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

    @include('admin.inc.status_scripts')
@endsection
<!--/==/ End of Extra Scripts -->
