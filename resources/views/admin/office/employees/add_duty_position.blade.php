@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'ثبت بست خدمتی کارمند')
<!-- Extra Styles -->
@section('extra_css')

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.employees.show', $employee->id) }}">@lang('pages.employees.employeeInfo')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">ثبت بست خدمتی</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">

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
                                     src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}">
                            </div>
                        </div>

                        <!-- Main Info -->
                        <div class="item-user pro-user">
                            <h4 class="pro-user-username text-dark mt-2 mb-0">
                                <span>{{ $employee->name }} {{ $employee->last_name }}</span>
                            </h4>

                            <!-- Position -->
                            @can('office_position_view')
                                <a href="{{ route('admin.office.positions.show', $employee->position->id) }}" target="_blank" class="pro-user-desc mb-1">{{ $employee->position->title ?? '' }}</a>
                            @else
                                <p class="pro-user-desc text-muted mb-1">{{ $employee->position->title ?? '' }}</p>
                            @endcan
                            @if($employee->on_duty == 1)
                                <p class="pro-user-desc text-muted mb-1">{{ $employee->duty_position ?? '' }}</p>
                            @endif

                            @if($employee->position->position_number == 2 || $employee->position->position_number == 3)
                            @else
                                <p class="pro-user-desc text-primary mb-1">({{ $employee->position->type ?? '' }})</p>
                            @endif
                            <!-- Employee Star -->
                            @if($employee->position)
                                <p class="user-info-rating">
                                    @for($i=1; $i<=$employee->position->position_number; $i++)
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
                                اطلاعات لازم
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="main-profile-contact-list main-profile-work-list">
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
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="card custom-card main-content-body-profile">
                    <!-- Card Body -->
                    <div class="card-body tab-content h-100">
                        <!-- Success Message -->
                        @include('admin.inc.alerts')

                        <!-- Header -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="font-weight-bold">ثبت بست خدمتی کارمند</div>
                            </div>
                            <div class="col-md-6 text-left">

                            </div>
                        </div>

                        <!-- Form -->
                        <form method="post" action="{{ route('admin.office.employees.add_duty_pos', $employee->id) }}" class="background_form" enctype="multipart/form-data">
                            @csrf
                            <div class="">
                                <!-- Employee && Document Number -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Duty Position -->
                                        <div class="form-group @error('position') has-danger @enderror">
                                            <p class="mb-2">@lang('form.dutyPosition'): <span class="tx-danger">*</span></p>
                                            <select class="form-control select2" name="position" id="position">
                                                <option value="">@lang('form.chooseOne')</option>
                                                @foreach(\App\Models\Office\Position::all()->except($employee->position->id) as $position)
                                                    <option value="{{ $position->title }}">{{ $position->title }}</option>
                                                @endforeach
                                            </select>

                                            @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Duty Document Number -->
                                        <div class="form-group @error('doc_number') has-danger @enderror">
                                            <p class="mb-2">@lang('form.dutyDocNumber'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="doc_number" class="form-control @error('doc_number') form-control-danger @enderror" name="doc_number" value="{{ old('doc_number') }}" required>

                                            @error('doc_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!--/==/ End of Employee && Document Number -->

                                <!-- Start Duty && Duty Document Number -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Start Duty Date -->
                                        <div class="form-group @error('start_date') has-danger @enderror">
                                            <p class="mb-2">@lang('form.startDuty'): <span class="tx-danger">*</span></p>
                                            <input data-jdp data-jdp-max-date="today" type="text" id="start_date" class="form-control @error('start_date') form-control-danger @enderror" name="start_date" value="{{ old('start_date') }}" required>

                                            @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Info -->
                                        <div class="form-group @error('info') has-danger @enderror">
                                            <p class="mb-2">@lang('global.extraInfo'):</p>
                                            <textarea class="form-control" name="info" id="info">{{ old('info') }}</textarea>

                                            @error('info')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!--/==/ End of Info -->

                                <!-- File -->
                                <div class="form-group @error('document') has-danger @enderror">
                                    <p class="mb-2">اسکن مکتوب: </p>
                                    <input type="file" id="document" class="form-control @error('document') form-control-danger @enderror" name="document">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn ripple btn-primary" type="submit">@lang('global.save')</button>
                            </div>
                        </form>
                        <!--/==/ End of Form -->
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Row Content -->
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')

@endsection
<!--/==/ End of Extra Scripts -->
