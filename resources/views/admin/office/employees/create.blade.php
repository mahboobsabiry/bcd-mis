@extends('layouts.admin.master')
<!-- Title -->
@section('title', trans('pages.employees.addNewEmployee'))
<!-- Extra Styles -->
@section('extra_css')
    <!-- Fileupload css -->
    <link href="{{ asset('backend/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet">
    <!-- Dropify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
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

        .form-label .optional {
            color: var(--secondary-color);
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }

        .form-control, .select2-container--default .select2-selection--single {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.75rem;
            transition: all 0.3s;
        }

        .form-control:focus, .select2-container--default.select2-container--focus .select2-selection--single {
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

        .form-group {
            margin-bottom: 1.25rem;
        }

        .file-upload-container {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            background-color: #f8f9fc;
        }

        .file-upload-container:hover {
            border-color: var(--primary-color);
            background-color: #f0f3ff;
        }

        .file-upload-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .preview-container {
            margin-top: 1rem;
        }

        .preview-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e3e6f0;
        }

        .preview-signature {
            width: 300px;
            height: 100px;
            object-fit: contain;
            border: 2px dashed #e3e6f0;
            border-radius: 4px;
            padding: 0.5rem;
        }

        .employee-type-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .type-option {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border: 2px solid #e3e6f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-option:hover {
            border-color: var(--primary-color);
            background-color: #f0f3ff;
        }

        .type-option.active {
            border-color: var(--primary-color);
            background-color: #f0f3ff;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .type-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .type-label {
            font-weight: 600;
            color: #3a3b45;
        }

        .type-description {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .form-stepper {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .stepper-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e3e6f0;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
            border: 3px solid white;
            transition: all 0.3s;
        }

        .stepper-step.active .step-number {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 5px rgba(67, 97, 238, 0.1);
        }

        .stepper-step.completed .step-number {
            background-color: var(--success-color);
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6c757d;
            text-align: center;
        }

        .stepper-step.active .step-label {
            color: var(--primary-color);
        }

        .stepper-line {
            flex: 1;
            height: 2px;
            background-color: #e3e6f0;
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            z-index: 1;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #e3e6f0;
            margin-top: 2rem;
            z-index: 100;
        }

        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .info-badge {
            background-color: #e7f4ff;
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .info-badge i {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }

            .employee-type-selector {
                flex-direction: column;
            }

            .form-stepper {
                flex-wrap: wrap;
            }

            .stepper-step {
                margin-bottom: 1rem;
            }

            .stepper-line {
                display: none;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.employees.addNewEmployee')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.office.employees.index') }}">@lang('admin.sidebar.employees')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.employees.addNewEmployee')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Back -->
                <a class="btn btn-outline-secondary btn-sm" href="{{ url()->previous() }}">
                    <i class="fe fe-arrow-left"></i> @lang('global.back')
                </a>

                <!-- Preview -->
                <button type="button" class="btn btn-outline-info btn-sm" id="previewBtn">
                    <i class="fas fa-eye"></i> پیش نمایش
                </button>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Form Stepper -->
        <div class="form-stepper">
            <div class="stepper-line"></div>

            <div class="stepper-step active">
                <div class="step-number">1</div>
                <div class="step-label">نوع کارمند</div>
            </div>

            <div class="stepper-step">
                <div class="step-number">2</div>
                <div class="step-label">معلومات شخصی</div>
            </div>

            <div class="stepper-step">
                <div class="step-number">3</div>
                <div class="step-label">معلومات کاری</div>
            </div>

            <div class="stepper-step">
                <div class="step-number">4</div>
                <div class="step-label">اسناد و تصاویر</div>
            </div>
        </div>

        <!-- Errors Message -->
        @include('admin.inc.alerts')

        <!-- Form -->
        <form method="post" action="{{ route('admin.office.employees.store') }}" data-parsley-validate="" enctype="multipart/form-data">
            @csrf

            <!-- Step 1: Employee Type -->
            <div class="form-section" id="step1">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div>
                        <h3 class="section-title">انتخاب نوع کارمند</h3>
                        <div class="section-subtitle">لطفا نوع استخدام کارمند را انتخاب کنید</div>
                    </div>
                </div>

                <div class="info-badge">
                    <i class="fas fa-info-circle"></i>
                    انتخاب نوع کارمند در فرآیندهای بعدی سیستم تاثیر گذار است
                </div>

                <div class="employee-type-selector">
                    <div class="type-option active" data-type="0">
                        <div class="type-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="type-label">کارمند اصلی</div>
                        <div class="type-description">کارمند برحال این ریاست</div>
                        <input type="radio" name="type" value="0" checked hidden>
                    </div>

                    <div class="type-option" data-type="1">
                        <div class="type-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="type-label">کارمند خدمتی</div>
                        <div class="type-description">طور خدمتی از اداره دیگر</div>
                        <input type="radio" name="type" value="1" hidden>
                    </div>
                </div>

                <div class="mt-4" id="positionSection">
                    <!-- Position Selection -->
                    <div class="form-group">
                        <label class="form-label">
                            <span class="required">*</span>
                            موقف وظیفوی
                        </label>
                        <select id="ps_code_id" name="ps_code_id" class="form-control select2 @error('ps_code_id') is-invalid @enderror" required>
                            <option value="">انتخاب موقف...</option>
                            @foreach($codes as $code)
                                <option value="{{ $code->id }}" {{ old('ps_code_id') == $code->id ? 'selected' : '' }}>
                                    {{ $code->code }} - {{ $code->position->title }} ({{ $code->position->place->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ps_code_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="help-text">کد بست و موقف وظیفوی را انتخاب کنید</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary next-step" data-next="step2">
                        مرحله بعد <i class="fas fa-arrow-left ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div class="form-section" id="step2" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h3 class="section-title">معلومات شخصی</h3>
                        <div class="section-subtitle">لطفا معلومات شخصی کارمند را وارد کنید</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-signature mr-2 text-primary"></i>
                                نام
                            </label>
                            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" required placeholder="نام کارمند">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-signature mr-2 text-primary"></i>
                                تخلص
                            </label>
                            <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name" value="{{ old('last_name') }}" placeholder="تخلص کارمند">
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Father Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-male mr-2 text-primary"></i>
                                نام پدر
                            </label>
                            <input type="text" id="father_name" class="form-control @error('father_name') is-invalid @enderror"
                                   name="father_name" value="{{ old('father_name') }}" required placeholder="نام پدر">
                            @error('father_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Gender -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-venus-mars mr-2 text-primary"></i>
                                جنسیت
                            </label>
                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                <option value="1" {{ old('gender', 1) == 1 ? 'selected' : '' }}>مرد</option>
                                <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>زن</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Birth Year -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-birthday-cake mr-2 text-primary"></i>
                                سال تولد
                            </label>
                            <input type="number" id="birth_year" class="form-control @error('birth_year') is-invalid @enderror"
                                   name="birth_year" value="{{ old('birth_year') }}" required
                                   min="1300" max="1402" placeholder="مثال: 1360">
                            @error('birth_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text">سال تولد به شمسی (بین 1300 تا 1402)</div>
                        </div>

                        <!-- NID Number -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-id-card mr-2 text-primary"></i>
                                نمبر تذکره
                            </label>
                            <input type="text" id="nid_number" class="form-control @error('nid_number') is-invalid @enderror"
                                   name="nid_number" value="{{ old('nid_number') }}" required placeholder="نمبر تذکره">
                            @error('nid_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step1">
                        <i class="fas fa-arrow-right mr-2"></i> مرحله قبل
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="step3">
                        مرحله بعد <i class="fas fa-arrow-left ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Job Information -->
            <div class="form-section" id="step3" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h3 class="section-title">معلومات کاری و تماس</h3>
                        <div class="section-subtitle">لطفا معلومات کاری و تماس کارمند را وارد کنید</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Start Job Date -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                                تاریخ آغاز کار
                            </label>
                            <input data-jdp data-jdp-max-date="today" type="text" id="start_job"
                                   class="form-control @error('start_job') is-invalid @enderror"
                                   name="start_job" value="{{ old('start_job') }}" required
                                   placeholder="تاریخ شروع کار">
                            @error('start_job')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Appointment Information -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-file-contract mr-2 text-primary"></i>
                                معلومات تقرر
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="appointment_number" class="form-control @error('appointment_number') is-invalid @enderror mb-2" name="appointment_number" value="{{ old('appointment_number') }}"
                                           placeholder="نمبر تقرر" required>
                                </div>
                                <div class="col-md-6">
                                    <input data-jdp data-jdp-max-date="today" type="text" id="appointment_date"
                                           class="form-control @error('appointment_date') is-invalid @enderror"
                                           name="appointment_date" value="{{ old('appointment_date') }}"
                                           placeholder="تاریخ تقرر" required>
                                </div>
                            </div>
                            @error('appointment_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('appointment_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Education -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-graduation-cap mr-2 text-primary"></i>
                                تحصیلات
                            </label>
                            <input type="text" id="education" class="form-control @error('education') is-invalid @enderror"
                                   name="education" value="{{ old('education') }}" placeholder="مثال: لیسانس اقتصاد">
                            @error('education')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Employee Number -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-hashtag mr-2 text-primary"></i>
                                نمبر کارمندی
                            </label>
                            <input type="number" id="emp_number" class="form-control @error('emp_number') is-invalid @enderror"
                                   name="emp_number" value="{{ old('emp_number') }}" required placeholder="مثال: 1234">
                            @error('emp_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Contact Information -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-address-book mr-2 text-primary"></i>
                                معلومات تماس
                            </label>
                            <input type="email" id="email" class="form-control mb-2 @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="ایمیل">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone') }}" placeholder="شماره تماس اصلی">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="phone2" class="form-control @error('phone2') is-invalid @enderror"
                                           name="phone2" value="{{ old('phone2') }}" placeholder="شماره تماس دوم">
                                    @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                آدرس اصلی
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="main_province" class="form-control mb-2 @error('main_province') is-invalid @enderror"
                                           name="main_province" value="{{ old('main_province') }}" placeholder="ولایت">
                                    @error('main_province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="main_district" class="form-control @error('main_district') is-invalid @enderror"
                                           name="main_district" value="{{ old('main_district') }}" placeholder="ولسوالی">
                                    @error('main_district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Address -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-home mr-2 text-primary"></i>
                                آدرس فعلی
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="current_province" class="form-control mb-2 @error('current_province') is-invalid @enderror"
                                           name="current_province" value="{{ old('current_province') }}" placeholder="ولایت فعلی">
                                    @error('current_province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="current_district" class="form-control @error('current_district') is-invalid @enderror"
                                           name="current_district" value="{{ old('current_district') }}" placeholder="ولسوالی فعلی">
                                    @error('current_district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hostel -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bed mr-2 text-primary"></i>
                                اقامتگاه
                            </label>
                            <select class="form-control select2 @error('hostel_id') is-invalid @enderror" name="hostel_id" id="hostel_id">
                                <option value="">منزل شخصی</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                        اتاق {{ $hostel->number }} - سکشن {{ $hostel->section }} ({{ $hostel->place->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('hostel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- PRR/NPR -->
                        <div class="form-group">
                            <label class="form-label">
                                <span class="required">*</span>
                                <i class="fas fa-passport mr-2 text-primary"></i>
                                PRR/NPR
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control @error('prr_npr') is-invalid @enderror" name="prr_npr" id="prr_npr">
                                        <option value="NPR" {{ old('prr_npr', 'NPR') == 'NPR' ? 'selected' : '' }}>NPR</option>
                                        <option value="PRR" {{ old('prr_npr') == 'PRR' ? 'selected' : '' }}>PRR</option>
                                    </select>
                                    @error('prr_npr')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input data-jdp data-jdp-max-date="today" type="text" id="prr_date"
                                           class="form-control @error('prr_date') is-invalid @enderror"
                                           name="prr_date" value="{{ old('prr_date') }}" placeholder="تاریخ PRR">
                                    @error('prr_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Last Duty & Introducer -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-history mr-2 text-primary"></i>
                                وظیفه قبلی
                            </label>
                            <input type="text" id="last_duty" class="form-control @error('last_duty') is-invalid @enderror"
                                   name="last_duty" value="{{ old('last_duty') }}" placeholder="وظیفه قبلی">
                            @error('last_duty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-friends mr-2 text-primary"></i>
                                معرف
                            </label>
                            <input type="text" id="introducer" class="form-control @error('introducer') is-invalid @enderror"
                                   name="introducer" value="{{ old('introducer') }}" placeholder="نام معرف">
                            @error('introducer')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step2">
                        <i class="fas fa-arrow-right mr-2"></i> مرحله قبل
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="step4">
                        مرحله بعد <i class="fas fa-arrow-left ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 4: Documents and Finalization -->
            <div class="form-section" id="step4" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <div>
                        <h3 class="section-title">اسناد و اطلاعات اضافی</h3>
                        <div class="section-subtitle">لطفا تصاویر و اطلاعات اضافی را وارد کنید</div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-camera mr-2 text-primary"></i>
                                تصویر شخصی
                            </label>
                            <div class="file-upload-container" onclick="document.getElementById('photo').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="mb-2">
                                    <strong>برای آپلود تصویر کلیک کنید</strong>
                                </div>
                                <div class="text-muted small">
                                    فرمت‌های مجاز: JPG, PNG, GIF<br>
                                    حداکثر حجم: 2MB
                                </div>
                                <input type="file" id="photo" name="photo" accept="image/*" style="display: none;"
                                       onchange="previewImage(this, 'photoPreview')">
                            </div>
                            <div class="preview-container text-center">
                                <img id="photoPreview" class="preview-image"
                                     src="{{ asset('assets/images/avatar-default.jpeg') }}"
                                     alt="پیش نمایش تصویر" style="display: block;">
                            </div>
                            @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-signature mr-2 text-primary"></i>
                                نمونه امضاء
                            </label>
                            <div class="file-upload-container" onclick="document.getElementById('signature').click()">
                                <div class="file-upload-icon">
                                    <i class="fas fa-signature"></i>
                                </div>
                                <div class="mb-2">
                                    <strong>برای آپلود امضاء کلیک کنید</strong>
                                </div>
                                <div class="text-muted small">
                                    فرمت‌های مجاز: JPG, PNG<br>
                                    حداکثر حجم: 1MB
                                </div>
                                <input type="file" id="signature" name="signature" accept="image/*" style="display: none;"
                                       onchange="previewImage(this, 'signaturePreview')">
                            </div>
                            <div class="preview-container text-center">
                                <img id="signaturePreview" class="preview-signature"
                                     src="{{ asset('assets/images/signature-placeholder.png') }}"
                                     alt="پیش نمایش امضاء" style="display: block;">
                            </div>
                            @error('signature')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Extra Information -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sticky-note mr-2 text-primary"></i>
                        توضیحات اضافی
                    </label>
                    <textarea name="info" class="form-control @error('info') is-invalid @enderror"
                              rows="4" placeholder="هرگونه توضیحات اضافی درباره کارمند">{{ old('info') }}</textarea>
                    @error('info')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step3">
                        <i class="fas fa-arrow-right mr-2"></i> مرحله قبل
                    </button>

                    <div>
                        <button type="reset" class="btn btn-outline-danger mr-2">
                            <i class="fas fa-redo"></i> پاک کردن فرم
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> ذخیره کارمند
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Action Buttons (Sticky) -->
        <div class="action-buttons">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn">
                        <i class="fas fa-save"></i> ذخیره پیش نویس
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.office.employees.index') }}" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-times"></i> انصراف
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Dropify JS -->
    <script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>
    <!-- Select2 -->
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: 'انتخاب کنید...',
                allowClear: true
            });

            // Employee Type Selector
            $('.type-option').on('click', function() {
                $('.type-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);

                var type = $(this).data('type');
                if (type == "1") {
                    $('#positionSection').hide();
                } else {
                    $('#positionSection').show();
                }
            });

            // Stepper Navigation
            $('.next-step').on('click', function() {
                var nextStep = $(this).data('next');
                var currentStep = $(this).closest('.form-section').attr('id');

                // Validate current step
                if (validateStep(currentStep)) {
                    // Update stepper UI
                    $('.stepper-step').removeClass('active');
                    $('#' + nextStep + ' .stepper-step').addClass('active');

                    // Show next step
                    $('.form-section').hide();
                    $('#' + nextStep).show();

                    // Scroll to top
                    $('html, body').animate({
                        scrollTop: $('#' + nextStep).offset().top - 100
                    }, 300);
                }
            });

            $('.prev-step').on('click', function() {
                var prevStep = $(this).data('prev');

                // Update stepper UI
                $('.stepper-step').removeClass('active');
                $('#' + prevStep + ' .stepper-step').addClass('active');

                // Show previous step
                $('.form-section').hide();
                $('#' + prevStep).show();

                // Scroll to top
                $('html, body').animate({
                    scrollTop: $('#' + prevStep).offset().top - 100
                }, 300);
            });

            // Form validation for each step
            function validateStep(stepId) {
                var isValid = true;
                var $step = $('#' + stepId);

                // Check required fields
                $step.find('[required]').each(function() {
                    if ($(this).val() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;

                        // Show error message
                        if (!$(this).next('.invalid-feedback').length) {
                            $(this).after('<div class="invalid-feedback">این فیلد الزامی است</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.invalid-feedback').remove();
                    }
                });

                // Special validation for email
                var email = $step.find('#email').val();
                if (email && !isValidEmail(email)) {
                    $step.find('#email').addClass('is-invalid');
                    $step.find('#email').after('<div class="invalid-feedback">لطفا یک ایمیل معتبر وارد کنید</div>');
                    isValid = false;
                }

                // Special validation for birth year
                var birthYear = $step.find('#birth_year').val();
                if (birthYear && (birthYear < 1300 || birthYear > 1402)) {
                    $step.find('#birth_year').addClass('is-invalid');
                    $step.find('#birth_year').after('<div class="invalid-feedback">سال تولد باید بین ۱۳۰۰ تا ۱۴۰۲ باشد</div>');
                    isValid = false;
                }

                return isValid;
            }

            function isValidEmail(email) {
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // Image preview
            window.previewImage = function(input, previewId) {
                var preview = document.getElementById(previewId);
                var file = input.files[0];
                var reader = new FileReader();

                reader.onloadend = function() {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    preview.src = previewId === 'photoPreview' ?
                        "{{ asset('assets/images/avatar-default.jpeg') }}" :
                        "{{ asset('assets/images/signature-placeholder.png') }}";
                }
            }

            // Save draft
            $('#saveDraftBtn').on('click', function() {
                // Implement save draft functionality
                alert('ذخیره پیش نویس در حال توسعه است...');
            });

            // Preview form
            $('#previewBtn').on('click', function() {
                // Collect form data and show preview
                alert('پیش نمایش در حال توسعه است...');
            });

            // Initialize date pickers
            $('[data-jdp]').each(function() {
                $(this).attr('readonly', true);
            });

            // Real-time validation
            $('input, select, textarea').on('blur', function() {
                if ($(this).is('[required]') && !$(this).val()) {
                    $(this).addClass('is-invalid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">این فیلد الزامی است</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
