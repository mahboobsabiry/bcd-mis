@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'ثبت سوابق حساب کاربری سیستم اسیکودا')
<!-- Extra Styles -->
@section('extra_css')
    <!---Fileupload css-->
    <link href="{{ asset('backend/assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet">
    <!---Fancy uploader css-->
    <link href="{{ asset('backend/assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet">
    <!--Sumoselect css-->
    <link href="{{ asset('backend/assets/plugins/sumoselect/sumoselect.css') }}" rel="stylesheet">

    <!---Datetimepicker css-->
    <link href="{{ asset('backend/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">
@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">ثبت سوابق حساب کاربری سیستم اسیکودا</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    @can('office_employee_view')
                        <li class="breadcrumb-item"><a href="{{ route('admin.office.employees.index') }}">@lang('admin.sidebar.employees')</a></li>
                    @else
                        <li class="breadcrumb-item">@lang('admin.sidebar.employees')</li>
                    @endcan
                    <li class="breadcrumb-item"><a href="{{ route('admin.asycuda.users.index') }}">حسابات کاربری سیستم اسیکودا</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.asycuda.users.show', $asycuda_user->id) }}">حساب کاربری سیستم اسیکودا</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ثبت سابقه حساب کاربری ({{ $asycuda_user->employee->name }} {{ $asycuda_user->employee->last_name }})</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Back -->
                <a class="btn btn-orange btn-sm btn-with-icon" href="{{ url()->previous() }}">
                    @lang('global.back')
                    <i class="fe fe-arrow-left"></i>
                </a>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Row -->
        <div class="row">
            <!-- Short Part -->
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

            <!-- Large Part -->
            <div class="col-lg-9 col-md-12">
                <!-- Errors Message -->
                @include('admin.inc.alerts')

                <!-- Card -->
                <div class="card">
                    <!-- Form Title -->
                    <div class="card-header">
                        <h6 class="card-title mb-1">ثبت سوابق حساب کاربری سیستم اسیکودا ({{ $asycuda_user->employee->name }} {{ $asycuda_user->employee->last_name }})</h6>
                        <p class="text-muted card-sub-title">ثبت سوابق فعالیت حساب کاربری سیستم اسیکودا کارمندان گمرک بلخ اینجا انجام میشود.</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- Form -->
                        <form method="post" action="{{ route('admin.asycuda.users.store_user_resume', $asycuda_user->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Position -->
                                    <div class="form-group @error('position') has-danger @enderror">
                                        <p class="mb-2">بست: <span class="tx-danger">*</span></p>
                                        <input type="text" id="position" class="form-control @error('position') form-control-danger @enderror" name="position" @if($asycuda_user->employee->on_duty == 0) value="{{ $asycuda_user->employee->position->title ?? old('position') }}" @else value="{{ $asycuda_user->employee->duty_position ?? old('position') }}" @endif required>

                                        @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Position Type -->
                                    <div class="form-group @error('position_type') has-danger @enderror">
                                        <p class="mb-2">نوع بست: <span class="tx-danger">*</span></p>
                                        <select name="position_type" class="form-control">
                                            <option value="0" {{ $asycuda_user->employee->on_duty == 0 ? 'selected' : '' }}>اصل بست</option>
                                            <option value="1" {{ $asycuda_user->employee->on_duty == 1 ? 'selected' : '' }}>خدمتی</option>
                                        </select>

                                        @error('position_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- User -->
                                    <div class="form-group @error('username') has-danger @enderror">
                                        <p class="mb-2">نام کاربری: <span class="tx-danger">*</span></p>
                                        <input type="text" id="username" class="form-control @error('username') form-control-danger @enderror" name="username" value="{{ $asycuda_user->user ?? old('username') }}" required>

                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group @error('password') has-danger @enderror">
                                        <p class="mb-2">رمز عبور: <span class="tx-danger">*</span></p>
                                        <input type="text" id="password" class="form-control @error('password') form-control-danger @enderror" name="password" value="{{ $asycuda_user->password ?? old('password') }}" required>

                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- User Roles -->
                                    <div class="form-group @error('user_roles') has-danger @enderror">
                                        <p class="mb-2">صلاحیت های حساب کاربری: <span class="tx-danger">*</span></p>
                                        <input type="text" id="user_roles" class="form-control @error('user_roles') form-control-danger @enderror" name="user_roles" value="{{ $asycuda_user->roles ?? old('user_roles') }}" required>

                                        @error('user_roles')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- User Status -->
                                    <div class="form-group @error('user_status') has-danger @enderror">
                                        <p class="mb-2">وضعیت حساب کاربری: <span class="tx-danger">*</span></p>
                                        <select name="user_status" class="form-control">
                                            <option value="1" {{ $asycuda_user->status == 1 ? 'selected' : '' }}>فعال</option>
                                            <option value="0" {{ $asycuda_user->status == 0 ? 'selected' : '' }}>غیر فعال</option>
                                        </select>

                                        @error('user_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Document Number -->
                                    <div class="form-group @error('doc_number') has-danger @enderror">
                                        <p class="mb-2">نمبر مکتوب:</p>
                                        <input type="text" id="doc_number" class="form-control @error('doc_number') form-control-danger @enderror" name="doc_number" value="{{ old('doc_number') }}">

                                        @error('doc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Document Date -->
                                    <div class="form-group @error('doc_date') has-danger @enderror">
                                        <p class="mb-2">تاریخ مکتوب:</p>
                                        <input data-jdp data-jdp-max-date="today" type="text" id="doc_date" class="form-control @error('doc_date') form-control-danger @enderror" name="doc_date" value="{{ old('doc_date') }}">

                                        @error('doc_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Photo -->
                                    <div class="form-group @error('photo') has-danger @enderror">
                                        <p class="mb-2">مکتوب:</p>
                                        <input type="file" class="dropify" name="photo" accept="image/*" data-height="200" />
                                        @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--/==/ End of Photo -->

                                    <!-- Info -->
                                    <div class="form-group @error('info') has-danger @enderror">
                                        <p class="mb-2">@lang('global.extraInfo'):</p>
                                        <textarea class="form-control" name="info">{{ old('info') }}</textarea>

                                        @error('info')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group float-left">
                                        <button class="btn ripple btn-primary rounded-2" type="submit">@lang('global.save')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/==/ End of Form -->
                    </div>
                    <!--/==/ End of Card Body -->
                </div>
                <!--/==/ End of Card -->
            </div>
        </div>
        <!--/==/ End of Main Row -->
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <!--Fileuploads js-->
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Fancy uploader js-->
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
