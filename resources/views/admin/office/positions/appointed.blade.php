@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('pages.positions.appointmentPositions'))

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .badge-status {
            font-size: 0.75em;
            padding: 0.3em 0.6em;
        }
        .employee-name {
            font-weight: 600;
            color: #2d3748;
        }
        .position-link {
            color: #2a4365;
            transition: color 0.2s;
        }
        .position-link:hover {
            color: #1a202c;
            text-decoration: underline;
        }
        .dataTables_wrapper {
            padding: 0;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
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
        .stat-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d3748;
        }
        .stat-label {
            color: #718096;
            font-size: 0.875rem;
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
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.positions.appointmentPositions')</li>
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
                        @can('office_employee_view')
                            <a class="dropdown-item" href="{{ route('admin.office.employees.index') }}">
                                <i class="fe fe-users mr-2"></i> @lang('pages.employees.allEmployees')
                            </a>
                        @endcan
                        <div class="dropdown-divider"></div>
                        @can('office_position_create')
                            <a class="dropdown-item" href="{{ route('admin.office.positions.create') }}">
                                <i class="fe fe-plus-circle mr-2"></i> @lang('pages.positions.addPosition')
                            </a>
                        @endcan
                        @can('office_employee_create')
                            <a class="dropdown-item" href="{{ route('admin.office.employees.create') }}">
                                <i class="fe fe-user-plus mr-2"></i> @lang('pages.employees.addEmployee')
                            </a>
                        @endcan
                    </div>
                </div>

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
                                    @lang('pages.positions.totalAppointed')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">{{ $codes->count() }}</h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-briefcase tx-40 tx-primary"></i>
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
                                <i class="fe fe-layers tx-40 tx-success"></i>
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
                                    @lang('pages.employees.activeEmployees')
                                </h6>
                                <h4 class="tx-22 tx-sm-20 tx-lg-22 tx-normal tx-rubik mg-b-0">
                                    {{ $codes->where('employee.status', 1)->count() }}
                                </h4>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-user-check tx-40 tx-info"></i>
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
                                    @lang('form.status')
                                </h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-success badge-status mr-2">{{ $codes->count() }} @lang('global.active')</span>
                                </div>
                            </div>
                            <div class="align-self-center">
                                <i class="fe fe-activity tx-40 tx-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Statistics Cards -->

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
                                <i class="fe fe-briefcase mr-2"></i> @lang('pages.positions.appointmentPositions')
                            </h6>
                            <p class="tx-12 tx-color-03 mg-b-0">
                                {{ $codes->count() }} @lang('global.recordsFound')
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
                                <table id="appointed-positions-table"
                                       class="table table-hover table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">@lang('form.title')</th>
                                        <th width="10%">@lang('form.code')</th>
                                        <th width="25%">@lang('pages.employees.employee')</th>
                                        <th width="20%">@lang('pages.positions.underHand')</th>
                                        <th width="10%">@lang('pages.positions.positionNumber')</th>
                                        <th width="10%">@lang('form.actions')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($codes as $code)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <!-- Position Title -->
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.office.positions.show', $code->position->id) }}"
                                                       class="position-link mb-1" title="{{ $code->position->title }}">
                                                        {{ Str::limit($code->position->title, 40) }}
                                                    </a>
                                                    <small class="text-muted">
                                                        @if($code->position->place)
                                                            <i class="fe fe-map-pin mr-1"></i>{{ $code->position->place->name ?? '' }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>

                                            <!-- Position Code -->
                                            <td>
                                                <span class="badge badge-primary badge-status">{{ $code->code }}</span>
                                                <div class="mt-1">
                                                    {!! $code->getStatusBadge() !!}
                                                </div>
                                            </td>

                                            <!-- Employee Info -->
                                            <td>
                                                @if($code->employee)
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('admin.office.employees.show', $code->employee->id) }}"
                                                           target="_blank" class="employee-name mb-1">
                                                            {{ $code->employee->name }} {{ $code->employee->last_name }}
                                                        </a>
                                                        <div class="d-flex flex-wrap">
                                                            <small class="text-muted mr-2">
                                                                <i class="fe fe-hash mr-1"></i>{{ $code->employee->emp_number }}
                                                            </small>
                                                            @if($code->employee->phone)
                                                                <small class="text-muted">
                                                                    <i class="fe fe-phone mr-1"></i>{{ $code->employee->phone }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">@lang('global.notSet')</span>
                                                @endif
                                            </td>

                                            <!-- Parent Position -->
                                            <td>
                                                @if($code->position->parent)
                                                    <a href="{{ route('admin.office.positions.show', $code->position->parent->id) }}"
                                                       class="text-primary" title="{{ $code->position->parent->title }}">
                                                        {{ Str::limit($code->position->parent->title, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">@lang('pages.positions.afCustomsDep')</span>
                                                @endif
                                            </td>

                                            <!-- Position Number -->
                                            <td>
                                                {{ $code->position->position_number }}
                                            </td>

                                            <!-- Actions -->
                                            <td>
                                                <div class="btn-group">
                                                    @can('office_position_view')
                                                        <a href="{{ route('admin.office.positions.show', $code->position->id) }}"
                                                           class="btn btn-sm btn-info" title="@lang('global.view')">
                                                            <i class="fe fe-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('office_employee_view')
                                                        @if($code->employee)
                                                            <a href="{{ route('admin.office.employees.show', $code->employee->id) }}"
                                                               class="btn btn-sm btn-success" title="@lang('global.viewEmployee')" target="_blank">
                                                                <i class="fe fe-user"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('office_position_code_edit')
                                                        <a href="#" class="btn btn-sm btn-warning"
                                                           onclick="editPositionCode({{ $code->id }})" title="@lang('global.edit')">
                                                            <i class="fe fe-edit-2"></i>
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
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <i class="fe fe-briefcase"></i>
                                <h4 class="tx-20 tx-semibold mg-b-10">@lang('pages.positions.noAppointedPositions')</h4>
                                <p class="tx-14 mg-b-30">@lang('pages.positions.noAppointedPositionsDesc')</p>
                                @can('office_position_create')
                                    <a href="{{ route('admin.office.positions.create') }}" class="btn btn-primary">
                                        <i class="fe fe-plus-circle mr-2"></i> @lang('pages.positions.addPosition')
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

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#appointed-positions-table').DataTable({
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
                    // Update showing count
                    updateShowingCount();

                    // Set initial length
                    $('#table-length').val(this.api().page.len());
                },
                drawCallback: function() {
                    updateShowingCount();
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

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.btn-group').length) {
                    $('.btn-group').removeClass('show');
                }
            });

            function updateShowingCount() {
                const info = table.page.info();
                const showing = info.length === -1 ? info.recordsTotal : Math.min(info.end + 1, info.recordsTotal);
                $('#showing-count').text(showing);
            }

            // Add spin animation class
            $.fn.addClassWithTimeout = function(className, timeout) {
                const element = this;
                element.addClass(className);
                setTimeout(() => {
                    element.removeClass(className);
                }, timeout);
            };

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
            `).appendTo('head');
        });

        // Edit Position Code Function
        function editPositionCode(codeId) {
            // This function can be implemented based on your needs
            // It could open a modal or redirect to an edit page
            window.location.href = `{{ url('admin/office/position-codes') }}/${codeId}/edit`;
        }
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
