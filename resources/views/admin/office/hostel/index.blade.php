@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('pages.hostel.hostel'))

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Chart.js -->
    <link href="{{ asset('backend/assets/plugins/chart.js/Chart.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert2.css') }}">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6571ff;
            --success-color: #05a34a;
            --warning-color: #ff9800;
            --danger-color: #ff4961;
            --info-color: #1e9ff2;
        }

        .hostel-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .hostel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .progress-thin {
            height: 6px;
            border-radius: 3px;
        }
        .capacity-badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
        }
        .employee-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .employee-avatar:hover {
            transform: scale(1.1);
            transition: transform 0.2s;
        }
        .room-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.1rem;
            color: var(--primary-color);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .filter-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .quick-filter {
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .quick-filter:hover {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .quick-filter.active {
            background: white;
            box-shadow: 0 2px 8px rgba(101, 113, 255, 0.2);
            border-left: 3px solid var(--primary-color);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .chart-container {
            position: relative;
            height: 200px;
        }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .action-buttons .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.hostel.hostel')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.hostel.hostel')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Quick Stats -->
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="fe fe-bar-chart-2 mr-1"></i> @lang('global.quickStats')
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">@lang('global.statistics')</div>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.totalHostels'):</span>
                                <strong>{{ $statistics['total_hostels'] }}</strong>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.totalCapacity'):</span>
                                <strong>{{ $statistics['total_capacity'] }}</strong>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.occupied'):</span>
                                <strong>{{ $statistics['total_occupied'] }}</strong>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.available'):</span>
                                <strong>{{ $statistics['available_capacity'] }}</strong>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.occupancyRate'):</span>
                                <strong>{{ $statistics['occupancy_rate'] }}%</strong>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- View Toggle -->
                <div class="btn-group mr-2" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn">
                        <i class="fe fe-list"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="cardViewBtn">
                        <i class="fe fe-grid"></i>
                    </button>
                </div>

                <!-- Export Button -->
                <button type="button" class="btn btn-success mr-2" id="exportBtn">
                    <i class="fe fe-download mr-1"></i> @lang('global.export')
                </button>

                <!-- Add New Button -->
                @can('office_hostel_create')
                    <a class="btn ripple btn-primary" href="{{ route('admin.office.hostel.create') }}">
                        <i class="fe fe-plus-circle mr-1"></i> @lang('global.new')
                    </a>
                @endcan
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Statistics Cards -->
        <div class="row row-sm mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card hostel-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">@lang('global.totalHostels')</h6>
                                <h4 class="mb-0">{{ $statistics['total_hostels'] }}</h4>
                            </div>
                            <div class="stat-icon bg-light-primary">
                                <i class="fe fe-home text-primary"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fe fe-map-pin mr-1"></i>
                                {{ $places->count() }} @lang('global.locations')
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card hostel-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">@lang('global.totalCapacity')</h6>
                                <h4 class="mb-0">{{ $statistics['total_capacity'] }}</h4>
                            </div>
                            <div class="stat-icon bg-light-success">
                                <i class="fe fe-users text-success"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-success">
                                {{ $statistics['total_occupied'] }} @lang('global.occupied')
                            </small>
                            <span class="text-muted mx-1">•</span>
                            <small class="text-info">
                                {{ $statistics['available_capacity'] }} @lang('global.available')
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card hostel-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">@lang('global.occupancyRate')</h6>
                                <h4 class="mb-0">{{ $statistics['occupancy_rate'] }}%</h4>
                            </div>
                            <div class="stat-icon bg-light-info">
                                <i class="fe fe-percent text-info"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="progress progress-thin">
                                <div class="progress-bar bg-info" style="width: {{ $statistics['occupancy_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card hostel-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">@lang('global.avgOccupancy')</h6>
                                <h4 class="mb-0">
                                    @php
                                        $avg = $statistics['total_hostels'] > 0 ?
                                            round($statistics['total_occupied'] / $statistics['total_hostels'], 1) : 0;
                                    @endphp
                                    {{ $avg }}
                                </h4>
                            </div>
                            <div class="stat-icon bg-light-warning">
                                <i class="fe fe-trending-up text-warning"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                @lang('global.perHostel')
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Statistics Cards -->

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('global.location')</label>
                                    <select class="form-control" id="filterPlace">
                                        <option value="">@lang('global.allLocations')</option>
                                        @foreach($places as $place)
                                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('pages.hostel.section')</label>
                                    <select class="form-control" id="filterSection">
                                        <option value="">@lang('global.all')</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('global.status')</label>
                                    <select class="form-control" id="filterStatus">
                                        <option value="">@lang('global.all')</option>
                                        <option value="available">@lang('global.available')</option>
                                        <option value="full">@lang('global.full')</option>
                                        <option value="partial">@lang('global.partial')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('global.search')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                               placeholder="@lang('global.searchHostels')...">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fe fe-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="text-muted mr-2">@lang('global.quickFilters'):</span>
                                    <span class="quick-filter active" data-filter="all">
                                        @lang('global.all') ({{ $hostels->total() }})
                                    </span>
                                    <span class="quick-filter" data-filter="available">
                                        @lang('global.available') ({{ $statistics['available_capacity'] }})
                                    </span>
                                    <span class="quick-filter" data-filter="full">
                                        @lang('global.full') ({{ $statistics['total_occupied'] }})
                                    </span>
                                    <span class="quick-filter" data-filter="recent">
                                        @lang('global.recent')
                                    </span>
                                    <span class="quick-filter" data-filter="empty">
                                        @lang('global.empty')
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Filter Section -->

        <!-- Card View (Hidden by default) -->
        <div class="row" id="cardView" style="display: none;">
            <div class="col-lg-12">
                @if($hostels->count() > 0)
                    <div class="row">
                        @foreach($hostels as $hostel)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="card hostel-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.office.hostel.show', $hostel->id) }}" class="text-dark">
                                                        اتاق
                                                        <span class="room-number">{{ $hostel->number }}</span>
                                                        @if($hostel->section)
                                                            <span class="text-muted">({{ $hostel->section }})</span>
                                                        @endif
                                                    </a>
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted mr-2">
                                                        <i class="fe fe-map-pin mr-1"></i>
                                                        {{ $hostel->place->name ?? '' }}
                                                    </small>
                                                    <span class="badge badge-light">
                                                    {{ $hostel->capacity }} @lang('global.capacity')
                                                </span>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fe fe-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.office.hostel.show', $hostel->id) }}">
                                                        <i class="fe fe-eye mr-2"></i> @lang('global.view')
                                                    </a>
                                                    @can('office_hostel_edit')
                                                        <a class="dropdown-item" href="{{ route('admin.office.hostel.edit', $hostel->id) }}">
                                                            <i class="fe fe-edit mr-2"></i> @lang('global.edit')
                                                        </a>
                                                    @endcan
                                                    @can('office_hostel_delete')
                                                        <a class="dropdown-item text-danger modal-effect" data-effect="effect-sign"
                                                           data-toggle="modal" href="#deleteHostelModal{{ $hostel->id }}">
                                                            <i class="fe fe-trash-2 mr-2"></i> @lang('global.delete')
                                                        </a>
                                                    @endcan
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" onclick="assignEmployees({{ $hostel->id }})">
                                                        <i class="fe fe-user-plus mr-2"></i> @lang('global.assignEmployees')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Occupancy Progress -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">
                                                    @lang('global.occupancy')
                                                </small>
                                                <small class="text-muted">
                                                    {{ $hostel->employees_count }}/{{ $hostel->capacity }}
                                                </small>
                                            </div>
                                            <div class="progress progress-thin">
                                                @php
                                                    $occupancyPercent = $hostel->capacity > 0 ?
                                                        round(($hostel->employees_count / $hostel->capacity) * 100, 0) : 0;
                                                @endphp
                                                <div class="progress-bar
                                                @if($occupancyPercent >= 90) bg-danger
                                                @elseif($occupancyPercent >= 70) bg-warning
                                                @else bg-success @endif"
                                                     style="width: {{ $occupancyPercent }}%"></div>
                                            </div>
                                        </div>

                                        <!-- Employees -->
                                        <div class="mb-3">
                                            @if($hostel->employees_count > 0)
                                                <div class="d-flex flex-wrap">
                                                    @foreach($hostel->employees->take(4) as $employee)
                                                        <div class="mr-2 mb-1" data-toggle="tooltip" title="{{ $employee->name }} {{ $employee->last_name }}">
                                                            <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                                 class="employee-avatar" alt="{{ $employee->name }}">
                                                        </div>
                                                    @endforeach
                                                    @if($hostel->employees_count > 4)
                                                        <div class="mb-1">
                                                            <span class="badge badge-light">+{{ $hostel->employees_count - 4 }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center py-2">
                                                    <small class="text-muted">@lang('global.noEmployees')</small>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Footer -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($hostel->capacity > $hostel->employees_count)
                                                    <span class="capacity-badge bg-success text-white">
                                                    {{ $hostel->capacity - $hostel->employees_count }} @lang('global.available')
                                                </span>
                                                @else
                                                    <span class="capacity-badge bg-danger text-white">
                                                    @lang('global.full')
                                                </span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                <i class="fe fe-calendar mr-1"></i>
                                                {{ $hostel->created_at->format('Y-m-d') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $hostels->links() }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fe fe-home"></i>
                                <h5 class="mb-2">@lang('global.noHostelsFound')</h5>
                                <p class="text-muted mb-4">@lang('global.noHostelsDescription')</p>
                                @can('office_hostel_create')
                                    <a href="{{ route('admin.office.hostel.create') }}" class="btn btn-primary">
                                        <i class="fe fe-plus-circle mr-1"></i> @lang('global.addFirstHostel')
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--/==/ End of Card View -->

        <!-- Table View (Default) -->
        <div class="row" id="tableView">
            <div class="col-lg-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Table Card -->
                <div class="card hostel-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">@lang('pages.hostel.hostel')</h6>
                            <p class="text-muted card-sub-title mb-0">
                                {{ $hostels->total() }} @lang('global.recordsFound')
                            </p>
                        </div>
                        <div class="card-options">
                            <select id="tableLength" class="form-control form-control-sm w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">@lang('global.all')</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($hostels->count() > 0)
                            <!-- Table -->
                            <div class="table-responsive">
                                <table id="hostelsTable" class="table table-hover table-bordered border-t0 key-buttons text-nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">@lang('global.location')</th>
                                        <th width="15%">@lang('pages.hostel.roomNumber')</th>
                                        <th width="10%">@lang('pages.hostel.section')</th>
                                        <th width="20%">@lang('global.capacity')</th>
                                        <th width="15%">@lang('admin.sidebar.employees')</th>
                                        <th width="10%">@lang('global.status')</th>
                                        <th width="10%">@lang('global.actions')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($hostels as $hostel)
                                        <tr data-id="{{ $hostel->id }}"
                                            data-place="{{ $hostel->place_id }}"
                                            data-section="{{ $hostel->section }}"
                                            data-capacity="{{ $hostel->capacity }}"
                                            data-occupied="{{ $hostel->employees_count }}">
                                            <td>{{ ($hostels->currentPage() - 1) * $hostels->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.places.show', $hostel->place->id) }}" class="font-weight-semibold">
                                                        {{ $hostel->place->name ?? '' }}
                                                    </a>
                                                    <small class="text-muted">
                                                        {{ $hostel->place->code ?? '' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.office.hostel.show', $hostel->id) }}" class="room-number">
                                                    {{ $hostel->number }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($hostel->section)
                                                    <span class="badge badge-secondary">{{ $hostel->section }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <small class="text-muted">
                                                            {{ $hostel->employees_count }}/{{ $hostel->capacity }}
                                                        </small>
                                                        <small class="text-muted">
                                                            @php
                                                                $percent = $hostel->capacity > 0 ?
                                                                    round(($hostel->employees_count / $hostel->capacity) * 100, 0) : 0;
                                                            @endphp
                                                            {{ $percent }}%
                                                        </small>
                                                    </div>
                                                    <div class="progress progress-thin">
                                                        <div class="progress-bar
                                                            @if($percent >= 90) bg-danger
                                                            @elseif($percent >= 70) bg-warning
                                                            @else bg-success @endif"
                                                             style="width: {{ $percent }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-success">
                                                        @if($hostel->capacity > $hostel->employees_count)
                                                            {{ $hostel->capacity - $hostel->employees_count }} @lang('global.available')
                                                        @else
                                                            @lang('global.full')
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($hostel->employees_count > 0)
                                                    <div class="d-flex flex-wrap">
                                                        @foreach($hostel->employees->take(3) as $employee)
                                                            <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                                               class="mr-1 mb-1" data-toggle="tooltip"
                                                               title="{{ $employee->name }} {{ $employee->last_name }}">
                                                                <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                                     class="employee-avatar" alt="{{ $employee->name }}">
                                                            </a>
                                                        @endforeach
                                                        @if($hostel->employees_count > 3)
                                                            <a href="{{ route('admin.office.hostel.show', $hostel->id) }}"
                                                               class="mb-1">
                                                                <span class="badge badge-light">+{{ $hostel->employees_count - 3 }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">@lang('global.noEmployees')</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($hostel->capacity > $hostel->employees_count)
                                                    @if($hostel->employees_count == 0)
                                                        <span class="status-badge bg-success text-white">
                                                            @lang('global.empty')
                                                        </span>
                                                    @else
                                                        <span class="status-badge bg-info text-white">
                                                            @lang('global.partial')
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="status-badge bg-danger text-white">
                                                        @lang('global.full')
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.office.hostel.show', $hostel->id) }}"
                                                       class="btn btn-sm btn-info" title="@lang('global.view')">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    @can('office_hostel_edit')
                                                        <a href="{{ route('admin.office.hostel.edit', $hostel->id) }}"
                                                           class="btn btn-sm btn-warning" title="@lang('global.edit')">
                                                            <i class="fe fe-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('office_hostel_delete')
                                                        <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-sign"
                                                           data-toggle="modal" href="#deleteHostelModal{{ $hostel->id }}"
                                                           title="@lang('global.delete')">
                                                            <i class="fe fe-trash-2"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Table -->

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $hostels->links() }}
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <i class="fe fe-home"></i>
                                <h5 class="mb-2">@lang('global.noHostelsFound')</h5>
                                <p class="text-muted mb-4">@lang('global.noHostelsDescription')</p>
                                @can('office_hostel_create')
                                    <a href="{{ route('admin.office.hostel.create') }}" class="btn btn-primary">
                                        <i class="fe fe-plus-circle mr-1"></i> @lang('global.addFirstHostel')
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
                <!--/==/ End of Table Card -->
            </div>
        </div>
        <!--/==/ End of Table View -->
    </div>

    <!-- Include Delete Modals -->
    @foreach($hostels as $hostel)
        @include('admin.office.hostel.modals.delete', ['hostel' => $hostel])
    @endforeach

    <!-- Assign Employees Modal -->
    <div class="modal fade" id="assignEmployeesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('global.assignEmployees')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="assignEmployeesContent">
                        <!-- Content will be loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.colVis.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>

    <!-- Chart.js -->
    <script src="{{ asset('backend/assets/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#hostelsTable').DataTable({
                language: {
                    url: '{{ asset("assets/lang/" . app()->getLocale() . ".json") }}'
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ trans('global.all') }}"]],
                order: [[0, 'asc']],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-sm btn-outline-primary',
                        text: '<i class="fe fe-copy mr-1"></i> {{ trans("global.copy") }}'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success',
                        text: '<i class="fe fe-file-text mr-1"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger',
                        text: '<i class="fe fe-file mr-1"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-info',
                        text: '<i class="fe fe-printer mr-1"></i> {{ trans("global.print") }}'
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-sm btn-outline-secondary',
                        text: '<i class="fe fe-eye mr-1"></i> {{ trans("global.columns") }}'
                    }
                ],
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: false, // Set to true for large datasets
                initComplete: function() {
                    $('#tableLength').val(this.api().page.len());
                }
            });

            // Search functionality
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Table length menu
            $('#tableLength').on('change', function() {
                table.page.len(this.value).draw();
            });

            // Filter by place
            $('#filterPlace').on('change', function() {
                const placeId = $(this).val();
                table.column(1).search(placeId).draw();
            });

            // Filter by section
            $('#filterSection').on('change', function() {
                const section = $(this).val();
                table.column(3).search(section).draw();
            });

            // Filter by status
            $('#filterStatus').on('change', function() {
                const status = $(this).val();
                if (status === 'available') {
                    table.rows().every(function() {
                        const row = this.node();
                        const capacity = parseInt($(row).data('capacity'));
                        const occupied = parseInt($(row).data('occupied'));
                        $(row).toggle(occupied < capacity);
                    });
                    table.draw(false);
                } else if (status === 'full') {
                    table.rows().every(function() {
                        const row = this.node();
                        const capacity = parseInt($(row).data('capacity'));
                        const occupied = parseInt($(row).data('occupied'));
                        $(row).toggle(occupied >= capacity);
                    });
                    table.draw(false);
                } else if (status === 'partial') {
                    table.rows().every(function() {
                        const row = this.node();
                        const capacity = parseInt($(row).data('capacity'));
                        const occupied = parseInt($(row).data('occupied'));
                        $(row).toggle(occupied > 0 && occupied < capacity);
                    });
                    table.draw(false);
                } else {
                    table.rows().every(function() {
                        $(this.node()).show();
                    });
                    table.draw(false);
                }
            });

            // Quick filters
            $('.quick-filter').on('click', function() {
                $('.quick-filter').removeClass('active');
                $(this).addClass('active');

                const filter = $(this).data('filter');
                if (filter === 'available') {
                    $('#filterStatus').val('available').trigger('change');
                } else if (filter === 'full') {
                    $('#filterStatus').val('full').trigger('change');
                } else if (filter === 'partial') {
                    $('#filterStatus').val('partial').trigger('change');
                } else if (filter === 'recent') {
                    table.order([0, 'desc']).draw();
                } else if (filter === 'empty') {
                    table.rows().every(function() {
                        const row = this.node();
                        const occupied = parseInt($(row).data('occupied'));
                        $(row).toggle(occupied === 0);
                    });
                    table.draw(false);
                } else {
                    table.rows().every(function() {
                        $(this.node()).show();
                    });
                    table.draw(false);
                }
            });

            // View toggle
            $('#tableViewBtn').on('click', function() {
                $(this).addClass('active');
                $('#cardViewBtn').removeClass('active');
                $('#tableView').show();
                $('#cardView').hide();
            });

            $('#cardViewBtn').on('click', function() {
                $(this).addClass('active');
                $('#tableViewBtn').removeClass('active');
                $('#cardView').show();
                $('#tableView').hide();
            });

            // Export button
            $('#exportBtn').on('click', function() {
                $('.dt-buttons .btn-group').toggleClass('show');
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.btn-group').length) {
                    $('.btn-group').removeClass('show');
                }
            });
        });

        // Assign employees function
        function assignEmployees(hostelId) {
            // Load employees list via AJAX
            $.ajax({
                url: '{{ route("admin.office.hostel.get-by-place", ":id") }}'.replace(':id', hostelId),
                method: 'GET',
                success: function(response) {
                    let content = `
                        <form id="assignForm">
                            <input type="hidden" name="hostel_id" value="${hostelId}">
                            <div class="form-group">
                                <label>@lang('global.selectEmployees')</label>
                                <select class="form-control select2-employees" name="employee_ids[]" multiple="multiple" style="width: 100%;">
                                    <!-- Employees will be loaded here -->
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="alert alert-info">
                                    <i class="fe fe-info mr-2"></i>
                                    @lang('global.selectEmployeesHelp')
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">@lang('global.cancel')</button>
                                <button type="submit" class="btn btn-primary">@lang('global.assign')</button>
                            </div>
                        </form>
                    `;

                    $('#assignEmployeesContent').html(content);
                    $('#assignEmployeesModal').modal('show');

                    // Initialize Select2 for employees
                    $('.select2-employees').select2({
                        ajax: {
                            url: '{{ route("admin.office.employees.search-available") }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    page: params.page,
                                    hostel_id: hostelId
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.results
                                };
                            },
                            cache: true
                        },
                        placeholder: '@lang('global.searchEmployees')',
                        minimumInputLength: 2,
                        templateResult: formatEmployee,
                        templateSelection: formatEmployeeSelection
                    });

                    // Handle form submission
                    $('#assignForm').on('submit', function(e) {
                        e.preventDefault();

                        const formData = $(this).serialize();

                        $.ajax({
                            url: '{{ route("admin.office.hostel.bulk-assign") }}',
                            method: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '@lang('global.success')',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#assignEmployeesModal').modal('hide');
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '@lang('global.error')',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '@lang('global.error')',
                                    text: xhr.responseJSON?.message || '@lang('global.unknownError')'
                                });
                            }
                        });
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: '@lang('global.error')',
                        text: '@lang('global.loadError')'
                    });
                }
            });
        }

        // Format employee for Select2
        function formatEmployee(employee) {
            if (!employee.id) {
                return employee.text;
            }

            const $result = $(
                '<div class="d-flex align-items-center">' +
                '<div class="mr-2">' +
                '<img src="' + (employee.photo || '{{ asset("assets/images/avatar-default.jpeg") }}') + '" class="rounded-circle" width="30" height="30">' +
                '</div>' +
                '<div>' +
                '<div class="font-weight-semibold">' + employee.name + ' ' + employee.last_name + '</div>' +
                '<small class="text-muted">' + (employee.position || '') + '</small>' +
                '</div>' +
                '</div>'
            );

            return $result;
        }

        function formatEmployeeSelection(employee) {
            return employee.name + ' ' + employee.last_name || employee.text;
        }

        // Print functionality
        function printHostelReport() {
            const originalContent = document.body.innerHTML;
            const printContent = document.querySelector('.container-fluid').innerHTML;

            document.body.innerHTML = `
                <!DOCTYPE html>
                <html lang="fa">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>@lang('pages.hostel.hostel') - چاپ</title>
                    <style>
                        @media print {
                            @page { margin: 0; }
                            body { margin: 1.6cm; }
                            .no-print { display: none !important; }
                            .card { border: 1px solid #ddd !important; }
                            a { color: #000 !important; text-decoration: none !important; }
                        }
                        body { font-family: 'Tahoma', 'Arial', sans-serif; direction: rtl; }
                        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                        .print-footer { text-align: center; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { padding: 8px; text-align: right; border: 1px solid #ddd; }
                        th { background-color: #f5f5f5; }
                        .text-center { text-align: center; }
                        .mb-3 { margin-bottom: 15px; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2>@lang('pages.hostel.hostel') گزارش</h2>
                        <p>تاریخ چاپ: ${new Date().toLocaleDateString('fa-IR')}</p>
                    </div>
                    ${printContent}
                    <div class="print-footer">
                        <p>چاپ شده از سیستم مدیریت گمرک افغانستان</p>
                    </div>
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection
