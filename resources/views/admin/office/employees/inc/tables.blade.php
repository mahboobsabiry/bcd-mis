<div class="employee-details-container">
    <!-- Main Information Sections -->
    <div class="row">
        <!-- Personal Information Card -->
        <div class="col-lg-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-light d-flex align-items-center">
                    <div class="section-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-user fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 font-weight-bold">@lang('pages.employees.personalInfo')</h5>
                        <small class="text-muted">معلومات شخصی کارمند</small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ID Row -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-id-card text-primary mr-2"></i>
                            <span>شناسه:</span>
                        </div>
                        <div class="detail-value">
                            <span class="badge badge-light border">ID-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    <!-- Name Row -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-signature text-primary mr-2"></i>
                            <span>نام و تخلص:</span>
                        </div>
                        <div class="detail-value">
                            <span class="font-weight-bold">{{ $employee->name }}</span>
                            <span class="text-muted">({{ $employee->last_name }})</span>
                        </div>
                    </div>

                    <!-- Father Name -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-male text-primary mr-2"></i>
                            <span>@lang('form.fatherName'):</span>
                        </div>
                        <div class="detail-value">{{ $employee->father_name }}</div>
                    </div>

                    <!-- Gender -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-venus-mars text-primary mr-2"></i>
                            <span>@lang('form.gender'):</span>
                        </div>
                        <div class="detail-value">
                            <span class="badge badge-{{ $employee->gender == 1 ? 'info' : 'pink' }}">
                                {{ $employee->gender == 1 ? trans('form.male') : trans('form.female') }}
                            </span>
                        </div>
                    </div>

                    <!-- Birth Year & Age -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-birthday-cake text-primary mr-2"></i>
                            <span>سال تولد و عمر:</span>
                        </div>
                        <div class="detail-value">
                            <span class="font-weight-bold">{{ $employee->birth_year }}</span>
                            <span class="text-muted">({{ \Morilog\Jalali\Jalalian::now()->getYear() - $employee->birth_year }} سال)</span>
                        </div>
                    </div>

                    <!-- Employee Number -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-hashtag text-primary mr-2"></i>
                            <span>@lang('form.empNumber'):</span>
                        </div>
                        <div class="detail-value">
                            <code class="bg-light p-1 rounded">{{ $employee->emp_number }}</code>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-envelope text-primary mr-2"></i>
                            <span>@lang('form.email'):</span>
                        </div>
                        <div class="detail-value">
                            <a href="mailto:{{ $employee->email }}" class="text-primary">
                                <i class="fas fa-external-link-alt mr-1"></i>{{ $employee->email }}
                            </a>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-phone text-primary mr-2"></i>
                            <span>@lang('form.phone'):</span>
                        </div>
                        <div class="detail-value">
                            <a href="tel:{{ $employee->phone }}" class="text-success">
                                <i class="fas fa-phone-alt mr-1"></i>{{ $employee->phone }}
                            </a>
                            @if($employee->phone2)
                                <br>
                                <a href="tel:{{ $employee->phone2 }}" class="text-success">
                                    <i class="fas fa-phone-alt mr-1"></i>{{ $employee->phone2 }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Appointment Information -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-file-signature text-primary mr-2"></i>
                            <span>نمبر و تاریخ تقرر:</span>
                        </div>
                        <div class="detail-value">
                            <div class="d-flex">
                                <span class="mr-3">
                                    <small class="text-muted d-block">نمبر:</small>
                                    {{ $employee->appointment_number }}
                                </span>
                                <span>
                                    <small class="text-muted d-block">تاریخ:</small>
                                    {{ $employee->appointment_date }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Last Duty -->
                    @if($employee->last_duty)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-history text-primary mr-2"></i>
                                <span>@lang('form.lastDuty'):</span>
                            </div>
                            <div class="detail-value">{{ $employee->last_duty }}</div>
                        </div>
                    @endif

                    <!-- Home/Hostel -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-home text-primary mr-2"></i>
                            <span>@lang('pages.hostel.hostel')/@lang('global.home'):</span>
                        </div>
                        <div class="detail-value">
                            @if($employee->hostel)
                                <span class="badge badge-info">@lang('pages.hostel.hostel')</span>
                                <div class="mt-1">
                                    <small class="d-block">شماره اتاق: {{ $employee->hostel->number }}</small>
                                    @if($employee->hostel->place && $employee->hostel->place->code == 'P01')
                                        <small class="d-block">سکشن: {{ $employee->hostel->section }}</small>
                                    @endif
                                    <small class="d-block text-muted">{{ $employee->hostel->place->name ?? '' }}</small>
                                </div>
                            @else
                                <span class="badge badge-secondary">@lang('global.home')</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Information Card -->
        <div class="col-lg-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-light d-flex align-items-center">
                    <div class="section-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 font-weight-bold">@lang('pages.employees.generalInfo')</h5>
                        <small class="text-muted">معلومات عمومی</small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Education -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-graduation-cap text-success mr-2"></i>
                            <span>@lang('form.education'):</span>
                        </div>
                        <div class="detail-value">
                            <span class="badge badge-light border">{{ $employee->education }}</span>
                        </div>
                    </div>

                    <!-- PRR/NPR Information -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-passport text-success mr-2"></i>
                            <span>PRR/NPR:</span>
                        </div>
                        <div class="detail-value">
                            <div class="d-flex">
                                <span class="mr-3">
                                    <small class="text-muted d-block">نمبر:</small>
                                    {{ $employee->prr_npr }}
                                </span>
                                <span>
                                    <small class="text-muted d-block">تاریخ:</small>
                                    {{ $employee->prr_date }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-map-marker-alt text-success mr-2"></i>
                            <span>آدرس ها:</span>
                        </div>
                        <div class="detail-value">
                            <div class="mb-2">
                                <small class="text-muted d-block">آدرس اصلی:</small>
                                {{ $employee->main_province }}, {{ $employee->main_district }}
                            </div>
                            <div>
                                <small class="text-muted d-block">آدرس فعلی:</small>
                                {{ $employee->current_province }}, {{ $employee->current_district }}
                            </div>
                        </div>
                    </div>

                    <!-- Introducer -->
                    @if($employee->introducer)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-user-friends text-success mr-2"></i>
                                <span>@lang('form.introducer'):</span>
                            </div>
                            <div class="detail-value">{{ $employee->introducer }}</div>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-user-check text-success mr-2"></i>
                            <span>@lang('form.status'):</span>
                        </div>
                        <div class="detail-value">
                            @if($employee->status == 0 && $employee->on_duty == 0)
                                <span class="badge badge-success">
                                    <i class="fas fa-user-check mr-1"></i>در اصل بست در حال انجام وظیفه
                                </span>
                            @elseif($employee->status == 0 && $employee->on_duty == 1)
                                <span class="badge badge-primary">
                                    <i class="fas fa-user-clock mr-1"></i>در بست خدمتی در حال انجام وظیفه
                                </span>
                            @elseif($employee->status == 1)
                                <span class="badge badge-secondary">
                                    <i class="fas fa-user-slash mr-1"></i>تقاعد نموده است
                                </span>
                            @elseif($employee->status == 2)
                                <span class="badge badge-danger">
                                    <i class="fas fa-user-times mr-1"></i>منفک گردیده است
                                </span>
                            @elseif($employee->status == 3)
                                <span class="badge badge-info">
                                    <i class="fas fa-exchange-alt mr-1"></i>تبدیل شده است
                                </span>
                            @elseif($employee->status == 4)
                                <span class="badge badge-warning">
                                    <i class="fas fa-user-lock mr-1"></i>معلق می باشد
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Place -->
                    @if($employee->position && $employee->position->place)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-building text-success mr-2"></i>
                                <span>موقعیت:</span>
                            </div>
                            <div class="detail-value">
                                {{ $employee->position->place->name }}
                                <small class="text-muted d-block">{{ $employee->position->place->custom_code }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- Leave Information -->
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-calendar-alt text-success mr-2"></i>
                            <span>اطلاعات رخصتی:</span>
                        </div>
                        <div class="detail-value">
                            <div class="d-flex">
                                <div class="text-center mr-3">
                                    <div class="font-weight-bold text-primary">{{ $employee->leaves->sum('days') ?? '0' }}</div>
                                    <small class="text-muted">روز رخصتی</small>
                                </div>
                                <div class="text-center">
                                    <div class="font-weight-bold text-primary">{{ $employee->leaves->count() ?? '0' }}</div>
                                    <small class="text-muted">دفعات رخصتی</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Position Information -->
                    @if($employee->position)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-briefcase text-success mr-2"></i>
                                <span>بست وظیفوی:</span>
                            </div>
                            <div class="detail-value">
                                <strong>{{ $employee->position->title }}</strong>
                                <div>
                                    <small class="text-muted">
                                        کد: {{ $employee->position_code->code ?? '--' }} |
                                        درجه: {{ $employee->position->position_number }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Duty Position -->
                    @if($employee->on_duty == 1)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-user-tag text-success mr-2"></i>
                                <span>بست خدمتی:</span>
                            </div>
                            <div class="detail-value">
                                <span class="badge badge-primary">{{ $employee->duty_position }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Extra Info -->
                    @if($employee->info)
                        <div class="detail-row">
                            <div class="detail-label">
                                <i class="fas fa-sticky-note text-success mr-2"></i>
                                <span>@lang('global.extraInfo'):</span>
                            </div>
                            <div class="detail-value">
                                <div class="bg-light p-2 rounded">{{ $employee->info }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- User Accounts Row -->
    <div class="row">
        <!-- BCD-MIS Account -->
        @if($employee->user)
            <div class="col-lg-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-header bg-light d-flex align-items-center">
                        <div class="section-icon bg-purple text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-laptop fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 font-weight-bold">حساب کاربری BCD-MIS</h5>
                            <small class="text-muted">معلومات سیستم مدیریت</small>
                        </div>
                        <span class="badge badge-{{ $employee->user->status == 1 ? 'success' : 'secondary' }} mr-2">
                            {{ $employee->user->status == 1 ? trans('global.active') : trans('global.inactive') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">شناسه کاربر</small>
                                    <strong>{{ $employee->user->id }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">نام کاربری</small>
                                    <code class="bg-light p-1 rounded">{{ $employee->user->username }}</code>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">تاریخ ایجاد</small>
                                    <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($employee->user->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if($employee->user->info)
                                    <div class="account-info-item">
                                        <small class="text-muted d-block">توضیحات اضافی</small>
                                        <div class="bg-light p-2 rounded mt-1">{{ $employee->user->info }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @can('user_mgmt')
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.users.show', encrypt($employee->user->id)) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>مشاهده حساب کاربری
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @endif

        <!-- Asycuda Account -->
        @if($employee->asycuda_user)
            <div class="col-lg-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-header bg-light d-flex align-items-center">
                        <div class="section-icon bg-orange text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-database fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 font-weight-bold">حساب کاربری اسیکودا</h5>
                            <small class="text-muted">معلومات سیستم گمرکی</small>
                        </div>
                        <span class="badge badge-{{ $employee->asycuda_user->status == 1 ? 'success' : 'secondary' }} mr-2">
                            {{ $employee->asycuda_user->status == 1 ? trans('global.active') : trans('global.inactive') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">شناسه کاربر</small>
                                    <strong>{{ $employee->asycuda_user->id }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">نام کاربری</small>
                                    <code class="bg-light p-1 rounded">{{ $employee->asycuda_user->username }}</code>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">کلمه عبور</small>
                                    <code class="bg-light p-1 rounded">{{ $employee->asycuda_user->password }}</code>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">نقش ها</small>
                                    <span class="badge badge-info">{{ $employee->asycuda_user->roles }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="account-info-item">
                                    <small class="text-muted d-block">تاریخ ایجاد</small>
                                    <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($employee->asycuda_user->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if($employee->asycuda_user->info)
                                    <div class="account-info-item">
                                        <small class="text-muted d-block">توضیحات اضافی</small>
                                        <div class="bg-light p-2 rounded mt-1">{{ $employee->asycuda_user->info }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @can('asycuda_view')
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.asycuda.users.show', $employee->asycuda_user->id) }}"
                                   target="_blank"
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>مشاهده حساب اسیکودا
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .employee-details-container {
        direction: rtl;
    }

    .info-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        transform: translateY(-2px);
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
        background-color: #f8f9fc !important;
    }

    .detail-row {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        flex: 0 0 40%;
        font-weight: 600;
        color: #5a5c69;
        display: flex;
        align-items: center;
    }

    .detail-value {
        flex: 1;
        color: #3a3b45;
        word-break: break-word;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .account-info-item {
        padding: 8px;
        border-radius: 6px;
        background-color: #f8f9fc;
        height: 100%;
    }

    .badge-pink {
        background-color: #e83e8c;
        color: white;
    }

    .badge-purple {
        background-color: #6f42c1;
        color: white;
    }

    .badge-orange {
        background-color: #fd7e14;
        color: white;
    }

    @media (max-width: 768px) {
        .detail-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-label {
            margin-bottom: 5px;
            flex: 0 0 100%;
        }

        .detail-value {
            flex: 0 0 100%;
            padding-right: 20px;
        }
    }
</style>
