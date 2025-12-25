@extends('layouts.admin.master')
<!-- Title -->
@section('title', $employee->name . ' ' . $employee->last_name)
<!-- Extra Styles -->
@section('extra_css')
    <link href="{{ asset('assets/css/treeview.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        /* ID Card Styles (Preserved as requested) */
        .emp-profile-img {
            width: 190px;
            height: 190px;
            position: absolute;
            z-index: 1;
            justify-self: anchor-center;
            margin-top: 80px;
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

        /* New Styles */
        .employee-status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }

        .info-card {
            border-left: 4px solid var(--primary-color);
            transition: transform 0.2s;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .progress-percentage {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .quick-action-btn {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .quick-action-btn:hover {
            background-color: #f8f9fa;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .file-thumbnail {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .file-thumbnail:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .file-thumbnail img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .file-delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .file-thumbnail:hover .file-delete-btn {
            opacity: 1;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .tab-content {
            padding: 1.5rem;
            background: #fff;
            border-radius: 0 0 10px 10px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
    </style>
@endsection
<!--/==/ End of Extra Styles -->

<!-- Page Content -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">
                    <i class="fas fa-user-tie mr-2"></i>&nbsp;@lang('pages.employees.employeeInfo')
                </h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.employees.index') }}">@lang('admin.sidebar.employees')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $employee->name }} {{ $employee->last_name }}</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="btn btn-list">
                @can('office_employee_create')
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.office.employees.create') }}">
                        <i class="fe fe-plus-circle"></i> @lang('global.add')
                    </a>
                @endcan

                @if($employee->status == 0 || $employee->status == 4 || $employee->status == 5)
                    @can('office_employee_edit')
                        <a class="btn btn-dark btn-sm ml-2" href="{{ route('admin.office.employees.edit', $employee->id) }}">
                            <i class="fe fe-edit"></i> @lang('global.edit')
                        </a>
                    @endcan

                    @can('office_employee_delete')
                        <button class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#delete_record{{ $employee->id }}">
                            <i class="fe fe-trash"></i> @lang('global.delete')
                        </button>
                    @endcan
                @endif

                <!-- Print ID Card -->
                @if($employee->position)
                    <button class="btn btn-info btn-sm ml-2" onclick="printDiv()">
                        <i class="fas fa-print"></i> پرینت کارت
                    </button>
                @endif
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Delete Modal -->
        @include('admin.office.employees.delete')

        <!-- Success Message -->
        @include('admin.inc.alerts')

        <div class="row mb-2">
            <!-- Right Column -->
            <div class="col-lg-8 col-md-12">
                <!-- Performance Stats -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-chart-line mr-2"></i> امتیازات
                                </h6>
                                <div class="position-relative">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success"
                                             role="progressbar"
                                             style="width: 78%;"
                                             aria-valuenow="78"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            78%
                                        </div>
                                    </div>
                                    <div class="progress-percentage">78%</div>
                                </div>
                                <small class="text-muted">امتیاز کل کارمند</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-exclamation-circle mr-2"></i> اخطاریه ها
                                </h6>
                                @php
                                    $noticeCount = $employee->notices->count();
                                    $progressWidth = $noticeCount == 0 ? 1 : ($noticeCount == 1 ? 25 : ($noticeCount == 2 ? 50 : ($noticeCount == 3 ? 75 : 100)));
                                @endphp
                                <div class="position-relative">
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-danger"
                                             role="progressbar"
                                             style="width: {{ $progressWidth }}%;"
                                             aria-valuenow="{{ $progressWidth }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ $noticeCount }}
                                        </div>
                                    </div>
                                    <div class="progress-percentage">{{ $progressWidth }}%</div>
                                </div>
                                <small class="text-muted">{{ $noticeCount }} اخطار ثبت شده</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="card mb-4">
                    <div class="card-header p-0 border-0">
                        <ul class="nav nav-tabs" id="employeeTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab">
                                    <i class="fas fa-info-circle mr-2"></i> جزئیات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="notices-tab" data-toggle="tab" href="#notices" role="tab">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> اخطاریه ها
                                    @if($employee->notices->count() > 0)
                                        <span class="badge badge-danger">{{ $employee->notices->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                                    <i class="fas fa-file-alt mr-2"></i> اسناد
                                    <span class="badge badge-info">{{ $employee->files->count() + 1 }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="employeeTabContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            @include('admin.office.employees.inc.tables')
                        </div>

                        <!-- Notices Tab -->
                        <div class="tab-pane fade" id="notices" role="tabpanel">
                            @if($employee->notices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
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
                                            <tr class="{{ $notice->notice == 'high' ? 'table-danger' : ($notice->notice == 'medium' ? 'table-warning' : 'table-info') }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $notice->reason }}</td>
                                                <td>
                                                        <span class="badge badge-{{ $notice->notice == 'high' ? 'danger' : ($notice->notice == 'medium' ? 'warning' : 'info') }}">
                                                            {{ $notice->notice_text }}
                                                        </span>
                                                </td>
                                                <td>
                                                    @if($notice->image)
                                                        <a href="{{ $notice->image }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> مشاهده
                                                        </a>
                                                    @else
                                                        <span class="text-muted">بدون مدرک</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($notice->created_at)) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5 class="text-muted">هیچ اخطاری ثبت نشده است</h5>
                                </div>
                            @endif
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">مدارک و اسناد کارمند</h6>
                                @if($employee->position)
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#new_file{{ $employee->id }}">
                                        <i class="fas fa-plus"></i> افزودن سند
                                    </button>
                                @endif
                            </div>

                            <div class="row">
                                <!-- Signature -->
                                @if($employee->signature)
                                    <div class="col-md-4 mb-3">
                                        <div class="file-thumbnail">
                                            <a href="{{ asset('storage/signatures/' . $employee->signature) }}" target="_blank">
                                                <img src="{{ asset('storage/signatures/' . $employee->signature) }}"
                                                     alt="امضاء" class="img-fluid">
                                            </a>
                                            <div class="p-2">
                                                <small class="text-muted">امضاء</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Other Files -->
                                @foreach($employee->files as $file)
                                    <div class="col-md-4 mb-3">
                                        <div class="file-thumbnail">
                                            @if($employee->status == 0)
                                                <button class="btn btn-danger btn-sm file-delete-btn"
                                                        data-toggle="modal"
                                                        data-target="#delete_file{{ $file->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                            <a href="{{ asset('storage/employees/files/' . $file->path) }}" target="_blank">
                                                @if(in_array(pathinfo($file->path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ asset('storage/employees/files/' . $file->path) }}"
                                                         alt="سند" class="img-fluid">
                                                @else
                                                    <div class="text-center py-5">
                                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                                        <div class="mt-2">{{ pathinfo($file->path, PATHINFO_EXTENSION) }}</div>
                                                    </div>
                                                @endif
                                            </a>
                                            <div class="p-2">
                                                <small class="text-muted">{{ $file->title ?? 'سند ' . $loop->iteration }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($employee->files->count() == 0 && !$employee->signature)
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">هیچ سندی ثبت نشده است</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Column -->
            <div class="col-lg-4 col-md-12">
                <!-- Profile Card -->
                <div class="card info-card mb-4">
                    <div class="card-body text-center">
                        <!-- Profile Image -->
                        <div class="mb-3">
                            <img class="profile-avatar rounded-circle"
                                 src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                 alt="{{ $employee->name }}">
                        </div>

                        <!-- Employee Name -->
                        <h3 class="mb-1">{{ $employee->name }} {{ $employee->last_name }}</h3>

                        <!-- Status Badge -->
                        <div class="mb-3">
                            @if($employee->status == 0)
                                <span class="badge badge-success employee-status-badge">فعال</span>
                            @elseif($employee->status == 1)
                                <span class="badge badge-secondary employee-status-badge">تقاعد</span>
                            @elseif($employee->status == 2)
                                <span class="badge badge-warning employee-status-badge">منفک</span>
                            @elseif($employee->status == 3)
                                <span class="badge badge-info employee-status-badge">تبدیل</span>
                            @elseif($employee->status == 4)
                                <span class="badge badge-danger employee-status-badge">معلق</span>
                            @elseif($employee->status == 5)
                                <span class="badge badge-primary employee-status-badge">خدمتی</span>
                            @endif
                        </div>

                        <!-- Position -->
                        @if($employee->position)
                            <p class="text-muted mb-2">
                                <i class="fas fa-briefcase mr-2"></i>
                                @can('office_position_view')
                                    <a href="{{ route('admin.office.positions.show', $employee->position->id) }}"
                                       target="_blank" class="text-dark">
                                        {{ $employee->position->title }}
                                        @if($employee->position->place)
                                            <small class="d-block text-muted">{{ $employee->position->place->name }}</small>
                                        @endif
                                    </a>
                                @else
                                    {{ $employee->position->title }}
                                @endcan
                            </p>

                            @if($employee->on_duty == 1)
                                <p class="text-muted mb-3">
                                    <i class="fas fa-user-clock mr-2"></i>
                                    {{ $employee->duty_position }}
                                </p>
                            @endif
                        @endif

                        <!-- Rating Stars -->
                        <div class="mb-3">
                            @if($employee->position)
                                @for($i = 1; $i <= $employee->position->position_number; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                            @endif

                            @if($employee->notices->count() >= 1)
                                <i class="fas fa-exclamation-triangle text-danger ml-2"></i>
                                <span class="badge badge-danger">{{ $employee->notices->count() }} اخطار</span>
                            @endif
                        </div>

                        <!-- Quick Stats -->
                        <div class="row text-center mt-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                    <div class="stat-number">{{ $employee->leaves->count() }}</div>
                                    <small>رخصتی ها</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                                    <div class="stat-number">{{ $employee->files->count() + 1 }}</div>
                                    <small>اسناد</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-address-card mr-2"></i> اطلاعات تماس
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Phone -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 mr-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">شماره تماس</small>
                                <a href="tel:{{ $employee->phone }}" class="text-dark">{{ $employee->phone }}</a>
                                @if(!empty($employee->phone2))
                                    <br>
                                    <a href="tel:{{ $employee->phone2 }}" class="text-dark">{{ $employee->phone2 }}</a>
                                @endif
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 mr-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">ایمیل</small>
                                <a href="mailto:{{ $employee->email }}" class="text-dark">{{ $employee->email }}</a>
                            </div>
                        </div>

                        <!-- User Accounts -->
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 mr-3">
                                <i class="fas fa-user-shield text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">حساب های کاربری</small>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @if($employee->user)
                                        <span class="badge badge-success">
                                            <i class="fas fa-user mr-1"></i> BCD-MIS
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">بدون BCD-MIS</span>
                                    @endif

                                    @if($employee->asycuda_user)
                                        <span class="badge badge-info">
                                            <i class="fas fa-database mr-1"></i> Asycuda
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">بدون Asycuda</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card info-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-bolt mr-2"></i> اقدامات سریع
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.office.employees.resumes', $employee->id) }}"
                               class="btn btn-outline-success quick-action-btn">
                                <i class="fas fa-history mr-2"></i> سابقه کاری
                            </a>

                            <a href="{{ route('admin.office.employees.leaves.index', $employee->id) }}"
                               class="btn btn-outline-info quick-action-btn">
                                <i class="fas fa-calendar-alt mr-2"></i> رخصتی ها
                            </a>

                            @can('office_employee_add_score')
                                <button class="btn btn-outline-warning quick-action-btn"
                                        data-toggle="modal" data-target="#score_modal">
                                    <i class="fas fa-star mr-2"></i> ثبت امتیاز
                                </button>
                            @endcan

                            @can('office_employee_add_notice')
                                <button class="btn btn-outline-danger quick-action-btn"
                                        data-toggle="modal" data-target="#notice_modal">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> ثبت اخطار
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- ID Card Section (Preserved as requested) -->
                @if($employee->position)
                    <div class="card info-card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-id-card mr-2"></i> کارت شناسایی
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="position-relative d-inline-block" id="printIdCard">
                                <!-- Employee Profile Picture -->
                                <div class="emp-profile">
                                    <img class="emp-profile-img"
                                         src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                         alt="{{ $employee->name }}">
                                </div>

                                <!-- Employee Name & Last Name -->
                                <div class="emp-name">{{ $employee->name }} {{ $employee->last_name }}</div>

                                <!-- Employee Position -->
                                <div class="emp-pos-title">{{ $employee->position->title }}</div>

                                <!-- Employee ID -->
                                <div class="emp-id">
                                    {{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}
                                </div>

                                <!-- Employee Phone Number -->
                                <div class="emp-phone">{{ $employee->phone }}</div>

                                <!-- ID Card -->
                                <img class="id-card-img"
                                     src="{{ asset('assets/images/emp-id-card.jpg') }}"
                                     alt="کارت شناسایی">
                            </div>

                            <!-- Back of ID Card (Hidden for print) -->
                            <div id="printIdCardBack" class="d-none">
                                <img class="id-card-back-img"
                                     src="{{ asset('assets/images/emp-id-card-back.jpg') }}"
                                     alt="پشت کارت شناسایی">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<!-- Extra Scripts -->
@section('extra_js')
    <script src="{{ asset('backend/assets/js/pages/user-scripts.js') }}"></script>
    <script>
        function printDiv() {
            var printContents = document.getElementById('printIdCard').innerHTML +
                document.getElementById('printIdCardBack').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print ID Card</title>
                    <style>
                        body {
                            direction: rtl;
                            font-family: Tahoma, sans-serif;
                            margin: 0;
                            padding: 20px;
                        }
                        @media print {
                            @page { margin: 0; }
                            body { margin: 1.6cm; }
                        }
                    </style>
                </head>
                <body>
                    <div style="text-align: center; page-break-after: always;">
                        ${printContents}
                    </div>
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        // Initialize tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            // Store active tab in localStorage
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeEmployeeTab', $(e.target).attr('href'));
            });

            // Retrieve active tab from localStorage
            var activeTab = localStorage.getItem('activeEmployeeTab');
            if (activeTab) {
                $('#employeeTab a[href="' + activeTab + '"]').tab('show');
            }
        });

        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        const label = fileInput.nextElementSibling;
                        label.textContent = fileName;
                        label.classList.add('text-primary');
                    }
                });
            }
        });
    </script>

    <!-- Include Modals -->
    @include('admin.inc.status_scripts')
    @include('admin.office.employees.inc.score')
    @include('admin.office.employees.inc.notice')
    @include('admin.office.employees.inc.new_file')
@endsection
