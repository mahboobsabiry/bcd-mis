@extends('layouts.admin.master')

<!-- Title -->
@section('title', $place->name . ' - ' . trans('admin.sidebar.places'))

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        .place-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .place-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .position-tree {
            border-left: 2px solid #e3e6f0;
            padding-left: 20px;
        }
        .position-item {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border: 1px solid #e3e6f0;
            border-radius: 6px;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }
        .activity-timeline:before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e6f0;
        }
        .activity-item {
            position: relative;
            margin-bottom: 20px;
        }
        .activity-item:before {
            content: '';
            position: absolute;
            left: -23px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
            border: 2px solid white;
            box-shadow: 0 0 0 3px #667eea;
        }
        .tab-content {
            padding-top: 20px;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid #667eea;
            font-weight: 600;
        }
    </style>
@endsection

<!-- Page Content -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">{{ $place->name }}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.places.index') }}">@lang('admin.sidebar.places')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $place->name }}</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="btn btn-list">
                <!-- Back Button -->
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary mr-2">
                    <i class="fe fe-arrow-left mr-1"></i> @lang('global.back')
                </a>

                <!-- Edit Button -->
                <a class="modal-effect btn btn-warning" data-effect="effect-sign"
                   data-toggle="modal" href="#editPlaceModal{{ $place->id }}">
                    <i class="fe fe-edit mr-1"></i> @lang('global.edit')
                </a>

                <!-- Status Toggle -->
                @if($place->status)
                    <form action="{{ route('admin.places.deactivate', $place->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger"
                                onclick="return confirm('آیا مطمئن هستید که می‌خواهید این موقعیت را غیرفعال کنید؟')">
                            <i class="fe fe-x-circle mr-1"></i> @lang('global.deactivate')
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.places.activate', $place->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fe fe-check-circle mr-1"></i> @lang('global.activate')
                        </button>
                    </form>
                @endif

                <!-- Quick Actions Dropdown -->
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="fe fe-more-horizontal mr-1"></i> @lang('global.moreActions')
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.office.positions.create', ['place_id' => $place->id]) }}">
                            <i class="fe fe-plus-circle mr-2"></i> @lang('pages.positions.addPosition')
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.office.hostels.create', ['place_id' => $place->id]) }}">
                            <i class="fe fe-home mr-2"></i> @lang('global.addHostel')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-info" href="#" onclick="printPage()">
                            <i class="fe fe-printer mr-2"></i> @lang('global.print')
                        </a>
                        <a class="dropdown-item text-success" href="#" id="exportData">
                            <i class="fe fe-download mr-2"></i> @lang('global.export')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="deletePlace({{ $place->id }}, '{{ $place->name }}')">
                            <i class="fe fe-trash-2 mr-2"></i> @lang('global.delete')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Place Header -->
        <div class="place-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3">{{ $place->name }}</h1>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="place-badge badge bg-white text-dark">
                            <i class="fe fe-hash mr-1"></i> {{ $place->code }}
                        </span>
                        @if($place->custom_code)
                            <span class="place-badge badge bg-light text-dark">
                                <i class="fe fe-tag mr-1"></i> {{ $place->custom_code }}
                            </span>
                        @endif
                        {!! $place->status_badge !!}
                        <span class="text-light">
                            <i class="fe fe-calendar mr-1"></i>
                            ایجاد شده در: {{ \Morilog\Jalali\CalendarUtils::strftime('Y/F/d', strtotime($place->created_at)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <div class="avatar avatar-xxl bg-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fe fe-map-pin tx-40 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Place Header -->

        <!-- Statistics Cards -->
        <div class="row row-sm mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card" style="border-left-color: #667eea;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('global.totalPositions')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $statistics['total_positions'] }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-briefcase tx-30" style="color: #667eea;"></i>
                            </div>
                        </div>
                        <a href="#positions-tab" class="d-block tx-12 tx-color-03 mg-t-10">
                            @lang('global.viewAll') <i class="fe fe-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card" style="border-left-color: #10b759;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('global.positionCodes')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $statistics['total_codes'] }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-hash tx-30" style="color: #10b759;"></i>
                            </div>
                        </div>
                        <div class="d-flex tx-12">
                            <span class="tx-success mr-2">
                                <i class="fe fe-user-check"></i> {{ $statistics['occupied_positions'] }}
                            </span>
                            <span class="tx-warning">
                                <i class="fe fe-user-x"></i> {{ $statistics['vacant_positions'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card" style="border-left-color: #f6993f;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('global.users')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $statistics['total_users'] }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-users tx-30" style="color: #f6993f;"></i>
                            </div>
                        </div>
                        <a href="#users-tab" class="d-block tx-12 tx-color-03 mg-t-10">
                            @lang('global.viewAll') <i class="fe fe-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card stat-card" style="border-left-color: #6cb2eb;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('global.hostels')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $statistics['total_hostels'] }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-home tx-30" style="color: #6cb2eb;"></i>
                            </div>
                        </div>
                        <a href="#hostels-tab" class="d-block tx-12 tx-color-03 mg-t-10">
                            @lang('global.viewAll') <i class="fe fe-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Statistics Cards -->

        <!-- Main Content Tabs -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="placeTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview">
                                    <i class="fe fe-info mr-1"></i> @lang('global.overview')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="positions-tab" data-toggle="tab" href="#positions">
                                    <i class="fe fe-briefcase mr-1"></i> @lang('global.positions')
                                    <span class="badge badge-primary ml-1">{{ $statistics['total_positions'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="users-tab" data-toggle="tab" href="#users">
                                    <i class="fe fe-users mr-1"></i> @lang('global.users')
                                    <span class="badge badge-info ml-1">{{ $statistics['total_users'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hostels-tab" data-toggle="tab" href="#hostels">
                                    <i class="fe fe-home mr-1"></i> @lang('global.hostels')
                                    <span class="badge badge-success ml-1">{{ $statistics['total_hostels'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="chart-tab" data-toggle="tab" href="#chart">
                                    <i class="fe fe-bar-chart mr-1"></i> @lang('global.charts')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity">
                                    <i class="fe fe-activity mr-1"></i> @lang('global.activity')
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="placeTabContent">

                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <!-- Information Box -->
                                        <div class="info-box mb-4">
                                            <h5 class="mb-3">@lang('global.information')</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th width="40%">@lang('form.name'):</th>
                                                            <td>{{ $place->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>@lang('form.code'):</th>
                                                            <td>
                                                                <span class="badge badge-primary">{{ $place->code }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>@lang('form.status'):</th>
                                                            <td>{!! $place->status_badge !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>@lang('global.createdDate'):</th>
                                                            <td>
                                                                {{ \Morilog\Jalali\CalendarUtils::strftime('Y/F/d', strtotime($place->created_at)) }}
                                                                <small class="text-muted d-block">
                                                                    ({{ $place->created_at->diffForHumans() }})
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        @if($place->custom_code)
                                                            <tr>
                                                                <th width="40%">@lang('form.customCode'):</th>
                                                                <td>
                                                                    <span class="badge badge-secondary">{{ $place->custom_code }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <th>@lang('global.lastUpdated'):</th>
                                                            <td>
                                                                {{ \Morilog\Jalali\CalendarUtils::strftime('Y/F/d', strtotime($place->updated_at)) }}
                                                                <small class="text-muted d-block">
                                                                    ({{ $place->updated_at->diffForHumans() }})
                                                                </small>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>@lang('global.positionsRatio'):</th>
                                                            <td>
                                                                <div class="progress" style="height: 8px;">
                                                                    @php
                                                                        $occupiedPercent = $statistics['total_codes'] > 0
                                                                            ? round(($statistics['occupied_positions'] / $statistics['total_codes']) * 100, 1)
                                                                            : 0;
                                                                    @endphp
                                                                    <div class="progress-bar bg-success" style="width: {{ $occupiedPercent }}%"></div>
                                                                </div>
                                                                <small class="text-muted">
                                                                    {{ $occupiedPercent }}% @lang('global.occupied')
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Additional Information -->
                                            @if($place->info)
                                                <div class="mt-4">
                                                    <h6 class="mb-2">@lang('form.extraInfo'):</h6>
                                                    <div class="p-3 bg-light rounded">
                                                        {!! $place->info !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Quick Links -->
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ route('admin.office.positions.create', ['place_id' => $place->id]) }}"
                                                   class="card card-body text-center hover-shadow">
                                                    <i class="fe fe-plus-circle tx-30 text-primary mb-2"></i>
                                                    <h6>@lang('pages.positions.addPosition')</h6>
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ route('admin.users.create', ['place_id' => $place->id]) }}"
                                                   class="card card-body text-center hover-shadow">
                                                    <i class="fe fe-user-plus tx-30 text-success mb-2"></i>
                                                    <h6>@lang('global.addUser')</h6>
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ route('admin.office.hostels.create', ['place_id' => $place->id]) }}"
                                                   class="card card-body text-center hover-shadow">
                                                    <i class="fe fe-home tx-30 text-warning mb-2"></i>
                                                    <h6>@lang('global.addHostel')</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <!-- Position Distribution Chart -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">@lang('global.positionDistribution')</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="positionChart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Recent Activity -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h6 class="mb-0">@lang('global.recentActivity')</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="activity-timeline">
                                                    <!-- This would be populated with actual activity logs -->
                                                    <div class="activity-item">
                                                        <small class="text-muted">2 ساعت پیش</small>
                                                        <p class="mb-1">یک منصب جدید اضافه شد</p>
                                                    </div>
                                                    <div class="activity-item">
                                                        <small class="text-muted">1 روز پیش</small>
                                                        <p class="mb-1">کاربر احمدی به موقعیت اضافه شد</p>
                                                    </div>
                                                    <div class="activity-item">
                                                        <small class="text-muted">3 روز پیش</small>
                                                        <p class="mb-1">اطلاعات موقعیت به‌روزرسانی شد</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Overview Tab -->

                            <!-- Positions Tab -->
                            <div class="tab-pane fade" id="positions" role="tabpanel">
                                @if($place->positions->count() > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5>@lang('global.positionsList')</h5>
                                        <a href="{{ route('admin.office.positions.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fe fe-plus-circle mr-1"></i> @lang('pages.positions.addPosition')
                                        </a>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover" id="positionsTable">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('form.title')</th>
                                                <th>@lang('pages.positions.positionNumber')</th>
                                                <th>@lang('global.codes')</th>
                                                <th>@lang('global.occupied')</th>
                                                <th>@lang('global.vacant')</th>
                                                <th>@lang('global.actions')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($place->positions as $index => $position)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.office.positions.show', $position->id) }}">
                                                            {{ $position->title }}
                                                        </a>
                                                        @if($position->parent)
                                                            <br>
                                                            <small class="text-muted">
                                                                تحت: {{ $position->parent->title }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $position->position_number }}</td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ $position->codes->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            {{ $position->codes->where('employee')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">
                                                            {{ $position->codes->whereDoesntHave('employee')->count() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.office.positions.show', $position->id) }}"
                                                           class="btn btn-sm btn-info" title="@lang('global.view')">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.office.positions.edit', $position->id) }}"
                                                           class="btn btn-sm btn-warning" title="@lang('global.edit')">
                                                            <i class="fe fe-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fe fe-briefcase tx-60 text-muted mb-3"></i>
                                        <h5>@lang('global.noPositionsFound')</h5>
                                        <p class="text-muted mb-4">@lang('global.noPositionsDescription')</p>
                                        <a href="{{ route('admin.office.positions.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary">
                                            <i class="fe fe-plus-circle mr-1"></i> @lang('pages.positions.addFirstPosition')
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <!--/==/ End of Positions Tab -->

                            <!-- Users Tab -->
                            <div class="tab-pane fade" id="users" role="tabpanel">
                                @if($place->users->count() > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5>@lang('global.usersList')</h5>
                                        <a href="{{ route('admin.users.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fe fe-user-plus mr-1"></i> @lang('global.addUser')
                                        </a>
                                    </div>

                                    <div class="row">
                                        @foreach($place->users as $user)
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mr-3">
                                                                <i class="fe fe-user"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                                <small class="text-muted">{{ $user->email }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="fe fe-shield mr-1"></i> {{ $user->getRoleNames()->first() ?? 'بدون نقش' }}
                                                            </small>
                                                            <small class="text-muted">
                                                                <i class="fe fe-calendar mr-1"></i>
                                                                {{ $user->created_at->format('Y-m-d') }}
                                                            </small>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                               class="btn btn-sm btn-outline-info">
                                                                @lang('global.view')
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fe fe-users tx-60 text-muted mb-3"></i>
                                        <h5>@lang('global.noUsersFound')</h5>
                                        <p class="text-muted mb-4">@lang('global.noUsersDescription')</p>
                                        <a href="{{ route('admin.users.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary">
                                            <i class="fe fe-user-plus mr-1"></i> @lang('global.addFirstUser')
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <!--/==/ End of Users Tab -->

                            <!-- Hostels Tab -->
                            <div class="tab-pane fade" id="hostels" role="tabpanel">
                                @if($place->hostels->count() > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5>@lang('global.hostelsList')</h5>
                                        <a href="{{ route('admin.office.hostels.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fe fe-plus-circle mr-1"></i> @lang('global.addHostel')
                                        </a>
                                    </div>

                                    <div class="row">
                                        @foreach($place->hostels as $hostel)
                                            <div class="col-md-6 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $hostel->name }}</h6>
                                                        <p class="card-text text-muted">{{ $hostel->address }}</p>
                                                        <div class="d-flex justify-content-between">
                                                        <span class="badge badge-info">
                                                            <i class="fe fe-users mr-1"></i> {{ $hostel->capacity }} ظرفیت
                                                        </span>
                                                            <span class="badge badge-{{ $hostel->status ? 'success' : 'secondary' }}">
                                                            {{ $hostel->status ? 'فعال' : 'غیرفعال' }}
                                                        </span>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="{{ route('admin.office.hostels.show', $hostel->id) }}"
                                                               class="btn btn-sm btn-outline-info">
                                                                @lang('global.view')
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fe fe-home tx-60 text-muted mb-3"></i>
                                        <h5>@lang('global.noHostelsFound')</h5>
                                        <p class="text-muted mb-4">@lang('global.noHostelsDescription')</p>
                                        <a href="{{ route('admin.office.hostels.create', ['place_id' => $place->id]) }}"
                                           class="btn btn-primary">
                                            <i class="fe fe-plus-circle mr-1"></i> @lang('global.addFirstHostel')
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <!--/==/ End of Hostels Tab -->

                            <!-- Charts Tab -->
                            <div class="tab-pane fade" id="chart" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">@lang('global.positionStatus')</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="statusChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">@lang('global.monthlyActivity')</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="chart-container">
                                                    <canvas id="activityChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Charts Tab -->

                            <!-- Activity Tab -->
                            <div class="tab-pane fade" id="activity" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-4">@lang('global.recentActivity')</h5>
                                        <div class="activity-timeline">
                                            <!-- Placeholder for activity logs -->
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">امروز - 14:30</small>
                                                    <span class="badge badge-info">بروزرسانی</span>
                                                </div>
                                                <p class="mb-1">اطلاعات موقعیت ویرایش شد</p>
                                                <small class="text-muted">توسط: مدیر سیستم</small>
                                            </div>
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">دیروز - 10:15</small>
                                                    <span class="badge badge-success">ایجاد</span>
                                                </div>
                                                <p class="mb-1">منصب جدید "کارشناس مالی" اضافه شد</p>
                                                <small class="text-muted">توسط: احمد محمدی</small>
                                            </div>
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">3 روز پیش - 09:45</small>
                                                    <span class="badge badge-warning">تغییر وضعیت</span>
                                                </div>
                                                <p class="mb-1">کاربر "رضا کریمی" به موقعیت اضافه شد</p>
                                                <small class="text-muted">توسط: مدیر منابع انسانی</small>
                                            </div>
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">1 هفته پیش - 16:20</small>
                                                    <span class="badge badge-primary">ایجاد</span>
                                                </div>
                                                <p class="mb-1">موقعیت ایجاد شد</p>
                                                <small class="text-muted">توسط: مدیر سیستم</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/==/ End of Activity Tab -->

                        </div>
                        <!--/==/ End of Tab Content -->
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Main Content Tabs -->
    </div>

    <!-- Include Edit Modal -->
    @include('admin.places.modals.edit', ['place' => $place])
@endsection

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable for positions
            if ($('#positionsTable').length) {
                $('#positionsTable').DataTable({
                    language: {
                        url: '{{ asset("assets/lang/" . app()->getLocale() . ".json") }}'
                    },
                    pageLength: 10,
                    responsive: true
                });
            }

            // Initialize charts
            initializeCharts();

            // Tab activation based on URL hash
            if (window.location.hash) {
                const hash = window.location.hash.replace('#', '');
                $(`a[href="#${hash}"]`).tab('show');
            }

            // Update URL hash when tab changes
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                const hash = $(e.target).attr('href');
                window.location.hash = hash;
            });
        });

        // Initialize Charts
        function initializeCharts() {
            // Position Status Chart
            const statusCtx = document.getElementById('positionChart')?.getContext('2d');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            '@lang("global.occupied")',
                            '@lang("global.vacant")'
                        ],
                        datasets: [{
                            data: [
                                {{ $statistics['occupied_positions'] }},
                                {{ $statistics['vacant_positions'] }}
                            ],
                            backgroundColor: [
                                '#10b759',
                                '#f6993f'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Status Distribution Chart
            const statusChartCtx = document.getElementById('statusChart')?.getContext('2d');
            if (statusChartCtx) {
                new Chart(statusChartCtx, {
                    type: 'pie',
                    data: {
                        labels: [
                            '@lang("global.positions")',
                            '@lang("global.users")',
                            '@lang("global.hostels")'
                        ],
                        datasets: [{
                            data: [
                                {{ $statistics['total_positions'] }},
                                {{ $statistics['total_users'] }},
                                {{ $statistics['total_hostels'] }}
                            ],
                            backgroundColor: [
                                '#667eea',
                                '#f6993f',
                                '#6cb2eb'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Activity Chart
            const activityCtx = document.getElementById('activityChart')?.getContext('2d');
            if (activityCtx) {
                new Chart(activityCtx, {
                    type: 'line',
                    data: {
                        labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'],
                        datasets: [{
                            label: '@lang("global.activities")',
                            data: [12, 19, 3, 5, 2, 3],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        // Print Page
        function printPage() {
            window.print();
        }

        // Export Data
        document.getElementById('exportData')?.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '@lang("global.exportOptions")',
                text: '@lang("global.selectExportFormat")',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'PDF',
                cancelButtonText: 'Excel',
                showDenyButton: true,
                denyButtonText: '@lang("global.cancel")'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Export as PDF
                    window.open('{{ route("admin.places.export", ["place" => $place->id, "format" => "pdf"]) }}', '_blank');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Export as Excel
                    window.open('{{ route("admin.places.export", ["place" => $place->id, "format" => "excel"]) }}', '_blank');
                }
            });
        });

        // Delete Place
        function deletePlace(placeId, placeName) {
            Swal.fire({
                title: '@lang("global.deleteConfirm")',
                html: `@lang("global.deletePlaceWarning", ["name" => "${placeName}"])<br><br>
                       <div class="alert alert-warning text-left">
                           <small>
                               <i class="fe fe-alert-triangle mr-1"></i>
                               @lang("global.deletePlaceConsequences", ["count" => "{{ $statistics['total_positions'] }}"])
                </small>
            </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '@lang("global.yesDelete")',
                cancelButtonText: '@lang("global.cancel")',
                showDenyButton: true,
                denyButtonText: '@lang("global.deactivateInstead")'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete request
                    fetch(`{{ url('admin/places') }}/${placeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || !data.error) {
                                Swal.fire(
                                    '@lang("global.deleted")',
                                    '@lang("global.deleteSuccess")',
                                    'success'
                                ).then(() => {
                                    window.location.href = '{{ route("admin.places.index") }}';
                                });
                            } else {
                                Swal.fire(
                                    '@lang("global.error")',
                                    data.message || '@lang("global.deleteError")',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                '@lang("global.error")',
                                '@lang("global.deleteError")',
                                'error'
                            );
                        });
                } else if (result.isDenied) {
                    // Deactivate instead
                    fetch(`{{ url('admin/places') }}/${placeId}/deactivate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '@lang("global.deactivated")',
                                    '@lang("global.deactivateSuccess")',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    '@lang("global.error")',
                                    data.message || '@lang("global.deactivateError")',
                                    'error'
                                );
                            }
                        });
                }
            });
        }

        // Copy place info
        function copyPlaceInfo() {
            const textToCopy = `{{ $place->name }} ({{ $place->code }})`;

            navigator.clipboard.writeText(textToCopy).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: '@lang("global.copied")',
                    text: '@lang("global.copiedToClipboard")',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(function(err) {
                Swal.fire({
                    icon: 'error',
                    title: '@lang("global.error")',
                    text: '@lang("global.copyFailed")'
                });
            });
        }
    </script>
@endsection
