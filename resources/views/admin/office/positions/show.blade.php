@extends('layouts.admin.master')
<!-- Title -->
@section('title', $position->title)
<!-- Extra Styles -->
@section('extra_css')
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

        .position-header-card {
            background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .position-header-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.3;
        }

        .position-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            object-fit: cover;
        }

        .position-grade-stars {
            font-size: 1.5rem;
            margin: 0.5rem 0;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .info-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .card-header-custom {
            background-color: #f8f9fc;
            border-bottom: 2px solid var(--primary-color);
            padding: 1rem 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background-color: #e7f4ff;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #3a3b45;
            word-break: break-word;
        }

        .code-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #e7f4ff;
            color: var(--primary-color);
            border-radius: 8px;
            margin: 0.25rem;
            border: 1px solid #cce5ff;
            transition: all 0.3s;
            position: relative;
        }

        .code-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
        }

        .code-badge.filled {
            background-color: #d4edda;
            color: var(--success-color);
            border-color: #c3e6cb;
        }

        .code-badge.empty {
            background-color: #f8d7da;
            color: var(--danger-color);
            border-color: #f5c6cb;
        }

        .code-employee {
            font-size: 0.75rem;
            display: block;
            margin-top: 0.25rem;
            opacity: 0.8;
        }

        .organization-tree {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 1.5rem;
            overflow-x: auto;
        }

        .tree-node {
            background: white;
            border: 2px solid #e3e6f0;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem auto;
            max-width: 300px;
            position: relative;
            transition: all 0.3s;
        }

        .tree-node:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .tree-node.current {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f0f3ff, white);
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
        }

        .tree-connector {
            width: 2px;
            height: 20px;
            background-color: #dee2e6;
            margin: 0 auto;
        }

        .employee-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

        .tab-content-custom {
            background-color: #fff;
            border-radius: 0 0 10px 10px;
            padding: 1.5rem;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .nav-tabs-custom .nav-link {
            border-radius: 8px 8px 0 0;
            margin-left: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #6c757d;
            border: 1px solid transparent;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--primary-color);
            background-color: #f8f9fc;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary-color);
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            border-bottom: 3px solid var(--primary-color);
        }

        .action-buttons .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .position-avatar {
                width: 80px;
                height: 80px;
            }

            .stats-card {
                padding: 1rem;
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
                <h2 class="main-content-title tx-24 mg-b-5">{{ $position->title }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.index') }}">@lang('pages.positions.positions')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $position->title }}</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <div class="d-flex flex-wrap gap-2">
                    @can('office_position_edit')
                        <a class="btn btn-primary" href="{{ route('admin.office.positions.edit', $position->id) }}">
                            <i class="fas fa-edit mr-1"></i> @lang('global.edit')
                        </a>
                    @endcan

                    @can('office_position_create')
                        <a class="btn btn-success" href="{{ route('admin.office.positions.create') }}?parent={{ $position->id }}">
                            <i class="fas fa-plus-circle mr-1"></i> افزودن زیرمجموعه
                        </a>
                    @endcan

                    @can('office_position_create')
                        @if($position->codes->count() < $position->num_of_pos)
                            <button class="btn btn-info" data-toggle="modal" data-target="#addCodeModal">
                                <i class="fas fa-key mr-1"></i> افزودن کد
                            </button>
                        @else
                            <button class="btn btn-secondary" disabled title="ظرفیت کدها تکمیل شده است">
                                <i class="fas fa-key mr-1"></i> ظرفیت تکمیل
                            </button>
                        @endif
                    @endcan

                    @can('office_position_delete')
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deletePosition{{ $position->id }}">
                            <i class="fas fa-trash mr-1"></i> @lang('global.delete')
                        </button>
                    @endcan

                    <a class="btn btn-outline-secondary" href="{{ route('admin.office.positions.index') }}">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Success Message -->
        @include('admin.inc.alerts')

        <!-- Position Header -->
        <div class="position-header-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="position-avatar bg-white d-flex align-items-center justify-content-center">
                                <i class="fas fa-briefcase fa-3x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="mb-1">{{ $position->title }}</h1>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-light mr-2">ID: {{ $position->id }}</span>
                                <span class="badge badge-info">{{ $position->place->name ?? 'بدون موقعیت' }}</span>
                            </div>
                            <div class="position-grade-stars">
                                @for($i = 1; $i <= $position->position_number; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                                <span class="ml-2">درجه {{ $position->position_number }}</span>
                            </div>
                            @if($position->desc)
                                <p class="mb-0 opacity-75">{{ Str::limit($position->desc, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <div class="text-white-75">
                        <div class="mb-1">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            ایجاد شده در {{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($position->created_at)) }}
                        </div>
                        <div>
                            <i class="fas fa-history mr-2"></i>
                            آخرین ویرایش {{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($position->updated_at)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #e7f4ff; color: var(--primary-color);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stats-number">{{ $position->num_of_pos }}</div>
                    <div class="stats-label">تعداد بست‌ها</div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                @php
                    $filledCodes = $position->codes->filter(fn($code) => $code->employee)->count();
                    $emptyCodes = $position->codes->count() - $filledCodes;
                @endphp
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #d4edda; color: var(--success-color);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stats-number">{{ $filledCodes }}</div>
                    <div class="stats-label">بست‌های پر</div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #f8d7da; color: var(--danger-color);">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="stats-number">{{ $emptyCodes }}</div>
                    <div class="stats-label">بست‌های خالی</div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon" style="background-color: #fff3cd; color: var(--warning-color);">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="stats-number">{{ $position->children->count() }}</div>
                    <div class="stats-label">زیرمجموعه‌ها</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Tabs Navigation -->
                <div class="card info-card mb-4">
                    <div class="card-header-custom p-0 border-0">
                        <ul class="nav nav-tabs nav-tabs-custom" id="positionTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab">
                                    <i class="fas fa-info-circle mr-2"></i> جزئیات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="codes-tab" data-toggle="tab" href="#codes" role="tab">
                                    <i class="fas fa-key mr-2"></i> کدهای بست
                                    @if($position->codes->count() > 0)
                                        <span class="badge badge-primary ml-1">{{ $position->codes->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="employees-tab" data-toggle="tab" href="#employees" role="tab">
                                    <i class="fas fa-users mr-2"></i> کارمندان
                                    @if($position->employees->count() > 0)
                                        <span class="badge badge-success ml-1">{{ $position->employees->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hierarchy-tab" data-toggle="tab" href="#hierarchy" role="tab">
                                    <i class="fas fa-project-diagram mr-2"></i> ساختار سازمانی
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content tab-content-custom" id="positionTabContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">شناسه</div>
                                            <div class="info-value">ID-{{ $position->id }}</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-level-up-alt"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">بست مافوق</div>
                                            <div class="info-value">
                                                @if($position->parent)
                                                    <a href="{{ route('admin.office.positions.show', $position->parent->id) }}"
                                                       class="text-primary">
                                                        {{ $position->parent->title }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">ریاست</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-sort-numeric-up"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">درجه بست</div>
                                            <div class="info-value">
                                                <span class="badge badge-info">درجه {{ $position->position_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">موقعیت</div>
                                            <div class="info-value">
                                                {{ $position->place->name ?? 'بدون موقعیت' }}
                                                @if($position->place && $position->place->custom_code)
                                                    <small class="text-muted d-block">{{ $position->place->custom_code }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">تاریخ ایجاد</div>
                                            <div class="info-value">
                                                {{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($position->created_at)) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">آخرین ویرایش</div>
                                            <div class="info-value">
                                                {{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($position->updated_at)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($position->desc)
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">توضیحات</div>
                                        <div class="info-value">{{ $position->desc }}</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Summary -->
                            <div class="alert alert-info mt-3">
                                <div class="d-flex">
                                    <i class="fas fa-chart-pie fa-2x mr-3 text-info"></i>
                                    <div>
                                        <strong>خلاصه وضعیت:</strong>
                                        <div class="mt-1">
                                            این بست دارای {{ $position->num_of_pos }} موقعیت است که
                                            {{ $filledCodes }} موقعیت پر و {{ $emptyCodes }} موقعیت خالی می‌باشد.
                                            @if($position->children->count() > 0)
                                                همچنین {{ $position->children->count() }} زیرمجموعه دارد.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Codes Tab -->
                        <div class="tab-pane fade" id="codes" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">کدهای تعریف شده برای این بست</h6>
                                @can('office_position_create')
                                    @if($position->codes->count() < $position->num_of_pos)
                                        <button class="btn btn-info" data-toggle="modal" data-target="#addCodeModal">
                                            <i class="fas fa-key mr-1"></i> افزودن کد
                                        </button>
                                    @else
                                        <button class="btn btn-secondary" disabled title="ظرفیت کدها تکمیل شده است">
                                            <i class="fas fa-key mr-1"></i> ظرفیت تکمیل
                                        </button>
                                    @endif
                                @endcan
                            </div>

                            @if($position->codes->count() > 0)
                                <div class="row">
                                    @foreach($position->codes as $code)
                                        <div class="col-md-6 mb-3">
                                            <div class="code-badge {{ $code->employee ? 'filled' : 'empty' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ $code->code }}</strong>
                                                        @if($code->employee)
                                                            <span class="code-employee">
                                                                {{ $code->employee->name }} {{ $code->employee->last_name }}
                                                            </span>
                                                        @else
                                                            <span class="code-employee">خالی</span>
                                                        @endif
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link text-dark" type="button"
                                                                data-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                               data-target="#show_code{{ $code->id }}">
                                                                <i class="fas fa-eye mr-2"></i> مشاهده
                                                            </a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                               data-target="#edit_code{{ $code->id }}">
                                                                <i class="fas fa-edit mr-2"></i> ویرایش
                                                            </a>
                                                            @if(!$code->employee)
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                   onclick="confirmDelete('{{ route('admin.office.positions.destroy', $code->id) }}')">
                                                                    <i class="fas fa-trash mr-2"></i> حذف
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Include Modals -->
                                @foreach($position->codes as $code)
                                    @include('admin.office.positions.show_edit_code')
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-key"></i>
                                    <h5 class="mt-3">کدی تعریف نشده است</h5>
                                    <p class="text-muted">هنوز هیچ کدی برای این بست ایجاد نشده است.</p>
                                    <a href="{{ route('admin.office.positions.codes.create', $position->id) }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> تعریف اولین کد
                                    </a>
                                </div>
                            @endif

                            <!-- Add Code Modal -->
                            @if($position->codes->count() < $position->num_of_pos)
                                @include('admin.office.positions.add_code')
                            @endif
                        </div>

                        <!-- Employees Tab -->
                        <div class="tab-pane fade" id="employees" role="tabpanel">
                            @if($position->employees->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="employeesTable">
                                        <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>کارمند</th>
                                            <th>کد بست</th>
                                            <th>تماس</th>
                                            <th>وضعیت</th>
                                            <th width="80">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($position->employees as $employee)
                                            <tr>
                                                <td>{{ $employee->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                             alt="{{ $employee->name }}"
                                                             class="employee-avatar-small mr-2"
                                                             onerror="this.src='{{ asset('assets/images/avatar-default.jpeg') }}'">
                                                        <div>
                                                            <div class="font-weight-bold">
                                                                @can('office_employee_view')
                                                                    <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                                       class="text-dark">
                                                                        {{ $employee->name }} {{ $employee->last_name }}
                                                                    </a>
                                                                @else
                                                                    {{ $employee->name }} {{ $employee->last_name }}
                                                                @endcan
                                                            </div>
                                                            <div class="small text-muted">{{ $employee->father_name ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                        <span class="badge badge-light">
                                                            {{ $employee->position_code->code ?? '--' }}
                                                        </span>
                                                </td>
                                                <td>
                                                    @if($employee->phone)
                                                        <a href="tel:{{ $employee->phone }}" class="text-success">
                                                            <i class="fas fa-phone-alt mr-1"></i>{{ $employee->phone }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employee->status == 0)
                                                        <span class="badge badge-success">فعال</span>
                                                    @else
                                                        <span class="badge badge-secondary">غیرفعال</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                       class="btn btn-sm btn-outline-primary" title="مشاهده">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5 class="mt-3">کارمندی یافت نشد</h5>
                                    <p class="text-muted">هیچ کارمندی در این بست مشغول به کار نیست.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Hierarchy Tab -->
                        <div class="tab-pane fade" id="hierarchy" role="tabpanel">
                            <div class="organization-tree">
                                <div class="text-center">
                                    <!-- Parent -->
                                    @if($position->parent)
                                        <div class="tree-node">
                                            <a href="{{ route('admin.office.positions.show', $position->parent->id) }}"
                                               class="text-dark">
                                                <div class="font-weight-bold">{{ $position->parent->title }}</div>
                                                <div class="small text-muted">
                                                    درجه {{ $position->parent->position_number }}
                                                </div>
                                            </a>
                                        </div>
                                        <div class="tree-connector"></div>
                                    @endif

                                    <!-- Current Position -->
                                    <div class="tree-node current">
                                        <div class="font-weight-bold">{{ $position->title }}</div>
                                        <div class="small text-muted mb-2">
                                            درجه {{ $position->position_number }} | {{ $position->num_of_pos }} بست
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <span class="badge badge-primary mx-1">پر: {{ $filledCodes }}</span>
                                            <span class="badge badge-danger mx-1">خالی: {{ $emptyCodes }}</span>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    @if($position->children->count() > 0)
                                        <div class="tree-connector"></div>
                                        <div class="row justify-content-center mt-3">
                                            @foreach($position->children as $child)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="tree-node">
                                                        <a href="{{ route('admin.office.positions.show', $child->id) }}"
                                                           class="text-dark">
                                                            <div class="font-weight-bold">{{ $child->title }}</div>
                                                            <div class="small text-muted">
                                                                درجه {{ $child->position_number }} | {{ $child->num_of_pos }} بست
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Legend -->
                            <div class="mt-3">
                                <div class="alert alert-light">
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2" style="width: 20px; height: 20px; border-radius: 4px; background-color: #4361ee;"></div>
                                            <span>بست فعلی</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2" style="width: 20px; height: 20px; border-radius: 4px; border: 2px solid #e3e6f0;"></div>
                                            <span>بست‌های مرتبط</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card info-card mb-4">
                    <div class="card-header-custom">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-bolt mr-2"></i> اقدامات سریع
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @can('office_position_edit')
                                <a href="{{ route('admin.office.positions.edit', $position->id) }}"
                                   class="btn btn-outline-primary btn-block text-right">
                                    <i class="fas fa-edit mr-2"></i> ویرایش بست
                                </a>
                            @endcan

                            @can('office_position_create')
                                <a href="{{ route('admin.office.positions.create') }}?parent={{ $position->id }}"
                                   class="btn btn-outline-success btn-block text-right">
                                    <i class="fas fa-plus mr-2"></i> افزودن زیرمجموعه
                                </a>

                                <a href="{{ route('admin.office.positions.add_code', $position->id) }}"
                                   class="btn btn-outline-info btn-block text-right">
                                    <i class="fas fa-key mr-2"></i> مدیریت کدها
                                </a>
                            @endcan

                            <a href="{{ route('admin.office.employees.create') }}?position={{ $position->id }}"
                               class="btn btn-outline-warning btn-block text-right">
                                <i class="fas fa-user-plus mr-2"></i> افزودن کارمند
                            </a>

                            <button class="btn btn-outline-secondary btn-block text-right" onclick="window.print()">
                                <i class="fas fa-print mr-2"></i> چاپ اطلاعات
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Information -->
                <div class="card info-card mb-4">
                    <div class="card-header-custom">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-link mr-2"></i> اطلاعات مرتبط
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">زیرمجموعه‌ها</span>
                                <span class="badge badge-info">{{ $position->children->count() }}</span>
                            </div>
                            @if($position->children->count() > 0)
                                <div class="small">
                                    @foreach($position->children->take(3) as $child)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="{{ route('admin.office.positions.show', $child->id) }}"
                                               class="text-dark">
                                                {{ $child->title }}
                                            </a>
                                            <span class="badge badge-light">{{ $child->num_of_pos }}</span>
                                        </div>
                                    @endforeach
                                    @if($position->children->count() > 3)
                                        <a href="{{ route('admin.office.positions.index') }}?parent={{ $position->id }}"
                                           class="btn btn-link btn-sm p-0">
                                            مشاهده همه →
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted small">بدون زیرمجموعه</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">کارمندان فعال</span>
                                <span class="badge badge-success">{{ $filledCodes }}</span>
                            </div>
                            @if($filledCodes > 0)
                                <div class="small">
                                    @foreach($position->employees->take(3) as $employee)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>{{ $employee->name }} {{ $employee->last_name }}</span>
                                            <span class="badge badge-light">{{ $employee->position_code->code ?? '--' }}</span>
                                        </div>
                                    @endforeach
                                    @if($filledCodes > 3)
                                        <a href="{{ route('admin.office.employees.index') }}?position={{ $position->id }}"
                                           class="btn btn-link btn-sm p-0">
                                            مشاهده همه →
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted small">بدون کارمند</div>
                            @endif
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">کدهای خالی</span>
                                <span class="badge badge-danger">{{ $emptyCodes }}</span>
                            </div>
                            @if($emptyCodes > 0)
                                <div class="small">
                                    @foreach($position->codes->where('employee_id', null)->take(3) as $code)
                                        <div class="mb-1">
                                            <span class="badge badge-light">{{ $code->code }}</span>
                                        </div>
                                    @endforeach
                                    @if($emptyCodes > 3)
                                        <span class="text-muted">+ {{ $emptyCodes - 3 }} کد دیگر</span>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted small">بدون کد خالی</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="card info-card">
                    <div class="card-header-custom">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-database mr-2"></i> اطلاعات سیستمی
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">شناسه:</span>
                                <span class="font-weight-bold">{{ $position->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">وضعیت:</span>
                                <span class="badge badge-success">فعال</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">تاریخ ایجاد:</span>
                                <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($position->created_at)) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">آخرین ویرایش:</span>
                                <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($position->updated_at)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Modal -->
        @include('admin.office.positions.delete', ['position' => $position])
        @include('admin.office.positions.modals.add-code')
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable for employees
            $('#employeesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Persian.json"
                },
                "pageLength": 10,
                "order": [[0, 'desc']],
                "responsive": true,
                "dom": '<"top"<"clear">>rt<"bottom"ip<"clear">>'
            });

            // Tab persistence
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem('activePositionTab', $(e.target).attr('href'));
            });

            var activeTab = localStorage.getItem('activePositionTab');
            if (activeTab) {
                $('#positionTab a[href="' + activeTab + '"]').tab('show');
            }

            // Delete confirmation
            window.confirmDelete = function(url) {
                Swal.fire({
                    title: 'آیا مطمئن هستید؟',
                    text: "این عمل قابل بازگشت نیست!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله، حذف کن',
                    cancelButtonText: 'انصراف',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create a form and submit
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        form.innerHTML = `
                            @csrf
                        @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            };

            // Print functionality
            $('[onclick="window.print()"]').on('click', function() {
                // Store current active tab
                var currentTab = $('#positionTab .nav-link.active').attr('href');

                // Open print window with only the active tab content
                var printContent = $('#positionTabContent').find(currentTab).html();
                var printWindow = window.open('', '_blank');

                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html dir="rtl">
                    <head>
                        <title>اطلاعات بست - ${$('#position-title').text()}</title>
                        <meta charset="utf-8">
                        <style>
                            body { font-family: Tahoma, sans-serif; direction: rtl; padding: 20px; }
                            h1 { color: #4361ee; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                            th { background-color: #f8f9fa; }
                        </style>
                    </head>
                    <body>
                        <h1>${$('#position-title').text()}</h1>
                        <hr>
                        ${printContent}
                    </body>
                    </html>
                `);

                printWindow.document.close();
                printWindow.print();
            });

            // Code badge hover effect
            $(document).on('mouseenter', '.code-badge', function() {
                $(this).css('transform', 'translateY(-3px)');
            }).on('mouseleave', '.code-badge', function() {
                $(this).css('transform', 'translateY(0)');
            });
        });
    </script>

    @include('admin.inc.status_scripts')
@endsection
<!--/==/ End of Extra Scripts -->
