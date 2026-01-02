@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('pages.positions.emptyPositions'))

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .badge-vacant {
            background-color: #f0ad4e;
            color: white;
            font-size: 0.75em;
            padding: 0.3em 0.6em;
        }
        .badge-inactive {
            background-color: #6c757d;
            color: white;
        }
        .assign-btn {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
        }
        .position-link {
            color: #2a4365;
            transition: color 0.2s;
        }
        .position-link:hover {
            color: #1a202c;
            text-decoration: underline;
        }
        .vacancy-highlight {
            background-color: #fff3cd !important;
            border-left: 3px solid #ffc107;
        }
        .urgency-high {
            background-color: #f8d7da !important;
            border-left: 3px solid #dc3545;
        }
        .urgency-medium {
            background-color: #fff3cd !important;
            border-left: 3px solid #ffc107;
        }
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #718096;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #cbd5e0;
        }
        .filter-badge {
            cursor: pointer;
            transition: all 0.2s;
        }
        .filter-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .code-cell {
            font-family: 'Courier New', monospace;
            font-weight: bold;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('admin.sidebar.positions')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.positions.index') }}">@lang('pages.positions.positions')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.positions.emptyPositions')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Quick Actions -->
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fe fe-settings"></i> @lang('global.quickActions')
                    </button>
                    <div class="dropdown-menu">
                        @can('office_position_view')
                            <a class="dropdown-item" href="{{ route('admin.office.positions.index') }}">
                                <i class="fe fe-list mr-2"></i> @lang('pages.positions.allPositions')
                            </a>
                        @endcan
                        @can('office_position_view')
                            <a class="dropdown-item" href="{{ route('admin.office.positions.appointed') }}">
                                <i class="fe fe-user-check mr-2"></i> @lang('pages.positions.appointedPositions')
                            </a>
                        @endcan
                        <div class="dropdown-divider"></div>
                        @can('office_position_create')
                            <a class="dropdown-item" href="{{ route('admin.office.positions.create') }}">
                                <i class="fe fe-plus-circle mr-2"></i> @lang('pages.positions.addPosition')
                            </a>
                        @endcan
                        @can('office_position_code_create')
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addPositionCodeModal">
                                <i class="fe fe-hash mr-2"></i> @lang('pages.positions.addPositionCode')
                            </a>
                        @endcan
                        @can('office_employee_create')
                            <a class="dropdown-item" href="{{ route('admin.office.employees.create') }}">
                                <i class="fe fe-user-plus mr-2"></i> @lang('pages.employees.addEmployee')
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fe fe-users"></i> @lang('global.bulkActions')
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" id="bulk-assign-employee">
                            <i class="fe fe-user-plus mr-2"></i> @lang('pages.positions.assignEmployee')
                        </a>
                        <a class="dropdown-item" href="#" id="bulk-activate">
                            <i class="fe fe-check-circle mr-2"></i> @lang('global.activate')
                        </a>
                        <a class="dropdown-item" href="#" id="bulk-deactivate">
                            <i class="fe fe-x-circle mr-2"></i> @lang('global.deactivate')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="bulk-export">
                            <i class="fe fe-download mr-2"></i> @lang('global.exportSelected')
                        </a>
                    </div>
                </div>

                <!-- Filter Button -->
                <button type="button" class="btn btn-outline-info mr-2" id="filter-toggle">
                    <i class="fe fe-filter"></i> @lang('global.filter')
                </button>

                <!-- Export Button -->
                <button type="button" class="btn btn-success mr-2" id="export-btn">
                    <i class="fe fe-download"></i> @lang('global.export')
                </button>

                <!-- Refresh Button -->
                <button type="button" class="btn btn-outline-secondary" id="refresh-table">
                    <i class="fe fe-refresh-cw"></i> @lang('global.refresh')
                </button>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Statistics Cards -->
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('pages.positions.totalEmpty')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $codes->count() }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-briefcase tx-40 tx-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('pages.positions.activeEmpty')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    {{ $codes->where('status', 1)->count() }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-check-circle tx-40 tx-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('pages.positions.uniquePositions')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    {{ $codes->pluck('position_id')->unique()->count() }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-layers tx-40 tx-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('pages.positions.vacancyRate')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    @php
                                        $totalPositions = \App\Models\Office\Position::active()->count();
                                        $vacancyRate = $totalPositions > 0 ? round(($codes->count() / $totalPositions) * 100, 1) : 0;
                                    @endphp
                                    {{ $vacancyRate }}%
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-percent tx-40 tx-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Statistics Cards -->

        <!-- Filter Panel -->
        <div class="row mb-3" id="filter-panel" style="display: none;">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mg-b-0"><i class="fe fe-filter mr-2"></i> @lang('global.filterOptions')</h6>
                        <button type="button" class="close" id="close-filter">&times;</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('form.status')</label>
                                    <select class="form-control" id="filter-status">
                                        <option value="">@lang('global.all')</option>
                                        <option value="1">@lang('global.active')</option>
                                        <option value="0">@lang('global.inactive')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('pages.positions.positionType')</label>
                                    <select class="form-control" id="filter-position-type">
                                        <option value="">@lang('global.all')</option>
                                        <option value="parent">@lang('pages.positions.withParent')</option>
                                        <option value="no_parent">@lang('pages.positions.withoutParent')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('pages.positions.place')</label>
                                    <select class="form-control" id="filter-place">
                                        <option value="">@lang('global.all')</option>
                                        @foreach(\App\Models\Place::all() as $place)
                                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('form.code')</label>
                                    <input type="text" class="form-control" id="filter-code" placeholder="@lang('global.searchByCode')">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary mr-2" id="apply-filter">
                                    <i class="fe fe-check mr-1"></i> @lang('global.applyFilter')
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="reset-filter">
                                    <i class="fe fe-refresh-cw mr-1"></i> @lang('global.reset')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Filter Panel -->

        <!-- Quick Filters -->
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap gap-2">
                    <span class="text-muted mr-2">@lang('global.quickFilters'):</span>
                    <span class="badge badge-primary filter-badge" data-filter="all">
                        @lang('global.all') ({{ $codes->count() }})
                    </span>
                    <span class="badge badge-success filter-badge" data-filter="active">
                        @lang('global.active') ({{ $codes->where('status', 1)->count() }})
                    </span>
                    <span class="badge badge-secondary filter-badge" data-filter="inactive">
                        @lang('global.inactive') ({{ $codes->where('status', 0)->count() }})
                    </span>
                    <span class="badge badge-info filter-badge" data-filter="recent">
                        @lang('global.recent') ({{ $codes->where('created_at', '>=', now()->subDays(30))->count() }})
                    </span>
                    <span class="badge badge-warning filter-badge" data-filter="urgent">
                        @lang('global.urgent') ({{ $codes->where('position.num_of_pos', '>', 0)->count() }})
                    </span>
                </div>
            </div>
        </div>
        <!--/==/ End of Quick Filters -->

        <!-- Data Table -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="tx-15 tx-bold mg-b-0">
                                <i class="fe fe-briefcase mr-2"></i> @lang('pages.positions.emptyPositions')
                            </h6>
                            <p class="tx-12 tx-color-03 mg-b-0">
                                {{ $codes->count() }} @lang('global.vacantPositions')
                            </p>
                        </div>
                        <div class="card-options">
                            <!-- Search Input -->
                            <div class="input-group mr-3" style="width: 250px;">
                                <input type="text" id="table-search" class="form-control"
                                       placeholder="@lang('global.search')...">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fe fe-search"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Table Length Menu -->
                            <select id="table-length" class="form-control form-control-sm w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">@lang('global.all')</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($codes->count() > 0)
                            <!-- Table -->
                            <div class="table-responsive">
                                <table id="empty-positions-table"
                                       class="table table-hover table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th width="3%">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th width="5%">#</th>
                                        <th width="25%">@lang('form.title')</th>
                                        <th width="15%">@lang('form.code')</th>
                                        <th width="20%">@lang('pages.positions.underHand')</th>
                                        <th width="10%">@lang('pages.positions.positionNumber')</th>
                                        <th width="12%">@lang('form.status')</th>
                                        <th width="10%">@lang('form.actions')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($codes as $code)
                                        @php
                                            $isUrgent = $code->position->num_of_pos > 0 &&
                                                      $code->position->codes->where('employee')->count() >= $code->position->num_of_pos;
                                            $rowClass = '';
                                            if ($isUrgent) {
                                                $rowClass = 'urgency-high';
                                            } elseif ($code->position->num_of_pos > 0) {
                                                $rowClass = 'urgency-medium';
                                            } else {
                                                $rowClass = 'vacancy-highlight';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}" data-id="{{ $code->id }}">
                                            <td>
                                                <input type="checkbox" class="row-select" value="{{ $code->id }}">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>

                                            <!-- Position Title -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.office.positions.show', $code->position->id) }}"
                                                       class="position-link mb-1" title="{{ $code->position->title }}">
                                                        {{ Str::limit($code->position->title, 50) }}
                                                    </a>
                                                    <div class="d-flex flex-wrap">
                                                        @if($code->position->place)
                                                            <small class="text-muted mr-2">
                                                                <i class="fe fe-map-pin mr-1"></i>{{ $code->position->place->name ?? '' }}
                                                            </small>
                                                        @endif
                                                        @if($code->position->num_of_pos > 0)
                                                            <small class="text-info">
                                                                <i class="fe fe-users mr-1"></i>
                                                                {{ $code->position->codes->where('employee')->count() }}/{{ $code->position->num_of_pos }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Position Code -->
                                            <td>
                                                <div class="code-cell">{{ $code->code }}</div>
                                                <small class="text-muted">
                                                    <i class="fe fe-calendar mr-1"></i>
                                                    {{ $code->created_at->format('Y-m-d') }}
                                                </small>
                                            </td>

                                            <!-- Parent Position -->
                                            <td>
                                                @if($code->position->parent)
                                                    <a href="{{ route('admin.office.positions.show', $code->position->parent->id) }}"
                                                       class="text-primary" title="{{ $code->position->parent->title }}">
                                                        {{ Str::limit($code->position->parent->title, 35) }}
                                                    </a>
                                                    <div>
                                                        <small class="text-muted">
                                                            @if($code->position->parent->place)
                                                                <i class="fe fe-map-pin mr-1"></i>{{ $code->position->parent->place->name ?? '' }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">@lang('pages.positions.afCustomsDep')</span>
                                                @endif
                                            </td>

                                            <!-- Position Number -->
                                            <td>
                                                {{ $code->position->position_number }}
                                                @if($code->position->num_of_pos > 0)
                                                    <div class="mt-1">
                                                        <small class="badge badge-{{ $isUrgent ? 'danger' : 'warning' }}">
                                                            @if($isUrgent)
                                                                @lang('global.urgent')
                                                            @else
                                                                @lang('global.vacant')
                                                            @endif
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td>
                                                {!! $code->getStatusBadge() !!}
                                                @if($code->info)
                                                    <div class="mt-1">
                                                        <small class="text-muted" title="{{ $code->info }}">
                                                            <i class="fe fe-info mr-1"></i>
                                                            {{ Str::limit($code->info, 20) }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @can('office_employee_create')
                                                        <a href="{{ route('admin.office.employees.create', ['position_code_id' => $code->id]) }}"
                                                           class="btn btn-primary assign-btn"
                                                           title="@lang('pages.positions.assignEmployee')">
                                                            <i class="fe fe-user-plus"></i>
                                                        </a>
                                                    @endcan

                                                    @can('office_position_view')
                                                        <a href="{{ route('admin.office.positions.show', $code->position->id) }}"
                                                           class="btn btn-info" title="@lang('global.viewPosition')">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('office_position_code_edit')
                                                        <a href="#" class="btn btn-warning"
                                                           onclick="editPositionCode({{ $code->id }})" title="@lang('global.edit')">
                                                            <i class="fe fe-edit-2"></i>
                                                        </a>
                                                    @endcan

                                                    @can('office_position_code_delete')
                                                        <button type="button" class="btn btn-danger"
                                                                onclick="deletePositionCode({{ $code->id }})" title="@lang('global.delete')">
                                                            <i class="fe fe-trash-2"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Table -->
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <i class="fe fe-briefcase"></i>
                                <h4 class="tx-20 tx-semibold mg-b-10">@lang('pages.positions.noEmptyPositions')</h4>
                                <p class="tx-14 mg-b-30">@lang('pages.positions.noEmptyPositionsDesc')</p>
                                @can('office_position_create')
                                    <a href="{{ route('admin.office.positions.create') }}" class="btn btn-primary mr-2">
                                        <i class="fe fe-plus-circle mr-2"></i> @lang('pages.positions.addPosition')
                                    </a>
                                @endcan
                                @can('office_position_code_create')
                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#addPositionCodeModal">
                                        <i class="fe fe-hash mr-2"></i> @lang('pages.positions.addPositionCode')
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>

                    <!-- Table Footer -->
                    @if($codes->count() > 0)
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                @lang('global.showing') <span id="showing-count">{{ $codes->count() > 10 ? 10 : $codes->count() }}</span>
                                @lang('global.of') {{ $codes->count() }} @lang('global.entries')
                            </div>
                            <div>
                                <span class="text-muted mr-3" id="selected-count">0 @lang('global.selected')</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="print-table">
                                    <i class="fe fe-printer mr-1"></i> @lang('global.print')
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <!--/==/ End of Table Card -->
            </div>
        </div>
        <!--/==/ End of Data Table -->
    </div>

    <!-- Add Position Code Modal -->
    @can('office_position_code_create')
        <div class="modal fade" id="addPositionCodeModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('pages.positions.addPositionCode')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.office.position-codes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('pages.positions.selectPosition') *</label>
                                <select class="form-control" name="position_id" required>
                                    <option value="">@lang('global.select')</option>
                                    @foreach(\App\Models\Office\Position::active()->get() as $position)
                                        <option value="{{ $position->id }}">{{ $position->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('form.code') *</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('form.extraInfo')</label>
                                <textarea class="form-control" name="info" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                            <button type="submit" class="btn btn-primary">@lang('global.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
<!--/==/ End of Page Content -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#empty-positions-table').DataTable({
                language: {
                    url: '{{ asset("assets/lang/" . app()->getLocale() . ".json") }}'
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ trans('global.all') }}"]],
                order: [[2, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 7] },
                    { searchable: false, targets: [0, 7] }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-sm btn-outline-primary',
                        text: '<i class="fe fe-copy mr-1"></i> {{ trans("global.copy") }}',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success',
                        text: '<i class="fe fe-file-text mr-1"></i> Excel',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger',
                        text: '<i class="fe fe-file mr-1"></i> PDF',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-info',
                        text: '<i class="fe fe-printer mr-1"></i> {{ trans("global.print") }}',
                        exportOptions: {
                            columns: ':visible:not(:first-child)'
                        }
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
                initComplete: function() {
                    updateShowingCount();
                    $('#table-length').val(this.api().page.len());
                },
                drawCallback: function() {
                    updateShowingCount();
                    updateSelectedCount();
                }
            });

            // Search input
            $('#table-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Length menu
            $('#table-length').on('change', function() {
                table.page.len(this.value).draw();
            });

            // Select all checkbox
            $('#select-all').on('change', function() {
                $('.row-select').prop('checked', this.checked);
                updateSelectedCount();
            });

            // Individual row checkbox
            $(document).on('change', '.row-select', function() {
                updateSelectedCount();
            });

            // Export button
            $('#export-btn').on('click', function() {
                $('.dt-buttons .btn-group').toggleClass('show');
            });

            // Print button
            $('#print-table').on('click', function() {
                table.button('.buttons-print').trigger();
            });

            // Refresh table
            $('#refresh-table').on('click', function() {
                table.ajax.reload();
                $(this).find('i').addClass('spin');
                setTimeout(() => {
                    $(this).find('i').removeClass('spin');
                }, 1000);
            });

            // Filter panel toggle
            $('#filter-toggle').on('click', function() {
                $('#filter-panel').slideToggle();
            });

            $('#close-filter').on('click', function() {
                $('#filter-panel').slideUp();
            });

            // Apply filter
            $('#apply-filter').on('click', function() {
                applyFilters();
            });

            // Reset filter
            $('#reset-filter').on('click', function() {
                $('#filter-status').val('');
                $('#filter-position-type').val('');
                $('#filter-place').val('');
                $('#filter-code').val('');
                table.search('').columns().search('').draw();
            });

            // Quick filters
            $('.filter-badge').on('click', function() {
                const filter = $(this).data('filter');
                applyQuickFilter(filter);
            });

            // Bulk actions
            $('#bulk-assign-employee').on('click', function(e) {
                e.preventDefault();
                const selectedIds = getSelectedIds();
                if (selectedIds.length > 0) {
                    // Implement bulk assign logic
                    Swal.fire({
                        title: '{{ trans("global.assignEmployee") }}',
                        text: '{{ trans("global.bulkAssignConfirm", ["count" => "__count__"]) }}'.replace('__count__', selectedIds.length),
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '{{ trans("global.yes") }}',
                        cancelButtonText: '{{ trans("global.cancel") }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Add your bulk assign logic here
                        }
                    });
                } else {
                    Swal.fire('{{ trans("global.warning") }}', '{{ trans("global.selectItemsFirst") }}', 'warning');
                }
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.btn-group').length) {
                    $('.btn-group').removeClass('show');
                }
            });

            // Functions
            function updateShowingCount() {
                const info = table.page.info();
                const showing = info.length === -1 ? info.recordsTotal : Math.min(info.end + 1, info.recordsTotal);
                $('#showing-count').text(showing);
            }

            function updateSelectedCount() {
                const selectedCount = $('.row-select:checked').length;
                $('#selected-count').text(selectedCount + ' {{ trans("global.selected") }}');
                $('#select-all').prop('checked', selectedCount === $('.row-select').length);
            }

            function getSelectedIds() {
                return $('.row-select:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            function applyFilters() {
                const status = $('#filter-status').val();
                const positionType = $('#filter-position-type').val();
                const place = $('#filter-place').val();
                const code = $('#filter-code').val();

                // Build search query
                let query = '';
                if (status) query += 'Status: ' + (status === '1' ? 'Active' : 'Inactive') + ' ';
                if (code) query += 'Code: ' + code + ' ';

                table.search(query).draw();

                // Additional filtering logic can be added here
                if (positionType === 'parent') {
                    // Filter positions with parent
                    table.column(4).search('^((?!{{ trans("pages.positions.afCustomsDep") }}).)*$', true, false).draw();
                } else if (positionType === 'no_parent') {
                    // Filter positions without parent
                    table.column(4).search('{{ trans("pages.positions.afCustomsDep") }}', true, false).draw();
                }
            }

            function applyQuickFilter(filter) {
                switch(filter) {
                    case 'active':
                        table.search('Active').draw();
                        break;
                    case 'inactive':
                        table.search('Inactive').draw();
                        break;
                    case 'recent':
                        // Filter for recent entries (last 30 days)
                        const thirtyDaysAgo = new Date();
                        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                        // You would need custom filtering logic here
                        break;
                    case 'urgent':
                        // Filter urgent positions
                        $('tr.urgency-high').show();
                        $('tr:not(.urgency-high)').hide();
                        break;
                    default:
                        table.search('').draw();
                }
            }

            // Add spin animation class
            $.fn.addClassWithTimeout = function(className, timeout) {
                const element = this;
                element.addClass(className);
                setTimeout(() => {
                    element.removeClass(className);
                }, timeout);
            };
        });

        // Edit Position Code Function
        function editPositionCode(codeId) {
            window.location.href = `{{ url('admin/office/position-codes') }}/${codeId}/edit`;
        }

        // Delete Position Code Function
        function deletePositionCode(codeId) {
            Swal.fire({
                title: '{{ trans("global.deleteConfirm") }}',
                text: '{{ trans("global.deleteWarning") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ trans("global.yesDelete") }}',
                cancelButtonText: '{{ trans("global.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    fetch(`{{ url('admin/office/position-codes') }}/${codeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '{{ trans("global.deleted") }}',
                                    '{{ trans("global.deleteSuccess") }}',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    '{{ trans("global.error") }}',
                                    data.message || '{{ trans("global.deleteError") }}',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                '{{ trans("global.error") }}',
                                '{{ trans("global.deleteError") }}',
                                'error'
                            );
                        });
                }
            });
        }

        // Custom CSS for spin animation
        $('<style>').text(`
            .fe.spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .dt-button-collection {
                padding: 0.5rem 0;
            }
            .dt-button-collection .dropdown-item {
                padding: 0.5rem 1rem;
            }
            .dt-button-collection .dropdown-item i {
                width: 20px;
            }
            .urgency-high td {
                font-weight: bold;
            }
            .vacancy-highlight:hover {
                background-color: #fef3e9 !important;
            }
        `).appendTo('head');
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
