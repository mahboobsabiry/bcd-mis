@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('pages.hostel.editHostel', ['number' => $hostel->number]))

<!-- Extra Styles -->
@section('extra_css')
    <!-- Select2 -->
    <link href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/sweet-alert/sweetalert2.css') }}">

    <!-- Custom CSS -->
    <style>
        .form-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .form-section {
            background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px 10px 0 0;
            margin-bottom: 2rem;
        }
        .form-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .info-card {
            background: #f8f9fa;
            border-left: 4px solid #ff9800;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .current-info {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .capacity-warning {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .section-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .section-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .section-badge.active {
            background: #ff9800;
            color: white;
            border-color: #ff9800;
        }
        .capacity-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s;
        }
        .capacity-dot.filled {
            background: #4caf50;
            color: white;
        }
        .capacity-dot.occupied {
            background: #ff9800;
            color: white;
        }
        .capacity-dot.over-capacity {
            background: #f44336;
            color: white;
        }
        .change-highlight {
            background-color: #fffde7;
            border: 1px dashed #ffd600;
            padding: 0.5rem;
            border-radius: 5px;
            margin-top: 0.5rem;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.hostel.editHostel', ['number' => $hostel->number])</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.hostel.index') }}">@lang('pages.hostel.hostel')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.hostel.show', $hostel->id) }}">@lang('global.details')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('global.edit')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Back Button -->
                <a href="{{ route('admin.office.hostel.show', $hostel->id) }}" class="btn btn-outline-secondary mr-2">
                    <i class="fe fe-arrow-left mr-1"></i> @lang('global.back')
                </a>

                <!-- View Details -->
                <a href="{{ route('admin.office.hostel.show', $hostel->id) }}" class="btn btn-info mr-2">
                    <i class="fe fe-eye mr-1"></i> @lang('global.view')
                </a>

                <!-- Compare Button -->
                <button type="button" class="btn btn-outline-primary" onclick="compareChanges()">
                    <i class="fe fe-git-compare mr-1"></i> @lang('global.compareChanges')
                </button>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Form Card -->
                <div class="card form-card">
                    <!-- Form Header -->
                    <div class="form-section">
                        <div class="d-flex align-items-center">
                            <div class="form-icon mr-3">
                                <i class="fe fe-edit-2"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">@lang('pages.hostel.editHostel', ['number' => $hostel->number])</h4>
                                <p class="mb-0 opacity-75">@lang('global.updateHostelInfo')</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @include('admin.inc.alerts')

                        <!-- Current Information -->
                        <div class="current-info">
                            <h6 class="mb-3">
                                <i class="fe fe-info mr-2"></i> @lang('global.currentInformation')
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">@lang('global.location'):</small>
                                    <strong>{{ $hostel->place->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">@lang('global.occupancy'):</small>
                                    <strong>{{ $hostel->employees->count() }}/{{ $hostel->capacity }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Capacity Warning -->
                        @if($hostel->employees->count() > 0)
                            <div class="capacity-warning">
                                <h6 class="mb-2">
                                    <i class="fe fe-alert-triangle mr-2"></i> @lang('global.importantNote')
                                </h6>
                                <p class="small mb-0">
                                    @lang('global.currentOccupantsWarning', [
                                        'count' => $hostel->employees->count(),
                                        'names' => $hostel->employees->pluck('name')->implode(', ')
                                    ])
                                </p>
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="post" action="{{ route('admin.office.hostel.update', $hostel->id) }}" id="hostelForm">
                            @csrf
                            @method('PUT')

                            <!-- Location -->
                            <div class="form-group">
                                <label for="place_id" class="font-weight-semibold">
                                    @lang('global.location') <span class="text-danger">*</span>
                                </label>
                                <select name="place_id" id="place_id" class="form-control select2" required>
                                    <option value="">@lang('form.chooseLocation')</option>
                                    @foreach($places as $place)
                                        <option value="{{ $place->id }}"
                                                {{ old('place_id', $hostel->place_id) == $place->id ? 'selected' : '' }}
                                                data-code="{{ $place->code }}">
                                            {{ $place->name }} ({{ $place->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fe fe-info mr-1"></i> @lang('form.locationHelp')
                                </small>
                                @error('place_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Room Number -->
                            <div class="form-group">
                                <label for="number" class="font-weight-semibold">
                                    @lang('pages.hostel.roomNumber') <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fe fe-hash"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="number" name="number"
                                           class="form-control @error('number') is-invalid @enderror"
                                           value="{{ old('number', $hostel->number) }}"
                                           placeholder="مثال: 101" required min="1">
                                </div>
                                @error('number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Section Selection -->
                            <div class="form-group">
                                <label class="font-weight-semibold d-block mb-2">
                                    @lang('pages.hostel.section')
                                </label>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach(['A', 'B', 'C', 'D', 'E'] as $section)
                                        <div class="section-badge badge badge-light {{ old('section', $hostel->section) == $section ? 'active' : '' }}"
                                             data-section="{{ $section }}">
                                            {{ $section }}
                                        </div>
                                    @endforeach
                                    <div class="section-badge badge badge-light {{ !in_array(old('section', $hostel->section), ['A','B','C','D','E']) ? 'active' : '' }}"
                                         data-section="">
                                        @lang('global.none')
                                    </div>
                                </div>
                                <input type="hidden" name="section" id="section" value="{{ old('section', $hostel->section) }}">
                                <small class="form-text text-muted">
                                    <i class="fe fe-info mr-1"></i> @lang('form.sectionHelp')
                                </small>
                                @error('section')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="form-group">
                                <label for="capacity" class="font-weight-semibold">
                                    @lang('global.capacity') <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fe fe-users"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="capacity" name="capacity"
                                           class="form-control @error('capacity') is-invalid @enderror"
                                           value="{{ old('capacity', $hostel->capacity) }}"
                                           min="1" max="10" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('global.persons')</span>
                                    </div>
                                </div>

                                <!-- Capacity Visualization -->
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">@lang('global.capacityVisualization'):</small>
                                        <small class="text-muted" id="capacityStatus"></small>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2" id="capacityVisualization"></div>
                                </div>

                                @error('capacity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Additional Information -->
                            <div class="form-group">
                                <label for="info" class="font-weight-semibold">
                                    @lang('global.extraInfo')
                                </label>
                                <textarea name="info" id="info"
                                          class="form-control @error('info') is-invalid @enderror"
                                          rows="4"
                                          placeholder="@lang('form.hostelInfoPlaceholder')">{{ old('info', $hostel->info) }}</textarea>
                                @error('info')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="font-weight-semibold d-block mb-2">
                                    @lang('form.status')
                                </label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status"
                                           name="status" value="1" {{ old('status', $hostel->status) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">
                                        @lang('global.active')
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fe fe-info mr-1"></i> @lang('form.statusHelp')
                                </small>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <button type="button" class="btn btn-outline-danger" onclick="resetForm()">
                                        <i class="fe fe-refresh-cw mr-1"></i> @lang('global.reset')
                                    </button>
                                </div>
                                <div>
                                    <a href="{{ route('admin.office.hostel.show', $hostel->id) }}"
                                       class="btn btn-secondary mr-2">
                                        @lang('global.cancel')
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fe fe-save mr-1"></i> @lang('global.update')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Hostel Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fe fe-home mr-2"></i> @lang('global.hostelSummary')
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-xl bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fe fe-home tx-24"></i>
                            </div>
                            <h5>اتاق {{ $hostel->number }}</h5>
                            @if($hostel->section)
                                <span class="badge badge-secondary">{{ $hostel->section }}</span>
                            @endif
                            <p class="text-muted mb-2">{{ $hostel->place->name ?? '' }}</p>
                        </div>

                        <div class="info-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-semibold">@lang('global.occupancy'):</span>
                                <span class="badge badge-{{ $hostel->employees->count() >= $hostel->capacity ? 'danger' : 'success' }}">
                                    {{ $hostel->employees->count() }}/{{ $hostel->capacity }}
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $occupancyPercent = $hostel->capacity > 0 ?
                                        round(($hostel->employees->count() / $hostel->capacity) * 100, 0) : 0;
                                @endphp
                                <div class="progress-bar
                                    @if($occupancyPercent >= 90) bg-danger
                                    @elseif($occupancyPercent >= 70) bg-warning
                                    @else bg-success @endif"
                                     style="width: {{ $occupancyPercent }}%"></div>
                            </div>
                        </div>

                        <!-- Current Occupants -->
                        @if($hostel->employees->count() > 0)
                            <div class="mt-3">
                                <h6 class="mb-2">@lang('global.currentOccupants'):</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($hostel->employees as $employee)
                                        <a href="{{ route('admin.office.employees.show', $employee->id) }}"
                                           class="d-flex align-items-center" data-toggle="tooltip"
                                           title="{{ $employee->name }} {{ $employee->last_name }}">
                                            <img src="{{ $employee->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                                 class="rounded-circle mr-1" width="30" height="30">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="mt-4">
                            <a href="{{ route('admin.office.hostel.show', $hostel->id) }}#employees"
                               class="btn btn-outline-info btn-sm btn-block mb-2">
                                <i class="fe fe-users mr-1"></i> @lang('global.manageOccupants')
                            </a>
                            <a href="{{ route('admin.places.show', $hostel->place_id) }}"
                               class="btn btn-outline-primary btn-sm btn-block">
                                <i class="fe fe-map-pin mr-1"></i> @lang('global.viewLocation')
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Change History -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fe fe-clock mr-2"></i> @lang('global.recentChanges')
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($hostel->updated_at != $hostel->created_at)
                            <div class="small text-muted mb-2">
                                @lang('global.lastUpdated'): {{ $hostel->updated_at->diffForHumans() }}
                            </div>
                        @endif
                        <div class="small text-muted">
                            @lang('global.created'): {{ $hostel->created_at->format('Y-m-d') }}
                        </div>
                        <!-- You can add actual change history here if you have an activity log -->
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Main Content -->
    </div>
@endsection

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Select2 -->
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('backend/assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>

    <!-- Custom Scripts -->
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "@lang('form.chooseLocation')",
                allowClear: true,
                width: '100%'
            });

            // Section selection
            $('.section-badge').on('click', function() {
                $('.section-badge').removeClass('active');
                $(this).addClass('active');
                const section = $(this).data('section');
                $('#section').val(section);
                showChangeHighlight('#section', section);
            });

            // Initialize capacity visualization
            updateCapacityVisualization();

            // Update capacity visualization on change
            $('#capacity').on('input', function() {
                updateCapacityVisualization();
                showChangeHighlight('#capacity', $(this).val());
            });

            // Track changes for other fields
            $('#place_id, #number, #info').on('change', function() {
                showChangeHighlight(this.id, $(this).val());
            });

            $('#status').on('change', function() {
                showChangeHighlight('#status', this.checked ? '1' : '0');
            });

            // Form submission validation
            $('#hostelForm').on('submit', function(e) {
                const newCapacity = parseInt($('#capacity').val());
                const currentOccupants = parseInt('{{ $hostel->employees->count() }}');

                // Check if new capacity is less than current occupants
                if (newCapacity < currentOccupants) {
                    e.preventDefault();

                    // Get employee names
                    const employeeNames = '{{ addslashes($hostel->employees->pluck("name")->implode(", ")) }}';

                    Swal.fire({
                        icon: 'error',
                        title: '@lang('global.capacityError')',
                        html: `@lang('global.capacityErrorMessage')
                        <br><br>
                        <strong>@lang('global.currentOccupants'):</strong> ${currentOccupants}
                           <br>
                           <strong>@lang('global.newCapacity'):</strong> ${newCapacity}
                           <br>
                           <strong>@lang('global.affectedEmployees'):</strong> ${employeeNames}`,
                        showCancelButton: true,
                        confirmButtonText: '@lang('global.continueAnyway')',
                        cancelButtonText: '@lang('global.cancel')'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form anyway
                            $('#hostelForm').off('submit').submit();
                        }
                    });
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Update capacity visualization
        function updateCapacityVisualization() {
            const capacity = $('#capacity').val() || 0;
            const currentOccupants = parseInt('{{ $hostel->employees->count() }}');
            const visualization = $('#capacityVisualization');
            const status = $('#capacityStatus');

            visualization.empty();

            let statusText = '';
            let statusClass = 'text-success';

            if (capacity < currentOccupants) {
                statusText = '@lang('global.overCapacity')';
                statusClass = 'text-danger';
            } else if (currentOccupants === 0) {
                statusText = '@lang('global.empty')';
                statusClass = 'text-success';
            } else if (currentOccupants === capacity) {
                statusText = '@lang('global.full')';
                statusClass = 'text-warning';
            } else {
                statusText = '@lang('global.partial')';
                statusClass = 'text-info';
            }

            status.text(statusText).removeClass('text-success text-danger text-warning text-info').addClass(statusClass);

            for (let i = 1; i <= 10; i++) {
                const dot = $('<div>')
                    .addClass('capacity-dot')
                    .text(i)
                    .attr('title', '@lang('global.capacityFor') ' + i + ' @lang('global.persons')');

                if (i <= currentOccupants) {
                    dot.addClass('occupied');
                    if (i > capacity) {
                        dot.addClass('over-capacity');
                        dot.attr('title', '@lang('global.overCapacityWarning')');
                    }
                } else if (i <= capacity) {
                    dot.addClass('filled');
                }

                visualization.append(dot);
            }
        }

        // Show change highlight
        function showChangeHighlight(fieldId, newValue) {
            // Remove existing highlight
            $(`#${fieldId}`).next('.change-highlight').remove();

            const originalValues = {
                'place_id': '{{ $hostel->place_id }}',
                'number': '{{ $hostel->number }}',
                'section': '{{ $hostel->section }}',
                'capacity': '{{ $hostel->capacity }}',
                'info': '{{ addslashes($hostel->info) }}',
                'status': '{{ $hostel->status ? '1' : '0' }}'
            };

            const fieldNames = {
                'place_id': '@lang('global.location')',
                'number': '@lang('pages.hostel.roomNumber')',
                'section': '@lang('pages.hostel.section')',
                'capacity': '@lang('global.capacity')',
                'info': '@lang('global.extraInfo')',
                'status': '@lang('form.status')'
            };

            const cleanFieldId = fieldId.replace('#', '');

            if (originalValues[cleanFieldId] != newValue) {
                let displayValue = newValue;

                // Special handling for place_id
                if (cleanFieldId === 'place_id') {
                    const selectedOption = $(`#${cleanFieldId} option:selected`);
                    displayValue = selectedOption.text() || newValue;
                }

                // Special handling for status
                if (cleanFieldId === 'status') {
                    displayValue = newValue === '1' ? '@lang('global.active')' : '@lang('global.inactive')';
                }

                const highlight = $(`
                <div class="change-highlight">
                    <small class="text-success">
                        <i class="fe fe-edit-2 mr-1"></i>
                        ${fieldNames[cleanFieldId]}: ${displayValue}
                    </small>
                </div>
            `);

                $(`#${cleanFieldId}`).after(highlight);
            }
        }

        // Compare changes
        function compareChanges() {
            const originalValues = {
                'place': '{{ $hostel->place->name ?? "N/A" }}',
                'number': '{{ $hostel->number }}',
                'section': '{{ $hostel->section ?: trans("global.none") }}',
                'capacity': '{{ $hostel->capacity }}',
                'info': '{{ addslashes($hostel->info ?: trans("global.noInfo")) }}',
                'status': '{{ $hostel->status ? trans("global.active") : trans("global.inactive") }}'
            };

            const currentValues = {
                'place': $('#place_id option:selected').text() || '@lang('global.notSelected')',
                'number': $('#number').val() || '-',
                'section': $('#section').val() || '@lang('global.none')',
                'capacity': $('#capacity').val() || '0',
                'info': $('#info').val() || '@lang('global.noInfo')',
                'status': $('#status').is(':checked') ? '@lang('global.active')' : '@lang('global.inactive')'
            };

            let changesHtml = `
            <div class="table-responsive">
                <table class="table table-sm table-borderless">
                    <thead>
                        <tr>
                            <th>@lang('global.field')</th>
                            <th>@lang('global.original')</th>
                            <th>@lang('global.new')</th>
                            <th>@lang('global.status')</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

            Object.keys(originalValues).forEach(field => {
                const original = originalValues[field];
                const current = currentValues[field];
                const hasChanged = original != current;

                changesHtml += `
                <tr>
                    <td>${getFieldName(field)}</td>
                    <td>${original}</td>
                    <td>${current}</td>
                    <td>
                        ${hasChanged ?
                    '<span class="badge badge-warning">@lang("global.changed")</span>' :
                    '<span class="badge badge-success">@lang("global.unchanged")</span>'
                }
                    </td>
                </tr>
            `;
            });

            changesHtml += `
                    </tbody>
                </table>
            </div>
        `;

            Swal.fire({
                title: '@lang('global.changesComparison')',
                html: changesHtml,
                width: '800px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }

        function getFieldName(field) {
            const fieldNames = {
                'place': '@lang('global.location')',
                'number': '@lang('pages.hostel.roomNumber')',
                'section': '@lang('pages.hostel.section')',
                'capacity': '@lang('global.capacity')',
                'info': '@lang('global.extraInfo')',
                'status': '@lang('form.status')'
            };
            return fieldNames[field] || field;
        }

        // Reset form to original values
        function resetForm() {
            Swal.fire({
                title: '@lang('global.resetForm')',
                text: '@lang('global.resetFormConfirm')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: '@lang('global.reset')',
                cancelButtonText: '@lang('global.cancel')'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset form values
                    $('#place_id').val('{{ $hostel->place_id }}').trigger('change');
                    $('#number').val('{{ $hostel->number }}');
                    $('#section').val('{{ $hostel->section }}');
                    $('#capacity').val('{{ $hostel->capacity }}');
                    $('#info').val(`{{ addslashes($hostel->info) }}`);
                    $('#status').prop('checked', {{ $hostel->status ? 'true' : 'false' }});

                    // Reset section badges
                    $('.section-badge').removeClass('active');
                    const section = '{{ $hostel->section }}';
                    if (['A','B','C','D','E'].includes(section)) {
                        $(`.section-badge[data-section="${section}"]`).addClass('active');
                    } else {
                        $(`.section-badge[data-section=""]`).addClass('active');
                    }

                    // Update UI
                    updateCapacityVisualization();

                    // Remove change highlights
                    $('.change-highlight').remove();

                    Swal.fire({
                        icon: 'success',
                        title: '@lang('global.formReset')',
                        text: '@lang('global.formResetSuccess')',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Initialize on page load
        updateCapacityVisualization();
    </script>
@endsection
