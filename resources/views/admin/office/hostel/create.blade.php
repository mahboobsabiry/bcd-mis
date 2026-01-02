@extends('layouts.admin.master')

<!-- Title -->
@section('title', trans('pages.hostel.addNewHostel'))

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #667eea;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .input-group-prepend-custom {
            background: #e9ecef;
            border: 1px solid #ced4da;
            border-right: none;
            border-radius: 5px 0 0 5px;
            padding: 0 15px;
            display: flex;
            align-items: center;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .capacity-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
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
            background: #05a34a;
            color: white;
        }
        .capacity-dot:hover {
            transform: scale(1.1);
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
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .auto-suggest {
            font-size: 0.85rem;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.2s;
        }
        .auto-suggest:hover {
            color: #667eea;
            text-decoration: underline;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            color: #6c757d;
            transition: all 0.3s;
        }
        .step.active {
            background: #667eea;
            color: white;
            transform: scale(1.1);
        }
        .step.completed {
            background: #05a34a;
            color: white;
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
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.hostel.addNewHostel')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.office.hostel.index') }}">@lang('pages.hostel.hostel')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('global.new')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <!-- Back Button -->
                <a href="{{ route('admin.office.hostel.index') }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left mr-1"></i> @lang('global.back')
                </a>

                <!-- Quick Actions -->
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="fe fe-zap mr-1"></i> @lang('global.quickActions')
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="duplicateForm()">
                            <i class="fe fe-copy mr-2"></i> @lang('global.duplicateForm')
                        </a>
                        <a class="dropdown-item" href="#" onclick="clearForm()">
                            <i class="fe fe-refresh-cw mr-2"></i> @lang('global.clearForm')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="printForm()">
                            <i class="fe fe-printer mr-2"></i> @lang('global.print')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                </div>

                <!-- Form Card -->
                <div class="card form-card">
                    <!-- Form Header -->
                    <div class="form-section">
                        <div class="d-flex align-items-center">
                            <div class="form-icon mr-3">
                                <i class="fe fe-plus-circle"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">@lang('pages.hostel.addNewHostel')</h4>
                                <p class="mb-0 opacity-75">@lang('global.fillRequiredFields')</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @include('admin.inc.alerts')

                        <!-- Form -->
                        <form method="post" action="{{ route('admin.office.hostel.store') }}" id="hostelForm">
                            @csrf

                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="step1">
                                <h5 class="mb-4">
                                    <i class="fe fe-info mr-2"></i> @lang('global.basicInformation')
                                </h5>

                                <!-- Location -->
                                <div class="form-group">
                                    <label for="place_id" class="font-weight-semibold">
                                        @lang('global.location') <span class="text-danger">*</span>
                                    </label>
                                    <select name="place_id" id="place_id" class="form-control select2" required>
                                        <option value="">@lang('form.chooseLocation')</option>
                                        @foreach($places as $place)
                                            <option value="{{ $place->id }}"
                                                    {{ old('place_id') == $place->id ? 'selected' : '' }}
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
                                        <div class="input-group-prepend-custom">
                                            <i class="fe fe-hash"></i>
                                        </div>
                                        <input type="number" id="number" name="number"
                                               class="form-control @error('number') is-invalid @enderror"
                                               value="{{ old('number', $suggestedNumber ?? '') }}"
                                               placeholder="مثال: 101" required min="1">
                                    </div>
                                    <small class="form-text text-muted">
                                        @lang('form.roomNumberHelp')
                                        @if(isset($suggestedNumber))
                                            <br>
                                            <span class="auto-suggest" onclick="document.getElementById('number').value = '{{ $suggestedNumber }}'">
                                            <i class="fe fe-check-circle mr-1"></i>
                                            @lang('global.useSuggestedNumber', ['number' => $suggestedNumber])
                                        </span>
                                        @endif
                                    </small>
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
                                            <div class="section-badge badge badge-light {{ old('section') == $section ? 'active' : '' }}"
                                                 data-section="{{ $section }}">
                                                {{ $section }}
                                            </div>
                                        @endforeach
                                        <div class="section-badge badge badge-light {{ !in_array(old('section'), ['A','B','C','D','E']) ? 'active' : '' }}"
                                             data-section="">
                                            @lang('global.none')
                                        </div>
                                    </div>
                                    <input type="hidden" name="section" id="section" value="{{ old('section') }}">
                                    <small class="form-text text-muted">
                                        <i class="fe fe-info mr-1"></i> @lang('form.sectionHelp')
                                    </small>
                                    @error('section')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <div>
                                        <!-- Back button disabled on first step -->
                                    </div>
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        @lang('global.next') <i class="fe fe-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Capacity & Details -->
                            <div class="form-step" id="step2">
                                <h5 class="mb-4">
                                    <i class="fe fe-users mr-2"></i> @lang('global.capacityAndDetails')
                                </h5>

                                <!-- Capacity -->
                                <div class="form-group">
                                    <label for="capacity" class="font-weight-semibold">
                                        @lang('global.capacity') <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend-custom">
                                            <i class="fe fe-user"></i>
                                        </div>
                                        <input type="number" id="capacity" name="capacity"
                                               class="form-control @error('capacity') is-invalid @enderror"
                                               value="{{ old('capacity', 5) }}"
                                               min="1" max="10" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">@lang('global.persons')</span>
                                        </div>
                                    </div>

                                    <!-- Capacity Preview -->
                                    <div class="capacity-preview" id="capacityPreview"></div>

                                    <small class="form-text text-muted">
                                        <i class="fe fe-info mr-1"></i> @lang('form.capacityHelp')
                                    </small>
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
                                              placeholder="@lang('form.hostelInfoPlaceholder')">{{ old('info') }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fe fe-info mr-1"></i> @lang('form.infoHelp')
                                    </small>
                                    @error('info')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                                        <i class="fe fe-arrow-left mr-1"></i> @lang('global.previous')
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-next="3">
                                        @lang('global.next') <i class="fe fe-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Review & Submit -->
                            <div class="form-step" id="step3">
                                <h5 class="mb-4">
                                    <i class="fe fe-check-circle mr-2"></i> @lang('global.reviewAndSubmit')
                                </h5>

                                <!-- Review Card -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">@lang('global.reviewDetails')</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">@lang('global.location'):</th>
                                                <td id="reviewPlace"></td>
                                            </tr>
                                            <tr>
                                                <th>@lang('pages.hostel.roomNumber'):</th>
                                                <td id="reviewNumber"></td>
                                            </tr>
                                            <tr>
                                                <th>@lang('pages.hostel.section'):</th>
                                                <td id="reviewSection"></td>
                                            </tr>
                                            <tr>
                                                <th>@lang('global.capacity'):</th>
                                                <td id="reviewCapacity"></td>
                                            </tr>
                                            <tr>
                                                <th>@lang('global.extraInfo'):</th>
                                                <td id="reviewInfo"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Quick Info Card -->
                                <div class="info-card">
                                    <h6 class="mb-2">
                                        <i class="fe fe-alert-circle mr-2"></i> @lang('global.importantNotes')
                                    </h6>
                                    <ul class="mb-0 pl-3">
                                        <li>@lang('global.hostelCreateNote1')</li>
                                        <li>@lang('global.hostelCreateNote2')</li>
                                        <li>@lang('global.hostelCreateNote3')</li>
                                    </ul>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                                        <i class="fe fe-arrow-left mr-1"></i> @lang('global.previous')
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fe fe-save mr-1"></i> @lang('global.saveHostel')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Tips -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fe fe-lightbulb mr-2"></i> @lang('global.quickTips')
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="info-card">
                            <h6 class="mb-2">@lang('global.namingConvention')</h6>
                            <p class="small mb-0">@lang('global.hostelNamingTip')</p>
                        </div>
                        <div class="info-card">
                            <h6 class="mb-2">@lang('global.capacityPlanning')</h6>
                            <p class="small mb-0">@lang('global.capacityPlanningTip')</p>
                        </div>
                        <div class="info-card">
                            <h6 class="mb-2">@lang('global.sectionUsage')</h6>
                            <p class="small mb-0">@lang('global.sectionUsageTip')</p>
                        </div>
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
            });

            // Initialize capacity preview
            updateCapacityPreview();

            // Update capacity preview on change
            $('#capacity').on('input', function() {
                updateCapacityPreview();
                updateReview();
            });

            // Update review on form changes
            $('#place_id, #number, #section, #capacity, #info').on('change input', function() {
                updateReview();
            });

            // Step navigation
            $('.next-step').on('click', function() {
                const currentStep = $(this).closest('.form-step').attr('id').replace('step', '');
                const nextStep = $(this).data('next');

                // Validate current step
                if (validateStep(currentStep)) {
                    // Update step indicator
                    $('.step').removeClass('active');
                    $(`.step[data-step="${nextStep}"]`).addClass('active');

                    // Mark current step as completed
                    $(`.step[data-step="${currentStep}"]`).addClass('completed');

                    // Show next step
                    $('.form-step').removeClass('active');
                    $(`#step${nextStep}`).addClass('active');

                    // Scroll to top of form
                    $('html, body').animate({
                        scrollTop: $('.form-card').offset().top - 100
                    }, 500);
                }
            });

            $('.prev-step').on('click', function() {
                const currentStep = $(this).closest('.form-step').attr('id').replace('step', '');
                const prevStep = $(this).data('prev');

                // Update step indicator
                $('.step').removeClass('active');
                $(`.step[data-step="${prevStep}"]`).addClass('active');

                // Remove completed status from current step
                $(`.step[data-step="${currentStep}"]`).removeClass('completed');

                // Show previous step
                $('.form-step').removeClass('active');
                $(`#step${prevStep}`).addClass('active');

                // Scroll to top of form
                $('html, body').animate({
                    scrollTop: $('.form-card').offset().top - 100
                }, 500);
            });

            // Form submission
            $('#hostelForm').on('submit', function(e) {
                // Final validation
                if (!validateStep('3')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: '@lang('global.validationError')',
                        text: '@lang('global.pleaseFillAllFields')'
                    });
                    return false;
                }

                // Show loading
                Swal.fire({
                    title: '@lang('global.saving')',
                    text: '@lang('global.pleaseWait')',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });

        // Update capacity preview
        function updateCapacityPreview() {
            const capacity = $('#capacity').val() || 0;
            const preview = $('#capacityPreview');
            preview.empty();

            for (let i = 1; i <= 10; i++) {
                const dot = $('<div>')
                    .addClass('capacity-dot')
                    .text(i)
                    .attr('title', '@lang('global.capacityFor') ' + i + ' @lang('global.persons')');

                if (i <= capacity) {
                    dot.addClass('filled');
                }

                preview.append(dot);
            }
        }

        // Update review section
        function updateReview() {
            // Get place name
            const placeSelect = $('#place_id');
            const placeText = placeSelect.find('option:selected').text() || '@lang('global.notSelected')';
            $('#reviewPlace').text(placeText);

            // Room number
            const roomNumber = $('#number').val() || '-';
            $('#reviewNumber').text(roomNumber);

            // Section
            const section = $('#section').val() || '@lang('global.none')';
            $('#reviewSection').text(section);

            // Capacity
            const capacity = $('#capacity').val() || '0';
            $('#reviewCapacity').text(capacity + ' @lang('global.persons')');

            // Info
            const info = $('#info').val() || '@lang('global.noInfo')';
            $('#reviewInfo').text(info);
        }

        // Validate step
        function validateStep(step) {
            let isValid = true;

            switch(step) {
                case '1':
                    if (!$('#place_id').val()) {
                        showFieldError('#place_id', '@lang('global.pleaseSelectLocation')');
                        isValid = false;
                    }
                    if (!$('#number').val()) {
                        showFieldError('#number', '@lang('global.pleaseEnterRoomNumber')');
                        isValid = false;
                    }
                    break;

                case '2':
                    const capacity = $('#capacity').val();
                    if (!capacity || capacity < 1 || capacity > 10) {
                        showFieldError('#capacity', '@lang('global.capacityMustBeBetween')');
                        isValid = false;
                    }
                    break;

                case '3':
                    // Final validation - check all required fields
                    const requiredFields = ['#place_id', '#number', '#capacity'];
                    requiredFields.forEach(field => {
                        if (!$(field).val()) {
                            showFieldError(field, '@lang('global.fieldIsRequired')');
                            isValid = false;
                        }
                    });
                    break;
            }

            if (!isValid) {
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);

                Swal.fire({
                    icon: 'warning',
                    title: '@lang('global.validationError')',
                    text: '@lang('global.pleaseFixErrors')'
                });
            }

            return isValid;
        }

        // Show field error
        function showFieldError(selector, message) {
            $(selector).addClass('is-invalid');
            let errorDiv = $(selector).next('.invalid-feedback');
            if (!errorDiv.length) {
                errorDiv = $('<div>').addClass('invalid-feedback d-block').insertAfter($(selector));
            }
            errorDiv.text(message);
        }

        // Clear all field errors
        function clearFieldErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        // Duplicate form function
        function duplicateForm() {
            Swal.fire({
                title: '@lang('global.duplicateForm')',
                text: '@lang('global.duplicateFormConfirm')',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '@lang('global.yes')',
                cancelButtonText: '@lang('global.cancel')'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Store current values
                    const currentValues = {
                        place_id: $('#place_id').val(),
                        number: $('#number').val(),
                        section: $('#section').val(),
                        capacity: $('#capacity').val(),
                        info: $('#info').val()
                    };

                    // Clear form
                    $('#hostelForm')[0].reset();
                    $('.section-badge').removeClass('active');
                    $('.section-badge[data-section=""]').addClass('active');

                    // Restore values
                    setTimeout(() => {
                        $('#place_id').val(currentValues.place_id).trigger('change');
                        $('#number').val(currentValues.number);
                        $('#section').val(currentValues.section);
                        $('#capacity').val(currentValues.capacity);
                        $('#info').val(currentValues.info);

                        // Update UI
                        updateCapacityPreview();
                        updateReview();

                        // Go to step 1
                        $('.step').removeClass('active completed');
                        $('.step[data-step="1"]').addClass('active');
                        $('.form-step').removeClass('active');
                        $('#step1').addClass('active');

                        Swal.fire({
                            icon: 'success',
                            title: '@lang('global.formDuplicated')',
                            text: '@lang('global.duplicateFormSuccess')',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, 100);
                }
            });
        }

        // Clear form function
        function clearForm() {
            Swal.fire({
                title: '@lang('global.clearForm')',
                text: '@lang('global.clearFormConfirm')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: '@lang('global.clear')',
                cancelButtonText: '@lang('global.cancel')'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#hostelForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                    $('.section-badge').removeClass('active');
                    $('.section-badge[data-section=""]').addClass('active');
                    $('#section').val('');

                    // Reset to step 1
                    $('.step').removeClass('active completed');
                    $('.step[data-step="1"]').addClass('active');
                    $('.form-step').removeClass('active');
                    $('#step1').addClass('active');

                    // Update UI
                    updateCapacityPreview();
                    updateReview();
                    clearFieldErrors();

                    Swal.fire({
                        icon: 'success',
                        title: '@lang('global.formCleared')',
                        text: '@lang('global.formClearedSuccess')',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Print form function
        function printForm() {
            const printContent = `
                <!DOCTYPE html>
                <html lang="fa">
                <head>
                    <meta charset="UTF-8">
                    <title>@lang('pages.hostel.addNewHostel') - چاپ فرم</title>
                    <style>
                        body { font-family: Tahoma, Arial, sans-serif; direction: rtl; padding: 20px; }
                        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                        .print-section { margin-bottom: 20px; }
                        .print-label { font-weight: bold; color: #333; margin-bottom: 5px; }
                        .print-value { padding: 8px; background: #f5f5f5; border-radius: 5px; margin-bottom: 15px; }
                        .print-footer { text-align: center; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2>@lang('pages.hostel.addNewHostel')</h2>
                        <p>تاریخ چاپ: ${new Date().toLocaleDateString('fa-IR')}</p>
                    </div>

                    <div class="print-section">
                        <div class="print-label">@lang('global.location'):</div>
                        <div class="print-value" id="printPlace"></div>

                        <div class="print-label">@lang('pages.hostel.roomNumber'):</div>
                        <div class="print-value" id="printNumber"></div>

                        <div class="print-label">@lang('pages.hostel.section'):</div>
                        <div class="print-value" id="printSection"></div>

                        <div class="print-label">@lang('global.capacity'):</div>
                        <div class="print-value" id="printCapacity"></div>

                        <div class="print-label">@lang('global.extraInfo'):</div>
                        <div class="print-value" id="printInfo"></div>
                    </div>

                    <div class="print-footer">
                        <p>چاپ شده از سیستم مدیریت خوابگاه گمرک افغانستان</p>
                    </div>

                    <script>
                        // Fill print values
                        document.getElementById('printPlace').textContent = document.querySelector('#place_id option:checked').textContent || 'انتخاب نشده';
                        document.getElementById('printNumber').textContent = document.getElementById('number').value || '-';
                        document.getElementById('printSection').textContent = document.getElementById('section').value || 'ندارد';
                        document.getElementById('printCapacity').textContent = (document.getElementById('capacity').value || '0') + ' نفر';
                        document.getElementById('printInfo').textContent = document.getElementById('info').value || 'اطلاعاتی وارد نشده است';
                    <\/script>
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        // Initialize review on page load
        updateReview();
    </script>
@endsection
