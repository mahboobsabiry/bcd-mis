@extends('layouts.admin.master')
<!-- Title -->
@section('title', $document->position->title)

<!-- Page Content -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">{{ $document->position->title }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.index') }}">@lang('pages.positions.positions')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.show', $document->position->id) }}">مدیر عمومی سیستم</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.documents.index') }}">مکاتیب مدیریت عمومی سیستم</a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">{{ $document->position->title }}</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <div class="d-flex">
                    @if(auth()->user()->isAdmin() || auth()->user()->employee->position->title == $document->receiver)
                    @else
                        <!-- Delete -->
                        @can('docs_delete')
                            <div class="mr-2">
                                <!-- Delete -->
                                <a class="modal-effect btn btn-sm ripple btn-danger text-white"
                                   data-effect="effect-sign" data-toggle="modal"
                                   href="#delete_record{{ $document->id }}"
                                   title="@lang('global.delete')">
                                    @lang('global.delete')
                                    <i class="fe fe-trash"></i>
                                </a>

                                @include('admin.documents.delete')
                            </div>
                        @endcan

                        @can('docs_edit')
                            <div class="mr-2">
                                <!-- Edit -->
                                <a class="btn ripple bg-dark btn-sm text-white"
                                   href="{{ route('admin.documents.edit', $document->id) }}">
                                    @lang('global.edit')
                                    <i class="fe fe-edit"></i>
                                </a>
                            </div>
                        @endcan

                        @can('docs_create')
                            <div class="mr-2">
                                <!-- Add -->
                                <a class="btn ripple btn-primary btn-sm" href="{{ route('admin.documents.create') }}">
                                    @lang('global.new')
                                    <i class="fe fe-plus-circle"></i>
                                </a>
                            </div>
                        @endcan
                    @endif
                </div>
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
                                @if($document->position->num_of_pos == 1)
                                    <a href="{{ $document->position->employees->first()->image ?? asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                        <img alt="avatar" src="{{ $document->position->employees->first()->image ?? asset('assets/images/avatar-default.jpeg') }}">
                                    </a>
                                @else
                                    <a href="{{ asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                        <img alt="avatar" src="{{ asset('assets/images/avatar-default.jpeg') }}">
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="item-user pro-user">
                            <h4 class="pro-user-username text-dark mt-2 mb-0">
                                @if($document->position->num_of_pos == 1)
                                    <span>{{ $document->position->employees->first()->name ?? trans('global.empty') }} {{ $document->position->employees->first()->last_name ?? '' }}</span>
                                @else
                                    <span>{{ $document->position->title }}</span>
                                @endif
                            </h4>

                            <p class="pro-user-desc text-muted mb-1">{{ $document->position->title }}</p>
                            @if($document->position->position_number == 2 || $document->position->position_number == 3)
                            @else
                                <p class="pro-user-desc text-primary mb-1">({{ $document->position->place->name ?? '' }})</p>
                            @endif
                            <!-- Position Star -->
                            <p class="user-info-rating">
                                @for($i=1; $i<=$document->position->position_number; $i++)
                                    <a href="javascript:void(0);"><i class="fa fa-star text-warning"> </i></a>
                                @endfor
                            </p>
                            <!--/==/ End of Position Star -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Profile Main Info -->

                <!-- Contact Information -->
                @if($document->position->num_of_pos == 1)
                    <div class="card custom-card">
                        <div class="card-header custom-card-header">
                            <div>
                                <h6 class="card-title mb-0">
                                    @lang('pages.users.contactInfo')
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
                                            <a href="callto:{{ $document->position->employees->first()->phone ?? '' }}"
                                               class="ctd">{{ $document->position->employees->first()->phone ?? '--- ---- ---' }}</a>
                                            <a href="callto:{{ $document->position->employees->first()->phone2 ?? '' }}"
                                               class="ctd">{{ $document->position->employees->first()->phone2 ?? '' }}</a>
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
                                            <a href="mailto:{{ $document->position->employees->first()->email ?? '' }}"
                                               class="ctd">{{ $document->position->employees->first()->email ?? '----@---.--' }}</a>
                                        </div>
                                    </div>
                                </div>
                                <!--/==/ End of Email Address -->
                            </div>
                        </div>
                    </div>
                @endif
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
                                {{ $document->subject }}
                            </div>
                            <div class="col-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                <i class="fa fa-file"></i> مکتوب
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mr-1 ml-1">
                            <div class="{{ app()->getLocale() == 'en' ? 'pr-2' : 'pl-2' }}"><i class="far fa-clock"></i></div>
                            <div>
                                تاریخ ثبت
                                <br>
                                <p class="text-muted small">{{ \Morilog\Jalali\CalendarUtils::strftime('Y-m-d h:i a', strtotime($document->created_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Header Card -->

                <!-- Details Card -->
                <div class="card mb-2">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">@lang('global.details')</h4>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body" style="background-color: #F7F9FCFF">
                        <div class="row">
                            <!-- Account Information -->
                            <div class="col-lg col-xxl-5">
                                <h5 class="fw-semi-bold ls mb-3 text-uppercase font-weight-bold">معلومات مکتوب</h5>
                                <!-- ID -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>ID:</strong></p>
                                    </div>
                                    <div class="col">ID-{{ $document->id }}</div>
                                </div>

                                <!-- Type -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>نوع:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->type }}</div>
                                </div>

                                <!-- Subject -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>موضوع:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->subject }}</div>
                                </div>

                                <!-- Document Type -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>نوع مکتوب:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->doc_type }}</div>
                                </div>

                                <!-- Document Number -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>نمبر مکتوب:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->doc_number }}</div>
                                </div>

                                <!-- Description -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>معلومات اضافی:</strong></p>
                                    </div>
                                    <div class="col">
                                        <p class="fst-italic text-400 mb-1">{{ $document->info ?? '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Position Information -->

                            <!-- General Information -->
                            <div class="col-lg col-xxl-5 mt-4 mt-lg-0 offset-xxl-1">
                                <h5 class="fw-semi-bold ls mb-3 text-uppercase font-weight-bold">معلومات عمومی</h5>
                                <!-- Sender -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>مرسل:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->position->title }}</div>
                                </div>

                                <!-- Receiver -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong>مرسل الیه:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->receiver }}</div>
                                </div>

                                <!-- CC -->
                                <div class="row">
                                    <div class="col-5 col-sm-4">
                                        <p class="fw-semi-bold mb-1"><strong> کاپی ها به:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->cc }}</div>
                                </div>

                                <!-- Place -->
                                <div class="row">
                                    <div class="col-6 col-sm-5">
                                        <p class="fw-semi-bold mb-1"><strong>تاریخ مکتوب:</strong></p>
                                    </div>
                                    <div class="col">{{ $document->doc_date }}</div>
                                </div>

                                <!-- Files -->
                                <div class="row">
                                    <div class="col-4 col-sm-3">
                                        <p class="fw-semi-bold mb-1"><strong>اسناد:</strong></p>
                                    </div>
                                    <div class="col bd m-2 p-2 row">
                                        @foreach($document->files as $file)
                                            <div class="bg-white p-2 bd">
                                                <div class="img-thumbnail">
                                                    <a href="{{ asset('storage/documents/files/' . $file->path) }}" target="_blank">
                                                        <img src="{{ asset('storage/documents/files/' . $file->path) }}" alt="{{ $document->subject }}" style="width: 60px;">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of General Information -->
                        </div>
                    </div>
                </div>
                <!--/==/ End of Details Card -->
            </div>
        </div>
        <!--/==/ End of Row Content -->
    </div>
@endsection
<!--/==/ End of Page Content -->
