@extends('layouts.admin.master')
<!-- Title -->
@section('title', trans('admin.sidebar.employees'))
<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        .employee-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .employee-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .employee-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 5px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 0.375rem 0.75rem;
            border: 1px solid #dee2e6;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .view-switch {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        .view-switch .btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .filter-card {
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fc;
        }

        .quick-filter-btn {
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            margin: 0.25rem;
            font-size: 0.875rem;
        }

        .employee-row:hover {
            background-color: #f8f9fa;
        }

        .user-account-badge {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 3px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }

        .pagination-controls {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .employee-avatar {
                width: 40px;
                height: 40px;
            }

            .stats-card {
                padding: 0.75rem;
            }

            .stats-number {
                font-size: 1.5rem;
            }
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('admin.sidebar.employees')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('admin.sidebar.employees')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Add New -->
                @can('office_employee_create')
                    <a class="btn ripple btn-primary" href="{{ route('admin.office.employees.create') }}">
                        <i class="fe fe-plus-circle"></i> @lang('global.new')
                    </a>
                @endcan

                <!-- Export Button -->
                <button class="btn ripple btn-success" id="exportBtn">
                    <i class="fas fa-file-export"></i> خروجی
                </button>

                <!-- Filter Toggle -->
                <button class="btn ripple btn-info" id="filterToggle">
                    <i class="fas fa-filter"></i> فیلتر
                </button>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کل کارمندان</h6>
                            <div class="stats-number">{{ count($employees) }}</div>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--success-color), #20c997);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کارمندان فعال</h6>
                            <div class="stats-number">{{ $employees->where('status', 0)->count() }}</div>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--warning-color), #ff922b);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کارمندان خدمتی</h6>
                            <div class="stats-number">{{ $employees->where('on_duty', 1)->count() }}</div>
                        </div>
                        <i class="fas fa-user-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--info-color), #3dc7be);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کارمندان معلق</h6>
                            <div class="stats-number">{{ $employees->where('status', 4)->count() }}</div>
                        </div>
                        <i class="fas fa-user-lock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4" id="filterSection" style="display: none;">
            <div class="col-12">
                <div class="filter-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fas fa-filter mr-2"></i>فیلتر پیشرفته</h6>
                        <button class="btn btn-sm btn-light" id="clearFilters">
                            <i class="fas fa-times mr-1"></i>پاک کردن فیلترها
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>وضعیت</label>
                            <select class="form-control filter-select" id="statusFilter">
                                <option value="">همه وضعیت ها</option>
                                <option value="0">فعال</option>
                                <option value="1">تقاعد</option>
                                <option value="2">منفک</option>
                                <option value="3">تبدیل</option>
                                <option value="4">معلق</option>
                                <option value="5">خدمتی</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>موقعیت</label>
                            <select class="form-control filter-select" id="positionFilter">
                                <option value="">همه موقعیت ها</option>
                                @foreach($employees->pluck('position')->whereNotNull()->unique('id') as $position)
                                    @if($position)
                                        <option value="{{ $position->id }}">{{ $position->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>استان فعلی</label>
                            <select class="form-control filter-select" id="provinceFilter">
                                <option value="">همه استان ها</option>
                                @foreach($employees->pluck('current_province')->unique()->filter() as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>حساب کاربری</label>
                            <select class="form-control filter-select" id="accountFilter">
                                <option value="">همه حساب ها</option>
                                <option value="bcd">دارای حساب BCD-MIS</option>
                                <option value="asycuda">دارای حساب اسیکودا</option>
                                <option value="both">دارای هر دو حساب</option>
                                <option value="none">بدون حساب</option>
                            </select>
                        </div>
                    </div>

                    <!-- Quick Filters -->
                    <div class="mt-3">
                        <label class="mb-2">فیلترهای سریع:</label>
                        <div>
                            <button class="btn btn-outline-primary quick-filter-btn" data-filter="notice">
                                <i class="fas fa-exclamation-triangle mr-1"></i>دارای اخطار
                            </button>
                            <button class="btn btn-outline-success quick-filter-btn" data-filter="leave">
                                <i class="fas fa-calendar-alt mr-1"></i>دارای رخصتی
                            </button>
                            <button class="btn btn-outline-info quick-filter-btn" data-filter="hostel">
                                <i class="fas fa-home mr-1"></i>ساکن هاستل
                            </button>
                            <button class="btn btn-outline-warning quick-filter-btn" data-filter="new">
                                <i class="fas fa-star mr-1"></i>کارمندان جدید
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @include('admin.inc.alerts')

        <!-- Data Table -->
        <div class="row">
            <div class="col-lg-12">
                <!-- View Toggle -->
                <div class="view-switch d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-outline-primary active" id="tableViewBtn">
                            <i class="fas fa-table"></i> نمایش جدولی
                        </button>
                        <button class="btn btn-outline-secondary ml-2" id="cardViewBtn">
                            <i class="fas fa-th-large"></i> نمایش کارتی
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <input type="text" class="form-control form-control-sm" id="searchBox" placeholder="جستجوی کارمند...">
                        </div>
                        <span class="text-muted">{{ count($employees) }} کارمند یافت شد</span>
                    </div>
                </div>

                <!-- Table View -->
                <div id="tableView">
                    <div class="card employee-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-users mr-2"></i>لیست کارمندان
                            </h6>
                            <div class="action-buttons">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-light" id="refreshTable">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" id="columnToggle" data-toggle="dropdown">
                                        <i class="fas fa-columns"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" id="columnMenu">
                                        <!-- Columns will be added dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="employeesTable">
                                    <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="80">تصویر</th>
                                        <th>شهرت</th>
                                        <th>معلومات بست</th>
                                        <th>تماس</th>
                                        <th>وضعیت</th>
                                        <th>آدرس</th>
                                        <th>حساب کاربری</th>
                                        <th>رخصتی</th>
                                        <th>اخطار</th>
                                        <th width="100">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $employee)
                                        <tr class="employee-row">
                                            <td>
                                                <span class="badge badge-light border">{{ $employee->id }}</span>
                                            </td>
                                            <td>
                                                <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                     alt="{{ $employee->name }}"
                                                     class="employee-avatar"
                                                     onerror="this.src='{{ asset('assets/images/avatar-default.jpeg') }}'">
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                       class="font-weight-bold text-dark">
                                                        {{ $employee->name }} {{ $employee->last_name }}
                                                    </a>
                                                    <div class="text-muted small">
                                                        <i class="fas fa-user-tag mr-1"></i>{{ $employee->father_name ?? '--' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $employee->position->title ?? '--' }}</div>
                                                <div class="small">
                                                    @if($employee->on_duty == 1)
                                                        <span class="badge badge-warning">خدمتی</span>
                                                        <span class="text-muted">{{ $employee->duty_position }}</span>
                                                    @else
                                                        <span class="badge badge-success">اصل بست</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted">{{ $employee->position_code->code ?? '--' }}</div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="tel:{{ $employee->phone }}" class="text-success">
                                                        <i class="fas fa-phone-alt mr-1"></i>{{ $employee->phone ?? '--' }}
                                                    </a>
                                                </div>
                                                <div class="small text-muted">
                                                    <a href="mailto:{{ $employee->email }}" class="text-primary">
                                                        <i class="fas fa-envelope mr-1"></i>{{ $employee->email ?? '--' }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                @if($employee->status == 0)
                                                    <span class="badge badge-success status-badge">فعال</span>
                                                @elseif($employee->status == 1)
                                                    <span class="badge badge-secondary status-badge">تقاعد</span>
                                                @elseif($employee->status == 2)
                                                    <span class="badge badge-danger status-badge">منفک</span>
                                                @elseif($employee->status == 3)
                                                    <span class="badge badge-info status-badge">تبدیل</span>
                                                @elseif($employee->status == 4)
                                                    <span class="badge badge-warning status-badge">معلق</span>
                                                @elseif($employee->status == 5)
                                                    <span class="badge badge-primary status-badge">خدمتی</span>
                                                @endif

                                                @if($employee->hostel)
                                                    <div class="small mt-1">
                                                        <i class="fas fa-home text-info"></i> هاستل
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $employee->current_province ?? '--' }}</div>
                                                <div class="small text-muted">{{ $employee->current_district ?? '--' }}</div>
                                                <div class="small">
                                                    <i class="fas fa-user-friends mr-1"></i>{{ $employee->introducer ?? '--' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($employee->user)
                                                        <span class="user-account-badge bg-success" title="دارای حساب BCD-MIS"></span>
                                                    @endif
                                                    @if($employee->asycuda_user)
                                                        <span class="user-account-badge bg-info ml-1" title="دارای حساب اسیکودا"></span>
                                                    @endif
                                                    <span class="small">
                                                            @if($employee->user && $employee->asycuda_user)
                                                            هر دو حساب
                                                        @elseif($employee->user)
                                                            فقط BCD-MIS
                                                        @elseif($employee->asycuda_user)
                                                            فقط اسیکودا
                                                        @else
                                                            بدون حساب
                                                        @endif
                                                        </span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $leaveCount = $employee->leaves->count();
                                                    $totalDays = $employee->leaves->sum('days');
                                                @endphp
                                                <div class="text-center">
                                                    <div class="font-weight-bold text-primary">{{ $totalDays }}</div>
                                                    <div class="small text-muted">{{ $leaveCount }} بار</div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $noticeCount = $employee->notices->count();
                                                @endphp
                                                @if($noticeCount > 0)
                                                    <span class="badge badge-danger" title="{{ $noticeCount }} اخطار">
                                                            <i class="fas fa-exclamation-triangle"></i> {{ $noticeCount }}
                                                        </span>
                                                @else
                                                    <span class="badge badge-success">بدون اخطار</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="مشاهده">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @can('office_employee_edit')
                                                        @if($employee->status == 0 || $employee->status == 4 || $employee->status == 5)
                                                            <a href="{{ route('admin.office.employees.edit', $employee->id) }}"
                                                               class="btn btn-sm btn-outline-info ml-1"
                                                               title="ویرایش">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('office_employee_add_score')
                                                        <button class="btn btn-sm btn-outline-success ml-1"
                                                                title="امتیاز"
                                                                onclick="addScore({{ $employee->id }})">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card View -->
                <div id="cardView" style="display: none;">
                    <div class="row">
                        @foreach($employees as $employee)
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                                <div class="card employee-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                 alt="{{ $employee->name }}"
                                                 class="employee-avatar mr-3"
                                                 onerror="this.src='{{ asset('assets/images/avatar-default.jpeg') }}'">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                       class="text-dark">
                                                        {{ $employee->name }} {{ $employee->last_name }}
                                                    </a>
                                                </h6>
                                                <div class="small text-muted">
                                                    <i class="fas fa-user-tag mr-1"></i>{{ $employee->father_name ?? '--' }}
                                                </div>
                                                <div class="mt-1">
                                                    @if($employee->status == 0)
                                                        <span class="badge badge-success status-badge">فعال</span>
                                                    @elseif($employee->status == 1)
                                                        <span class="badge badge-secondary status-badge">تقاعد</span>
                                                    @elseif($employee->status == 2)
                                                        <span class="badge badge-danger status-badge">منفک</span>
                                                    @elseif($employee->status == 3)
                                                        <span class="badge badge-info status-badge">تبدیل</span>
                                                    @elseif($employee->status == 4)
                                                        <span class="badge badge-warning status-badge">معلق</span>
                                                    @elseif($employee->status == 5)
                                                        <span class="badge badge-primary status-badge">خدمتی</span>
                                                    @endif

                                                    @if($employee->on_duty == 1)
                                                        <span class="badge badge-warning status-badge">خدمتی</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="employee-details mb-3">
                                            <div class="mb-2">
                                                <i class="fas fa-briefcase mr-2 text-primary"></i>
                                                <span class="small">{{ $employee->position->title ?? '--' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-phone-alt mr-2 text-success"></i>
                                                <span class="small">{{ $employee->phone ?? '--' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-map-marker-alt mr-2 text-info"></i>
                                                <span class="small">{{ $employee->current_province ?? '--' }}</span>
                                            </div>
                                            @if($employee->hostel)
                                                <div class="mb-2">
                                                    <i class="fas fa-home mr-2 text-warning"></i>
                                                    <span class="small">هاستل {{ $employee->hostel->number }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="employee-stats d-flex justify-content-between border-top pt-3">
                                            <div class="text-center">
                                                <div class="font-weight-bold text-primary">{{ $employee->leaves->sum('days') ?? '0' }}</div>
                                                <div class="small text-muted">روز رخصتی</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-weight-bold {{ $employee->notices->count() > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $employee->notices->count() }}
                                                </div>
                                                <div class="small text-muted">اخطار</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-weight-bold text-info">
                                                    @if($employee->user && $employee->asycuda_user)
                                                        2
                                                    @elseif($employee->user || $employee->asycuda_user)
                                                        1
                                                    @else
                                                        0
                                                    @endif
                                                </div>
                                                <div class="small text-muted">حساب</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 pt-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye mr-1"></i> مشاهده
                                            </a>
                                            @can('office_employee_edit')
                                                @if($employee->status == 0 || $employee->status == 4 || $employee->status == 5)
                                                    <a href="{{ route('admin.office.employees.edit', $employee->id) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Empty State -->
                @if(count($employees) == 0)
                    <div class="card employee-card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h5 class="mt-3">کارمندی یافت نشد</h5>
                                <p class="text-muted">هنوز هیچ کارمندی ثبت نشده است.</p>
                                @can('office_employee_create')
                                    <a href="{{ route('admin.office.employees.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle mr-1"></i> افزودن اولین کارمند
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#employeesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Persian.json"
                },
                "pageLength": 25,
                "order": [[0, 'desc']],
                "responsive": true,
                "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>',
                "initComplete": function() {
                    // Add column toggle menu
                    var columns = this.api().columns().header().toArray();
                    var columnMenu = $('#columnMenu');

                    $.each(columns, function(index, column) {
                        if (index > 0 && index < columns.length - 1) { // Skip ID and Actions columns
                            var colText = $(column).text();
                            var checkbox = $('<div class="custom-control custom-checkbox"></div>')
                                .append(
                                    $('<input type="checkbox" class="custom-control-input" id="col' + index + '" checked>')
                                        .attr('data-column', index)
                                        .on('change', function() {
                                            var columnIdx = $(this).data('column');
                                            var column = table.column(columnIdx);
                                            column.visible(!column.visible());
                                        })
                                )
                                .append(
                                    $('<label class="custom-control-label" for="col' + index + '"></label>')
                                        .text(colText)
                                );

                            columnMenu.append($('<div class="dropdown-item"></div>').append(checkbox));
                        }
                    });
                }
            });

            // Search functionality
            $('#searchBox').on('keyup', function() {
                table.search(this.value).draw();
            });

            // View toggle
            $('#tableViewBtn').on('click', function() {
                $(this).addClass('active').removeClass('btn-outline-primary').addClass('btn-primary');
                $('#cardViewBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-secondary');
                $('#tableView').show();
                $('#cardView').hide();
            });

            $('#cardViewBtn').on('click', function() {
                $(this).addClass('active').removeClass('btn-outline-secondary').addClass('btn-primary');
                $('#tableViewBtn').removeClass('active').removeClass('btn-primary').addClass('btn-outline-primary');
                $('#cardView').show();
                $('#tableView').hide();
            });

            // Filter toggle
            $('#filterToggle').on('click', function() {
                $('#filterSection').slideToggle();
                $(this).toggleClass('btn-info btn-secondary');
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('.filter-select').val('');
                table.search('').columns().search('').draw();
                $('.quick-filter-btn').removeClass('active');
            });

            // Apply filters
            $('.filter-select').on('change', function() {
                var columnIndex = $(this).attr('id') === 'statusFilter' ? 5 :
                    $(this).attr('id') === 'positionFilter' ? 2 :
                        $(this).attr('id') === 'provinceFilter' ? 6 :
                            $(this).attr('id') === 'accountFilter' ? 7 : null;

                if (columnIndex !== null) {
                    var searchTerm = $(this).val();
                    table.column(columnIndex).search(searchTerm).draw();
                }
            });

            // Quick filters
            $('.quick-filter-btn').on('click', function() {
                var filterType = $(this).data('filter');
                $(this).toggleClass('active');

                // Implement quick filter logic
                switch(filterType) {
                    case 'notice':
                        table.column(9).search($(this).hasClass('active') ? '\\d+' : '').draw();
                        break;
                    case 'leave':
                        table.column(8).search($(this).hasClass('active') ? '^[1-9]' : '').draw();
                        break;
                    case 'new':
                        // Filter for employees created in last 30 days
                        break;
                }
            });

            // Refresh table
            $('#refreshTable').on('click', function() {
                table.ajax.reload();
                $(this).addClass('fa-spin');
                setTimeout(() => {
                    $(this).removeClass('fa-spin');
                }, 1000);
            });

            // Export functionality
            $('#exportBtn').on('click', function() {
                var data = table.rows({ filter: 'applied' }).data();
                var exportData = [];

                data.each(function(value, index) {
                    exportData.push({
                        'ID': value[0],
                        'نام': value[2],
                        'موقعیت': value[3],
                        'تماس': value[4],
                        'وضعیت': value[5],
                        'آدرس': value[6]
                    });
                });

                // Create and download CSV
                downloadCSV(exportData, 'employees_export.csv');
            });

            function downloadCSV(data, filename) {
                var csv = 'ID,نام,موقعیت,تماس,وضعیت,آدرس\n';

                $.each(data, function(index, row) {
                    csv += Object.values(row).map(function(value) {
                        return '"' + String(value).replace(/"/g, '""') + '"';
                    }).join(',') + '\n';
                });

                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                var link = document.createElement("a");
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            // Add score function
            window.addScore = function(employeeId) {
                // You can implement a modal or redirect for adding score
                alert('افزودن امتیاز برای کارمند با ID: ' + employeeId);
                // Or open a modal: $('#scoreModal' + employeeId).modal('show');
            };
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
