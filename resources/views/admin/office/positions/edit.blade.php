@extends('layouts.admin.master')
<!-- Title -->
@section('title', config('app.name') . ' ~ ' . trans('pages.positions.editPosition'))
<!-- Extra Styles -->
@section('extra_css')
    <link href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

        .form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
        }

        .section-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
        }

        .section-title {
            font-weight: 600;
            color: #3a3b45;
            margin: 0;
        }

        .section-subtitle {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label .required {
            color: var(--danger-color);
            margin-right: 0.25rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.75rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .info-alert {
            background-color: #e7f4ff;
            border: 1px solid #cce5ff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .info-alert i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
        }

        .warning-alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .warning-alert i {
            color: var(--warning-color);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
            color: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .code-management {
            background-color: #f8f9fc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .code-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #e7f4ff;
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.875rem;
            margin: 0.125rem;
            border: 1px solid #cce5ff;
            position: relative;
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

        .code-badge .employee-name {
            font-size: 0.75rem;
            display: block;
            margin-top: 0.125rem;
            opacity: 0.8;
        }

        .code-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #e3e6f0;
            margin-top: 2rem;
            z-index: 100;
            border-radius: 0 0 10px 10px;
        }

        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .read-only-field {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            color: #6c757d;
        }

        .audit-info {
            background-color: #f8f9fc;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }

        .audit-info label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
            display: block;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-icon {
                margin-bottom: 0.5rem;
            }

            .code-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.positions.editPosition')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.index') }}">@lang('admin.sidebar.positions')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.show', $position->id) }}">@lang('global.details')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.positions.editPosition')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <a class="btn btn-outline-secondary btn-sm" href="{{ url()->previous() }}">
                    <i class="fe fe-arrow-left"></i> @lang('global.back')
                </a>

                <a href="{{ route('admin.office.positions.show', $position->id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye"></i> مشاهده
                </a>

                @can('office_position_create')
                    <a href="{{ route('admin.office.positions.create') }}?parent={{ $position->id }}"
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus"></i> زیرمجموعه جدید
                    </a>
                @endcan
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Errors Message -->
        @include('admin.inc.alerts')

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کل بست‌ها</h6>
                            <div class="stats-number">{{ $position->num_of_pos }}</div>
                        </div>
                        <i class="fas fa-layer-group fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--success-color), #20c997);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">بست‌های پر</h6>
                            @php
                                $filledCodes = $position->codes->filter(fn($code) => $code->employee)->count();
                            @endphp
                            <div class="stats-number">{{ $filledCodes }}</div>
                        </div>
                        <i class="fas fa-user-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--warning-color), #ff922b);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">بست‌های خالی</h6>
                            @php
                                $emptyCodes = $position->codes->count() - $filledCodes;
                                $vacantPositions = max(0, $position->num_of_pos - $position->codes->count());
                                $totalVacant = $emptyCodes + $vacantPositions;
                            @endphp
                            <div class="stats-number">{{ $totalVacant }}</div>
                        </div>
                        <i class="fas fa-user-slash fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, var(--info-color), #3dc7be);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">کدهای تعریف شده</h6>
                            <div class="stats-number">{{ $position->codes->count() }}</div>
                        </div>
                        <i class="fas fa-hashtag fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="form-card">
                    <div class="card-body">
                        <!-- Warning for Position with Codes -->
                        @if($position->codes->count() > 0)
                            <div class="warning-alert">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                                <div>
                                    <strong>توجه!</strong>
                                    <p class="mb-0">
                                        این بست دارای {{ $position->codes->count() }} کد تعریف شده است.
                                        تغییر تعداد بست‌ها ممکن است بر کدهای موجود تأثیر بگذارد.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="post" action="{{ route('admin.office.positions.update', $position->id) }}" id="positionEditForm" data-parsley-validate="">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">اطلاعات پایه</h3>
                                        <div class="section-subtitle">ویرایش معلومات اصلی بست وظیفوی</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Parent Position -->
                                        <div class="form-group @error('parent_id') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-level-up-alt mr-2 text-primary"></i>
                                                @lang('pages.positions.underHand')
                                            </label>
                                            <select id="parent_id" name="parent_id" class="form-control select2 @error('parent_id') is-invalid @enderror" required>
                                                <option value="">ریاست (بدون مافوق)</option>
                                                @foreach($positions->where('id', '!=', $position->id) as $pos)
                                                    <option value="{{ $pos->id }}"
                                                        {{ $position->parent_id == $pos->id ? 'selected' : '' }}
                                                        {{ $pos->id == $position->id ? 'disabled' : '' }}>
                                                        {{ $pos->title }}
                                                        @if($pos->place)
                                                            <small class="text-muted">({{ $pos->place->name }})</small>
                                                        @endif
                                                        @if($pos->id == $position->id)
                                                            <small class="text-danger">(خود بست)</small>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">بست مافوق این موقعیت را انتخاب کنید (انتخاب خود بست مجاز نیست)</div>
                                        </div>

                                        <!-- Title -->
                                        <div class="form-group @error('title') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-heading mr-2 text-primary"></i>
                                                @lang('form.title')
                                            </label>
                                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                                                   name="title" value="{{ old('title', $position->title) }}" required
                                                   placeholder="عنوان بست وظیفوی" maxlength="255">
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">عنوان کامل بست را وارد کنید (حداکثر ۲۵۵ کاراکتر)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Place -->
                                        <div class="form-group @error('place_id') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                                موقعیت
                                            </label>
                                            <select id="place_id" name="place_id" class="form-control select2 @error('place_id') is-invalid @enderror" required>
                                                <option value="">انتخاب موقعیت...</option>
                                                @foreach($places as $place)
                                                    <option value="{{ $place->id }}"
                                                        {{ $position->place_id == $place->id ? 'selected' : '' }}>
                                                        {{ $place->name }}
                                                        @if($place->custom_code)
                                                            <small class="text-muted">({{ $place->custom_code }})</small>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('place_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">موقعیت جغرافیایی یا اداری بست را انتخاب کنید</div>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group @error('desc') has-danger @enderror">
                                            <label class="form-label">
                                                <i class="fas fa-sticky-note mr-2 text-primary"></i>
                                                @lang('form.extraInfo')
                                            </label>
                                            <textarea name="desc" class="form-control @error('desc') is-invalid @enderror"
                                                      rows="3" placeholder="توضیحات اضافی درباره این بست">{{ old('desc', $position->desc) }}</textarea>
                                            @error('desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">هرگونه توضیحات اضافی درباره مسئولیت‌ها یا ویژگی‌های این بست</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Position Details Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">جزئیات موقعیت</h3>
                                        <div class="section-subtitle">ویرایش تنظیمات درجه و تعداد بست</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Position Number (Grade) -->
                                        <div class="form-group @error('position_number') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-sort-numeric-up mr-2 text-primary"></i>
                                                @lang('pages.positions.positionNumber')
                                            </label>
                                            <input type="number" id="position_number" class="form-control @error('position_number') is-invalid @enderror"
                                                   name="position_number" value="{{ old('position_number', $position->position_number) }}" required
                                                   min="1" max="10" placeholder="درجه بست">
                                            @error('position_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">درجه بست را مشخص کنید (۱ = پایین‌ترین، ۱۰ = بالاترین)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Number of Positions -->
                                        <div class="form-group @error('num_of_pos') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-layer-group mr-2 text-primary"></i>
                                                @lang('form.num_of_pos')
                                            </label>
                                            <div class="position-relative">
                                                <input type="number" id="num_of_pos" class="form-control @error('num_of_pos') is-invalid @enderror"
                                                       name="num_of_pos" value="{{ old('num_of_pos', $position->num_of_pos) }}" required
                                                       min="{{ $position->codes->count() }}" max="50" placeholder="تعداد بست‌ها">
                                                <div class="help-text">
                                                    حداقل: {{ $position->codes->count() }} (تعداد کدهای تعریف شده)
                                                </div>
                                            </div>
                                            @error('num_of_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">تعداد کل بست‌های مجاز برای این موقعیت (کمتر از کدهای موجود مجاز نیست)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Code Management Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">مدیریت کدهای بست</h3>
                                        <div class="section-subtitle">مشاهده و مدیریت کدهای تعریف شده</div>
                                    </div>
                                </div>

                                <div class="code-management">
                                    <label class="form-label mb-3">
                                        <i class="fas fa-hashtag mr-2 text-primary"></i>
                                        کدهای تعریف شده برای این بست
                                    </label>

                                    @if($position->codes->count() > 0)
                                        <div class="mb-3">
                                            @foreach($position->codes as $code)
                                                <span class="code-badge {{ $code->employee ? 'filled' : 'empty' }}"
                                                      title="{{ $code->employee ? 'پر شده توسط: ' . $code->employee->name . ' ' . $code->employee->last_name : 'خالی' }}">
                                                {{ $code->code }}
                                                    @if($code->employee)
                                                        <span class="employee-name">
                                                        {{ $code->employee->name }} {{ $code->employee->last_name }}
                                                    </span>
                                                    @else
                                                        <span class="employee-name">خالی</span>
                                                    @endif
                                            </span>
                                            @endforeach
                                        </div>

                                        <div class="code-actions">
                                            <a href="{{ route('admin.office.positions.index', $position->id) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i> مدیریت کدها
                                            </a>

                                            @if($position->num_of_pos > $position->codes->count())
                                                <a href="{{ route('admin.office.positions.add_code', $position->id) }}"
                                                   class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-plus"></i> افزودن کد جدید
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                    <i class="fas fa-plus"></i> ظرفیت کد تکمیل شده
                                                </button>
                                            @endif

                                            <button type="button" class="btn btn-outline-info btn-sm" id="generateCodesBtn">
                                                <i class="fas fa-magic"></i> تولید کدهای جدید
                                            </button>
                                        </div>

                                        <div class="help-text mt-2">
                                            {{ $position->codes->count() }} کد از {{ $position->num_of_pos }} بست تعریف شده است.
                                            {{ $position->num_of_pos - $position->codes->count() }} بست دیگر می‌توانید کد تعریف کنید.
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            هیچ کدی برای این بست تعریف نشده است.
                                        </div>

                                        <div class="code-actions">
                                            <a href="{{ route('admin.office.positions.codes.create', $position->id) }}"
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i> تعریف اولین کد
                                            </a>

                                            <button type="button" class="btn btn-outline-info btn-sm" id="generateCodesBtn">
                                                <i class="fas fa-magic"></i> تولید کد خودکار
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Position Statistics -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="read-only-field">
                                            <label class="small text-muted d-block mb-1">تعداد زیرمجموعه‌ها</label>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-sitemap text-primary mr-2"></i>
                                                <span class="font-weight-bold">{{ $position->children->count() }}</span>
                                                <span class="text-muted mr-2">زیرمجموعه</span>
                                                @if($position->children->count() > 0)
                                                    <a href="{{ route('admin.office.positions.index') }}?parent={{ $position->id }}"
                                                       class="btn btn-link btn-sm">
                                                        مشاهده
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="read-only-field">
                                            <label class="small text-muted d-block mb-1">کارمندان مرتبط</label>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users text-primary mr-2"></i>
                                                <span class="font-weight-bold">{{ $position->employees->count() }}</span>
                                                <span class="text-muted mr-2">کارمند</span>
                                                @if($position->employees->count() > 0)
                                                    <a href="{{ route('admin.office.employees.index') }}?position={{ $position->id }}"
                                                       class="btn btn-link btn-sm">
                                                        مشاهده
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Audit Information -->
                            <div class="audit-info">
                                <label>اطلاعات سیستمی:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">تاریخ ایجاد:</small>
                                        <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($position->created_at)) }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">آخرین ویرایش:</small>
                                        <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d H:i', strtotime($position->updated_at)) }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">وضعیت:</small>
                                        <span class="badge badge-success">فعال</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger" id="resetFormBtn">
                                            <i class="fas fa-redo"></i> بازنشانی تغییرات
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.office.positions.show', $position->id) }}"
                                           class="btn btn-outline-secondary mr-2">
                                            <i class="fas fa-times"></i> انصراف
                                        </a>

                                        <button type="button" class="btn btn-outline-warning mr-2" id="previewChangesBtn">
                                            <i class="fas fa-eye"></i> پیش نمایش تغییرات
                                        </button>

                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> ذخیره تغییرات
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: 'انتخاب کنید...',
                allowClear: true
            });

            // Store original form values
            const originalValues = {
                title: $('#title').val(),
                parent_id: $('#parent_id').val(),
                place_id: $('#place_id').val(),
                position_number: $('#position_number').val(),
                num_of_pos: $('#num_of_pos').val(),
                desc: $('#desc').val()
            };

            // Validate num_of_pos against existing codes
            $('#num_of_pos').on('change', function() {
                const newValue = parseInt($(this).val()) || 0;
                const existingCodes = {{ $position->codes->count() }};

                if (newValue < existingCodes) {
                    alert(`تعداد بست‌ها نمی‌تواند کمتر از ${existingCodes} باشد (تعداد کدهای تعریف شده)`);
                    $(this).val(existingCodes);
                }
            });

            // Generate suggested codes button
            $('#generateCodesBtn').on('click', function() {
                const title = $('#title').val();
                const place = $('#place_id option:selected').text();
                const numOfPos = parseInt($('#num_of_pos').val()) || 1;
                const existingCodes = {{ $position->codes->count() }};

                if (!title || !place) {
                    showToast('لطفا عنوان و موقعیت را پر کنید', 'warning');
                    return;
                }

                if (existingCodes >= numOfPos) {
                    showToast('ظرفیت کدها تکمیل شده است', 'warning');
                    return;
                }

                const placeCode = place.substring(0, 2).toUpperCase();
                const titleCode = title.substring(0, 2).toUpperCase();
                const newCodesCount = numOfPos - existingCodes;

                let suggestions = [];
                for (let i = 1; i <= newCodesCount; i++) {
                    const codeNumber = String(existingCodes + i).padStart(3, '0');
                    suggestions.push(`${placeCode}${titleCode}${codeNumber}`);
                }

                const suggestionsText = suggestions.join(', ');

                Swal.fire({
                    title: 'پیشنهاد کدهای جدید',
                    html: `
                <div class="text-right">
                    <p>${newCodesCount} کد پیشنهادی:</p>
                    <div class="alert alert-info">
                        ${suggestions.map(code => `<span class="code-badge">${code}</span>`).join('')}
                    </div>
                    <p>آیا می‌خواهید به صفحه مدیریت کدها هدایت شوید؟</p>
                </div>
            `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'بله، مدیریت کدها',
                    cancelButtonText: 'بعداً',
                    confirmButtonColor: '#4361ee'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.office.positions.add_code', $position->id) }}";
                    }
                });
            });

            // Preview changes button
            $('#previewChangesBtn').on('click', function() {
                const changes = detectChanges();

                if (changes.length === 0) {
                    showToast('هیچ تغییری اعمال نشده است', 'info');
                    return;
                }

                let changesHtml = '<ul class="list-group list-group-flush text-right">';
                changes.forEach(change => {
                    changesHtml += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${change.field}</span>
                    <div>
                        <small class="text-muted">${change.old}</small>
                        <i class="fas fa-arrow-left mx-2 text-primary"></i>
                        <strong>${change.new}</strong>
                    </div>
                </li>
            `;
                });
                changesHtml += '</ul>';

                Swal.fire({
                    title: 'پیش نمایش تغییرات',
                    html: changesHtml,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'ذخیره تغییرات',
                    cancelButtonText: 'بازنگری',
                    confirmButtonColor: '#28a745',
                    width: '600px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#positionEditForm').submit();
                    }
                });
            });

            // Reset form button
            $('#resetFormBtn').on('click', function() {
                Swal.fire({
                    title: 'بازنشانی فرم',
                    text: 'آیا مطمئن هستید که می‌خواهید تمام تغییرات را بازنشانی کنید؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله، بازنشانی کن',
                    cancelButtonText: 'انصراف',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#title').val(originalValues.title);
                        $('#parent_id').val(originalValues.parent_id).trigger('change');
                        $('#place_id').val(originalValues.place_id).trigger('change');
                        $('#position_number').val(originalValues.position_number);
                        $('#num_of_pos').val(originalValues.num_of_pos);
                        $('#desc').val(originalValues.desc);

                        showToast('فرم با موفقیت بازنشانی شد', 'success');
                    }
                });
            });

            // Form validation before submit
            $('#positionEditForm').on('submit', function(e) {
                const newNumOfPos = parseInt($('#num_of_pos').val()) || 0;
                const existingCodes = {{ $position->codes->count() }};

                if (newNumOfPos < existingCodes) {
                    e.preventDefault();
                    showToast(`تعداد بست‌ها نمی‌تواند کمتر از ${existingCodes} باشد`, 'danger');
                    return false;
                }

                // Prevent circular reference (position being its own parent)
                const parentId = $('#parent_id').val();
                if (parentId == "{{ $position->id }}") {
                    e.preventDefault();
                    showToast('بست نمی‌تواند مافوق خودش باشد', 'danger');
                    return false;
                }

                // Check if there are any changes
                const changes = detectChanges();
                if (changes.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'هیچ تغییری اعمال نشده است',
                        text: 'آیا می‌خواهید بدون تغییرات فرم را ارسال کنید؟',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'بله، ارسال کن',
                        cancelButtonText: 'بازگشت',
                        confirmButtonColor: '#4361ee'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#positionEditForm').off('submit').submit();
                        }
                    });
                    return false;
                }
            });

            // Helper functions
            function detectChanges() {
                const changes = [];
                const fieldLabels = {
                    title: 'عنوان',
                    parent_id: 'بست مافوق',
                    place_id: 'موقعیت',
                    position_number: 'درجه بست',
                    num_of_pos: 'تعداد بست‌ها',
                    desc: 'توضیحات'
                };

                // Check each field for changes
                Object.keys(originalValues).forEach(field => {
                    const currentValue = $(`#${field}`).val();
                    if (currentValue != originalValues[field]) {
                        let oldValue = originalValues[field];
                        let newValue = currentValue;

                        // For select fields, get the text
                        if (field === 'parent_id' || field === 'place_id') {
                            oldValue = getSelectText(field, originalValues[field]);
                            newValue = getSelectText(field, currentValue);
                        }

                        changes.push({
                            field: fieldLabels[field] || field,
                            old: oldValue || '(خالی)',
                            new: newValue || '(خالی)'
                        });
                    }
                });

                return changes;
            }

            function getSelectText(fieldId, value) {
                const $select = $(`#${fieldId}`);
                const $selectedOption = $select.find(`option[value="${value}"]`);
                return $selectedOption.text() || '';
            }

            function showToast(message, type = 'info') {
                // Simple toast implementation
                const toast = $(`
            <div class="toast-alert alert alert-${type} alert-dismissible fade show position-fixed"
                 style="bottom: 20px; left: 20px; z-index: 1050;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `);
                $('body').append(toast);
                setTimeout(() => toast.alert('close'), 5000);
            }

            // Auto-update min value for num_of_pos when codes are changed
            $(document).on('positionCodesUpdated', function() {
                // This event can be triggered from other pages/modals
                location.reload();
            });
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
