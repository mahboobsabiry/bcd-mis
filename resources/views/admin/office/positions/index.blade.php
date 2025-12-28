@extends('layouts.admin.master')

@section('title', trans('admin.sidebar.positions'))

@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

        .position-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .stats-card {
            padding: 1rem;
            border-radius: 8px;
            color: white;
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-3px);
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .nav-tabs-custom {
            border-bottom: 2px solid #e3e6f0;
            margin-bottom: 1.5rem;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 8px 8px 0 0;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #6c757d;
            margin-right: 0.5rem;
            transition: all 0.3s;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--primary-color);
            background-color: #f8f9fc;
            border-bottom: 3px solid var(--primary-color);
        }

        .filter-section {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
        }

        .filter-btn-group .btn {
            border-radius: 20px;
            margin: 0 0.25rem;
            padding: 0.375rem 1rem;
            transition: all 0.3s;
        }

        .filter-btn-group .btn.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }

        .search-input {
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            border: 2px solid #e3e6f0;
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .position-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .progress-thin {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.5rem;
        }

        .code-dots {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            max-width: 150px;
        }

        .code-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .code-dot.filled {
            background-color: var(--success-color);
        }

        .code-dot.empty {
            background-color: var(--danger-color);
        }

        .code-dot:hover {
            transform: scale(1.3);
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .tree-container {
            max-height: 600px;
            overflow-y: auto;
        }

        /* Custom DataTable Styles */
        #positionsTable_wrapper {
            padding: 0;
        }

        #positionsTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #positionsTable thead th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
            white-space: nowrap;
        }

        #positionsTable tbody td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e3e6f0;
            vertical-align: middle;
        }

        #positionsTable tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .dataTables_filter input {
            border-radius: 20px;
            border: 2px solid #e3e6f0;
            padding: 0.5rem 1rem;
        }

        .dataTables_filter input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        /* Optimize animations */
        .stats-card, .position-card {
            transition: transform 0.2s ease;
        }

        /* Reduce shadows on mobile */
        @media (max-width: 768px) {
            .position-card {
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
        }

        /* Optimize code dots rendering */
        .code-dots {
            will-change: transform;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('admin.sidebar.positions')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('admin.sidebar.positions')</li>
                </ol>
            </div>

            <div class="btn btn-list">
                @can('office_position_create')
                    <a class="btn btn-primary" href="{{ route('admin.office.positions.create') }}">
                        <i class="fas fa-plus-circle mr-1"></i> @lang('pages.positions.addPosition')
                    </a>
                @endcan
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="text-decoration-none">
                    <div class="stats-card" style="background: linear-gradient(135deg, var(--primary-color), #6c5ce7);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">کل بست ها</h6>
                                <div class="stats-number">{{ $stats['total_positions'] ?? 0 }}</div>
                            </div>
                            <i class="fas fa-layer-group fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-decoration-none">
                    <div class="stats-card" style="background: linear-gradient(135deg, var(--success-color), #20c997);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">بست های پر</h6>
                                <div class="stats-number">{{ $stats['filled_positions'] ?? 0 }}</div>
                            </div>
                            <i class="fas fa-user-check fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-decoration-none">
                    <div class="stats-card" style="background: linear-gradient(135deg, var(--warning-color), #ff922b);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">بست های خالی</h6>
                                <div class="stats-number">{{ $stats['empty_positions'] ?? 0 }}</div>
                            </div>
                            <i class="fas fa-user-slash fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-decoration-none">
                    <div class="stats-card" style="background: linear-gradient(135deg, var(--info-color), #3dc7be);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">بدون کد</h6>
                                <div class="stats-number">{{ $stats['uncoded_positions'] ?? 0 }}</div>
                            </div>
                            <i class="fas fa-key fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @include('admin.inc.alerts')

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.office.positions.index') }}" id="filterForm">
                <div class="row">
                    <!-- Search Input -->
                    <div class="col-md-5 mb-3">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control search-input"
                                   placeholder="جستجوی بست (عنوان، شماره درجه، توضیحات)"
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" style="border-radius: {{ app()->getlocale() == 'en' ? '0 25px 25px 0' : '25px 0 0 25px' }};">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filters -->
                    <div class="col-md-4 mb-3">
                        <div class="filter-btn-group">
                            <span class="mr-2 text-muted small">وضعیت:</span>
                            <button type="button" class="btn filter-status {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}" data-status="">
                                همه
                            </button>
                            <button type="button" class="btn filter-status {{ request('status') == 'full' ? 'btn-primary' : 'btn-outline-success' }}" data-status="full">
                                پر
                            </button>
                            <button type="button" class="btn filter-status {{ request('status') == 'vacant' ? 'btn-primary' : 'btn-outline-warning' }}" data-status="vacant">
                                خالی
                            </button>
                            <button type="button" class="btn filter-status {{ request('status') == 'uncoded' ? 'btn-primary' : 'btn-outline-info' }}" data-status="uncoded">
                                بدون کد
                            </button>
                        </div>
                    </div>

                    <!-- Level Filters -->
                    <div class="col-md-3 mb-3">
                        <div class="filter-btn-group">
                            <span class="mr-2 text-muted small">درجه:</span>
                            <button type="button" class="btn btn-outline-secondary text-white filter-level" data-level="">
                                همه
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-level" data-level="3">
                                3
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-level" data-level="4">
                                4
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-level" data-level="5">
                                5
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-level" data-level="6">
                                6
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-level" data-level="7">
                                7+
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for filter submission -->
                <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                <input type="hidden" name="level" id="levelInput" value="{{ request('level') }}">
                <input type="hidden" name="per_page" id="perPageInput" value="{{ request('per_page', 25) }}">
            </form>
        </div>

        <!-- Clear Filters Button -->
        @if(request()->hasAny(['search', 'status', 'level']))
            <div class="mb-3 text-center">
                <a href="{{ route('admin.office.positions.index') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-times-circle mr-1"></i> حذف فیلترها
                </a>
                <span class="text-muted small mr-3">فیلترهای فعال:
                @if(request('search'))
                        <span class="badge badge-info">{{ request('search') }}</span>
                    @endif
                    @if(request('status') == 'full')
                        <span class="badge badge-success">پر</span>
                    @elseif(request('status') == 'vacant')
                        <span class="badge badge-warning">خالی</span>
                    @elseif(request('status') == 'uncoded')
                        <span class="badge badge-info">بدون کد</span>
                    @endif
                    @if(request('level'))
                        <span class="badge badge-primary">درجه {{ request('level') }}</span>
                    @endif
            </span>
            </div>
        @endif

        <!-- Main Content -->
        <div class="row p-2">
            <div class="col-lg-12">
                <div class="card position-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-briefcase mr-2"></i>مدیریت بست های وظیفوی
                        </h6>

                        <!-- Export and Refresh -->
                        <div>
                            <button class="btn btn-sm btn-outline-success" onclick="exportTableToCSV()">
                                <i class="fas fa-file-export mr-1"></i> خروجی CSV
                            </button>
                            <button class="btn btn-sm btn-outline-secondary ml-2" onclick="location.reload()">
                                <i class="fas fa-sync-alt mr-1"></i> بروزرسانی
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" id="positionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="list-tab" data-toggle="tab" href="#listView" role="tab">
                                    <i class="fas fa-list mr-1"></i>لیست بست ها
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="organ-tab" data-toggle="tab" href="#organ" role="tab">
                                    <i class="fas fa-sitemap mr-1"></i>ساختار سازمانی
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- List View Tab -->
                        <div class="tab-pane fade show active" id="listView" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover" id="positionsTable">
                                    <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>عنوان بست</th>
                                        <th>کدها</th>
                                        <th>درجه / تعداد</th>
                                        <th>پر شده</th>
                                        <th>موقعیت</th>
                                        <th>وضعیت</th>
                                        <th width="140">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($positions as $position)
                                        <tr>
                                            <td>
                                                <span class="badge badge-light border">{{ $position->id }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $position->title }}</strong>
                                                @if($position->desc)
                                                    <div class="text-muted small mt-1">{{ Str::limit($position->desc, 50) }}</div>
                                                @endif
                                                <div class="small text-muted">
                                                    @if($position->parent)
                                                        <i class="fas fa-level-up-alt mr-1"></i>{{ $position->parent->title }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="code-dots">
                                                    @foreach($position->codes->take(20) as $code)
                                                        <span class="code-dot {{ $code->employee ? 'filled' : 'empty' }}"
                                                              title="{{ $code->code }}: {{ $code->employee ? $code->employee->name : 'خالی' }}"
                                                              data-toggle="tooltip">
                                                    </span>
                                                    @endforeach
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    {{ $position->codes_count }} کد
                                                </div>
                                            </td>
                                            <td>
                                            <span class="badge badge-info position-badge">
                                                درجه {{ $position->position_number }}
                                            </span>
                                                <div class="small text-muted mt-1">{{ $position->num_of_pos }} بست</div>
                                            </td>
                                            <td>
                                                @php
                                                    $percentage = $position->num_of_pos > 0
                                                        ? round(($position->filled_codes_count / $position->num_of_pos) * 100)
                                                        : 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                <span class="font-weight-bold {{ $percentage >= 80 ? 'text-success' : ($percentage >= 50 ? 'text-warning' : 'text-danger') }}">
                                                    {{ $percentage }}%
                                                </span>
                                                    <div class="progress progress-thin ml-2" style="width: 60px;">
                                                        <div class="progress-bar {{ $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                             style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $position->filled_codes_count }} از {{ $position->num_of_pos }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($position->place)
                                                    <div class="font-weight-bold">{{ $position->place->name }}</div>
                                                    <div class="small text-muted">{{ $position->place->custom_code }}</div>
                                                @else
                                                    <span class="text-muted">--</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $vacantPositions = $position->num_of_pos - $position->codes_count;
                                                @endphp
                                                @if($vacantPositions > 0)
                                                    <span class="badge badge-warning position-badge">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>نیازمند
                                                </span>
                                                @elseif($position->filled_codes_count == $position->num_of_pos)
                                                    <span class="badge badge-success position-badge">
                                                    <i class="fas fa-check-circle mr-1"></i>کامل
                                                </span>
                                                @else
                                                    <span class="badge badge-info position-badge">نیمه پر</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.office.positions.show', $position->id) }}"
                                                       class="btn btn-sm btn-outline-primary" title="مشاهده">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @can('office_position_edit')
                                                        <a href="{{ route('admin.office.positions.edit', $position->id) }}"
                                                           class="btn btn-sm btn-outline-info ml-1" title="ویرایش">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @can('office_position_create')
                                                        <a href="{{ route('admin.office.positions.create') }}?parent={{ $position->id }}"
                                                           class="btn btn-sm btn-outline-success ml-1" title="افزودن زیرمجموعه">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="small text-muted">
                                    نمایش {{ $positions->firstItem() }} تا {{ $positions->lastItem() }} از {{ $positions->total() }} رکورد
                                </div>
                                <div>
                                    {{ $positions->links() }}
                                </div>
                            </div>
                        </div>

                        <!-- Organization Chart Tab -->
                        <div class="tab-pane fade" id="organ" role="tabpanel">
                            <div class="tree-container">
                                @include('admin.office.positions.inc.org_tab')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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

    <script>
        $(document).ready(function() {
            // Lazy load DataTable
            setTimeout(function() {
                var table = $('#positionsTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Persian.json"
                    },
                    "pageLength": 25,
                    "order": [[0, 'desc']],
                    "responsive": true,
                    "processing": true, // Show processing indicator
                    "serverSide": false, // Client-side processing is fine for moderate data
                    "deferRender": true, // Defer rendering for performance
                    "dom": '<"top"fl>rt<"bottom"ip><"clear">',
                    "initComplete": function() {
                        // Initialize tooltips after table is ready
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });

                // Only add buttons if needed
                if ($('.dt-buttons').length === 0) {
                    new $.fn.dataTable.Buttons(table, {
                        buttons: ['copy', 'excel', 'pdf']
                    });
                    table.buttons().container().appendTo($('#exportButtons'));
                }
            }, 500); // Delay table initialization by 500ms

            // Optimize tooltip initialization
            $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
                if (!$(this).data('bs.tooltip')) {
                    $(this).tooltip();
                }
            });

            // Defer loading of organization chart tab
            $('#organ-tab').on('shown.bs.tab', function() {
                // Load org chart content via AJAX if not loaded
                if ($('#organ .tree-container').children().length === 0) {
                    $.ajax({
                        url: '{{ route("admin.office.positions.org-chart") }}',
                        method: 'GET',
                        success: function(data) {
                            $('#organ .tree-container').html(data);
                        },
                        error: function() {
                            $('#organ .tree-container').html('<div class="alert alert-danger">خطا در بارگذاری چارت سازمانی</div>');
                        }
                    });
                }
            });

            // Simple filter button activation
            $('.filter-status, .filter-level').on('click', function() {
                const $this = $(this);
                const inputName = $this.hasClass('filter-status') ? 'status' : 'level';
                const inputValue = $this.data(inputName);

                $('#' + inputName + 'Input').val(inputValue);
                $('#filterForm').submit();
            });

            // Optimize search input
            var searchTimer;
            $('input[name="search"]').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    $('#filterForm').submit();
                }, 800); // Increased from 1000 to 800ms
            });

            // Lazy load tooltips for code dots
            $(document).on('mouseenter', '.code-dot', function() {
                const $this = $(this);
                if (!$this.data('title-initialized')) {
                    const title = $this.attr('title');
                    $this.tooltip({
                        title: title,
                        placement: 'top'
                    });
                    $this.data('title-initialized', true);
                }
            });

            // Simple hover effects without complex calculations
            $('.stats-card, .position-card').hover(
                function() {
                    $(this).css('transform', 'translateY(-3px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );
        });

        // Optimize export function
        window.exportTableToCSV = function() {
            // Show loading indicator
            const $button = $('.btn-outline-success:contains("خروجی CSV")');
            const originalText = $button.html();
            $button.html('<i class="fas fa-spinner fa-spin mr-1"></i>در حال تولید...');
            $button.prop('disabled', true);

            setTimeout(function() {
                // Simple export implementation
                const table = $('#positionsTable').DataTable();
                table.button('.buttons-excel').trigger();

                // Restore button
                setTimeout(function() {
                    $button.html(originalText);
                    $button.prop('disabled', false);
                }, 1000);
            }, 500);
        }
    </script>
@endsection
