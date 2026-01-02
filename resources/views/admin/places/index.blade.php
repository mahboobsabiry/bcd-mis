@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('admin.sidebar.places'))

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert2.css') }}">

    <!-- Custom CSS -->
    <style>
        .place-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e3e6f0;
            border-radius: 10px;
        }
        .place-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .place-code {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        .stats-badge {
            font-size: 0.75em;
            padding: 0.25em 0.5em;
            margin-right: 5px;
        }
        .action-buttons .btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
        }
        .modal-xl-custom {
            max-width: 800px;
        }
        .info-preview {
            max-height: 100px;
            overflow: hidden;
            position: relative;
        }
        .info-preview:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(transparent, white);
        }
        .table-row-hover:hover {
            background-color: #f8f9fa;
        }
        .status-badge {
            font-size: 0.7em;
            padding: 0.2em 0.6em;
        }
        .custom-code-badge {
            background-color: #6c757d;
            color: white;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('admin.sidebar.places')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('admin.sidebar.places')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Quick Stats -->
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fe fe-bar-chart-2 mr-1"></i> @lang('global.quickStats')
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">@lang('global.statistics')</div>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.totalPlaces'):</span>
                                <strong>{{ $places->count() }}</strong>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.totalPositions'):</span>
                                <strong>{{ $places->sum('positions_count') }}</strong>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex justify-content-between">
                                <span>@lang('global.positionCodes'):</span>
                                <strong>{{ $places->sum('positions_codes_count') }}</strong>
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
                <a class="modal-effect btn ripple btn-primary" data-effect="effect-sign"
                   data-toggle="modal" href="#createPlaceModal">
                    <i class="fe fe-plus-circle mr-1"></i> @lang('global.new')
                </a>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Statistics Cards -->
        <div class="row row-sm mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                                    @lang('global.totalPlaces')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $places->count() }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-map-pin tx-40 tx-primary"></i>
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
                                    @lang('global.totalPositions')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    {{ $places->sum('positions_count') }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-briefcase tx-40 tx-success"></i>
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
                                    @lang('global.positionCodes')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    {{ $places->sum('positions_codes_count') }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-hash tx-40 tx-info"></i>
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
                                    @lang('global.avgPositionsPerPlace')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    @php
                                        $avg = $places->count() > 0 ? round($places->sum('positions_count') / $places->count(), 1) : 0;
                                    @endphp
                                    {{ $avg }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-percent tx-40 tx-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Statistics Cards -->

        <!-- Search and Filter Bar -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" id="searchInput" class="form-control"
                                           placeholder="@lang('global.searchPlaces')...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fe fe-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="sortSelect" class="form-control">
                                    <option value="name_asc">@lang('global.sortByNameAsc')</option>
                                    <option value="name_desc">@lang('global.sortByNameDesc')</option>
                                    <option value="code_asc">@lang('global.sortByCodeAsc')</option>
                                    <option value="code_desc">@lang('global.sortByCodeDesc')</option>
                                    <option value="created_desc">@lang('global.sortByNewest')</option>
                                    <option value="created_asc">@lang('global.sortByOldest')</option>
                                    <option value="positions_desc">@lang('global.mostPositions')</option>
                                    <option value="positions_asc">@lang('global.leastPositions')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" id="customCodeFilter" class="form-control"
                                           placeholder="@lang('global.filterByCustomCode')...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fe fe-hash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-secondary btn-block" id="resetFilters">
                                    <i class="fe fe-refresh-cw mr-1"></i> @lang('global.reset')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Search and Filter Bar -->

        <!-- Card View (Hidden by default) -->
        <div class="row" id="cardView" style="display: none;">
            <div class="col-lg-12">
                @if($places->count() > 0)
                    <div class="row">
                        @foreach($places as $place)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card place-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <a href="{{ route('admin.places.show', $place->id) }}" class="text-dark">
                                                        {{ $place->name }}
                                                    </a>
                                                </h5>
                                                <div class="d-flex flex-wrap">
                                                    <span class="place-code mr-2">{{ $place->code }}</span>
                                                    @if($place->custom_code)
                                                        <span class="badge custom-code-badge stats-badge">{{ $place->custom_code }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fe fe-more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.places.show', $place->id) }}">
                                                        <i class="fe fe-eye mr-2"></i> @lang('global.view')
                                                    </a>
                                                    <a class="dropdown-item modal-effect" data-effect="effect-sign"
                                                       data-toggle="modal" href="#editPlaceModal{{ $place->id }}">
                                                        <i class="fe fe-edit mr-2"></i> @lang('global.edit')
                                                    </a>
                                                    <a class="dropdown-item modal-effect" data-effect="effect-sign"
                                                       data-toggle="modal" href="#deletePlaceModal{{ $place->id }}">
                                                        <i class="fe fe-trash-2 mr-2"></i> @lang('global.delete')
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="{{ route('admin.office.positions.index', ['place_id' => $place->id]) }}">
                                                        <i class="fe fe-briefcase mr-2"></i> @lang('global.viewPositions')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="info-preview">
                                                {!! $place->info ?: '<span class="text-muted">' . trans('global.noInfo') . '</span>' !!}
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                            <span class="badge badge-primary stats-badge" title="@lang('global.totalPositions')">
                                                <i class="fe fe-briefcase mr-1"></i> {{ $place->positions_count }}
                                            </span>
                                                <span class="badge badge-info stats-badge" title="@lang('global.positionCodes')">
                                                <i class="fe fe-hash mr-1"></i> {{ $place->positions_codes_count }}
                                            </span>
                                            </div>
                                            <small class="text-muted">
                                                {{ \Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($place->created_at)) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fe fe-map-pin"></i>
                                <h4 class="tx-20 tx-semibold mg-b-10">@lang('global.noPlacesFound')</h4>
                                <p class="tx-14 mg-b-30">@lang('global.noPlacesDescription')</p>
                                <a class="modal-effect btn ripple btn-primary" data-effect="effect-sign"
                                   data-toggle="modal" href="#createPlaceModal">
                                    <i class="fe fe-plus-circle mr-1"></i> @lang('global.addFirstPlace')
                                </a>
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
                <!-- Message -->
                @include('admin.inc.alerts')

                <!-- Table Card -->
                <div class="card">
                    <!-- Table Header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">@lang('admin.sidebar.places')</h6>
                            <p class="text-muted card-sub-title mb-0">
                                {{ $places->count() }} @lang('global.recordsFound')
                            </p>
                        </div>
                        <div class="card-options">
                            <!-- Table Length Menu -->
                            <select id="tableLength" class="form-control form-control-sm w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="-1">@lang('global.all')</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Card Body -->
                    <div class="card-body">
                        @if($places->count() > 0)
                            <!-- Table -->
                            <div class="table-responsive">
                                <table id="placesTable" class="table table-hover table-bordered border-t0 key-buttons text-nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">@lang('form.name')</th>
                                        <th width="15%">@lang('form.code')</th>
                                        <th width="15%">@lang('form.customCode')</th>
                                        <th width="15%">@lang('global.statistics')</th>
                                        <th width="15%">@lang('global.createdDate')</th>
                                        <th width="15%">@lang('global.actions')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($places as $place)
                                        <tr class="table-row-hover" data-id="{{ $place->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.places.show', $place->id) }}" class="font-weight-semibold">
                                                        {{ $place->name }}
                                                    </a>
                                                    <small class="text-muted info-preview">
                                                        {!! Str::limit(strip_tags($place->info), 50) ?: '<span class="text-muted">' . trans('global.noInfo') . '</span>' !!}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="place-code">{{ $place->code }}</span>
                                            </td>
                                            <td>
                                                @if($place->custom_code)
                                                    <span class="badge custom-code-badge">{{ $place->custom_code }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge badge-primary stats-badge" title="@lang('global.totalPositions')">
                                                        <i class="fe fe-briefcase mr-1"></i> {{ $place->positions_count }}
                                                    </span>
                                                                                                <span class="badge badge-info stats-badge" title="@lang('global.positionCodes')">
                                                        <i class="fe fe-hash mr-1"></i> {{ $place->positions_codes_count }}
                                                    </span>
                                                                                                <span class="badge badge-success stats-badge" title="@lang('global.occupiedPositions')">
                                                        <i class="fe fe-user-check mr-1"></i> {{ $place->occupied_positions_count }}
                                                    </span>
                                                                                                <span class="badge badge-warning stats-badge" title="@lang('global.vacantPositions')">
                                                        <i class="fe fe-user-x mr-1"></i> {{ $place->vacant_positions_count }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime($place->created_at)) }}</span>
                                                    <small class="text-muted">
                                                        {{ $place->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <!-- View -->
                                                    <a href="{{ route('admin.places.show', $place->id) }}"
                                                       class="btn btn-sm btn-info" title="@lang('global.view')">
                                                        <i class="fe fe-eye"></i>
                                                    </a>

                                                    <!-- Edit -->
                                                    <a class="modal-effect btn btn-sm btn-warning" data-effect="effect-sign"
                                                       data-toggle="modal" href="#editPlaceModal{{ $place->id }}" title="@lang('global.edit')">
                                                        <i class="fe fe-edit"></i>
                                                    </a>

                                                    <!-- Delete -->
                                                    <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-sign"
                                                       data-toggle="modal" href="#deletePlaceModal{{ $place->id }}" title="@lang('global.delete')">
                                                        <i class="fe fe-trash-2"></i>
                                                    </a>

                                                    <!-- Quick Actions -->
                                                    <div class="dropdown d-inline">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <i class="fe fe-more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('admin.office.positions.index', ['place_id' => $place->id]) }}">
                                                                <i class="fe fe-briefcase mr-2"></i> @lang('global.viewPositions')
                                                            </a>
                                                            <a class="dropdown-item" href="#" onclick="copyPlaceInfo({{ $place->id }})">
                                                                <i class="fe fe-copy mr-2"></i> @lang('global.copyInfo')
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item text-danger" href="#" onclick="deactivatePlace({{ $place->id }})">
                                                                <i class="fe fe-x-circle mr-2"></i> @lang('global.deactivate')
                                                            </a>
                                                        </div>
                                                    </div>
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
                                <i class="fe fe-map-pin"></i>
                                <h4 class="tx-20 tx-semibold mg-b-10">@lang('global.noPlacesFound')</h4>
                                <p class="tx-14 mg-b-30">@lang('global.noPlacesDescription')</p>
                                <a class="modal-effect btn ripple btn-primary" data-effect="effect-sign"
                                   data-toggle="modal" href="#createPlaceModal">
                                    <i class="fe fe-plus-circle mr-1"></i> @lang('global.addFirstPlace')
                                </a>
                            </div>
                        @endif
                    </div>
                    <!--/==/ End of Table Card Body -->

                    <!-- Table Footer -->
                    @if($places->count() > 0)
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                @lang('global.showing') <span id="showingCount">{{ $places->count() > 10 ? 10 : $places->count() }}</span>
                                @lang('global.of') {{ $places->count() }} @lang('global.entries')
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="printTable">
                                    <i class="fe fe-printer mr-1"></i> @lang('global.print')
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <!--/==/ End of Table Card -->
            </div>
        </div>
        <!--/==/ End of Table View -->
    </div>

    <!-- Include Modals -->
    @include('admin.places.modals.create')
    @foreach($places as $place)
        @include('admin.places.modals.edit', ['place' => $place])
        @include('admin.places.modals.delete', ['place' => $place])
    @endforeach
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

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#placesTable').DataTable({
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
                        text: '<i class="fe fe-copy mr-1"></i> {{ trans("global.copy") }}',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success',
                        text: '<i class="fe fe-file-text mr-1"></i> Excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger',
                        text: '<i class="fe fe-file mr-1"></i> PDF',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-info',
                        text: '<i class="fe fe-printer mr-1"></i> {{ trans("global.print") }}',
                        exportOptions: {
                            columns: ':visible'
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
                    $('#tableLength').val(this.api().page.len());
                },
                drawCallback: function() {
                    updateShowingCount();
                }
            });

            // Table length menu
            $('#tableLength').on('change', function() {
                table.page.len(this.value).draw();
            });

            // Search functionality
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Sort functionality
            $('#sortSelect').on('change', function() {
                const sortValue = $(this).val();
                let column, dir;

                switch(sortValue) {
                    case 'name_asc':
                        column = 1;
                        dir = 'asc';
                        break;
                    case 'name_desc':
                        column = 1;
                        dir = 'desc';
                        break;
                    case 'code_asc':
                        column = 2;
                        dir = 'asc';
                        break;
                    case 'code_desc':
                        column = 2;
                        dir = 'desc';
                        break;
                    case 'created_desc':
                        column = 5;
                        dir = 'desc';
                        break;
                    case 'created_asc':
                        column = 5;
                        dir = 'asc';
                        break;
                    case 'positions_desc':
                        // Custom sorting for positions count
                        sortByPositionsCount('desc');
                        return;
                    case 'positions_asc':
                        // Custom sorting for positions count
                        sortByPositionsCount('asc');
                        return;
                }

                table.order([column, dir]).draw();
            });

            // Filter by custom code
            $('#customCodeFilter').on('keyup', function() {
                table.column(3).search(this.value).draw();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#customCodeFilter').val('');
                $('#sortSelect').val('name_asc');
                table.search('').columns().search('').order([0, 'asc']).draw();
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

            // Print button
            $('#printTable').on('click', function() {
                table.button('.buttons-print').trigger();
            });

            // Function to sort by positions count
            function sortByPositionsCount(direction) {
                const data = table.rows({ order: 'applied' }).data();
                const rowsArray = [];

                data.each(function(value, index) {
                    const positionsCount = parseInt($(value[4]).find('.badge-primary').text().match(/\d+/)[0]) || 0;
                    rowsArray.push({
                        index: index,
                        count: positionsCount,
                        data: value
                    });
                });

                rowsArray.sort(function(a, b) {
                    if (direction === 'asc') {
                        return a.count - b.count;
                    } else {
                        return b.count - a.count;
                    }
                });

                // Reorder table
                table.clear();
                rowsArray.forEach(function(row) {
                    table.row.add(row.data);
                });
                table.draw();
            }

            // Function to update showing count
            function updateShowingCount() {
                const info = table.page.info();
                const showing = info.length === -1 ? info.recordsTotal : Math.min(info.end + 1, info.recordsTotal);
                $('#showingCount').text(showing);
            }

            // Copy place info function
            window.copyPlaceInfo = function(placeId) {
                const placeRow = $(`tr[data-id="${placeId}"]`);
                const placeName = placeRow.find('td:nth-child(2) a').text();
                const placeCode = placeRow.find('.place-code').text();

                const textToCopy = `${placeName} (${placeCode})`;

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
            };

            // Deactivate place function
            window.deactivatePlace = function(placeId) {
                Swal.fire({
                    title: '@lang("global.deactivateConfirm")',
                    text: '@lang("global.deactivatePlaceWarning")',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '@lang("global.yesDeactivate")',
                    cancelButtonText: '@lang("global.cancel")'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send deactivation request
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
                            })
                            .catch(error => {
                                Swal.fire(
                                    '@lang("global.error")',
                                    '@lang("global.deactivateError")',
                                    'error'
                                );
                            });
                    }
                });
            };

            // Modal close handlers
            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $(this).find('.invalid-feedback').remove();
                $(this).find('.is-invalid').removeClass('is-invalid');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.btn-group').length) {
                    $('.btn-group').removeClass('show');
                }
            });
        });

        // Auto-generate code based on name
        function generateCodeFromName(nameInput) {
            const name = nameInput.value;
            if (name.length > 0) {
                // Generate code: first 3 letters in uppercase
                const code = name.substring(0, 3).toUpperCase();
                document.getElementById('code').value = code;
            }
        }
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
