@extends('layouts.admin.master')
<!-- Title -->
@section('title', trans('pages.positions.addPosition'))
<!-- Extra Styles -->
@section('extra_css')
    <link href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        .form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
        }

        .section-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), #6c5ce7);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
        }

        .section-title {
            font-weight: 600;
            color: #3a3b45;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label .required {
            color: var(--danger-color);
            margin-right: 0.25rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.75rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .code-input-container {
            border: 2px dashed #d1d3e2;
            border-radius: 8px;
            padding: 1.5rem;
            background-color: #f8f9fc;
        }

        .code-input-group {
            margin-bottom: 0.75rem;
            transition: all 0.3s;
        }

        .code-input-group:last-child {
            margin-bottom: 0;
        }

        .code-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #e7f4ff;
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.875rem;
            margin: 0.125rem;
            border: 1px solid #cce5ff;
        }

        .code-preview {
            min-height: 60px;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 1rem;
            background-color: #fff;
            margin-top: 1rem;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #e3e6f0;
            margin-top: 2rem;
            z-index: 100;
            border-radius: 0 0 10px 10px;
        }

        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .info-alert {
            background-color: #e7f4ff;
            border: 1px solid #cce5ff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .info-alert i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            margin-top: 0.25rem;
        }

        .number-input-hint {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-icon {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection
<!--/==/ End of Extra Styles -->

<!-- Main Content of The Page -->
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Breadcrumb -->
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('pages.positions.addPosition')</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.office.positions.index') }}">@lang('admin.sidebar.positions')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('pages.positions.addPosition')</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">
                <a class="btn btn-outline-secondary btn-sm" href="{{ url()->previous() }}">
                    <i class="fe fe-arrow-left"></i> @lang('global.back')
                </a>

                <button type="button" class="btn btn-outline-info btn-sm" id="previewBtn">
                    <i class="fas fa-eye"></i> پیش نمایش
                </button>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Errors Message -->
        @include('admin.inc.alerts')

        <div class="row">
            <div class="col-lg-12">
                <div class="form-card">
                    <div class="card-body">
                        <!-- Info Alert -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle fa-lg"></i>
                            <div>
                                <strong>راهنمای ایجاد بست جدید</strong>
                                <p class="mb-0">برای ایجاد بست جدید، لطفا تمام فیلدهای ضروری را با دقت پر کنید. کدهای بست باید منحصر به فرد باشند.</p>
                            </div>
                        </div>

                        <!-- Form -->
                        <form method="post" action="{{ route('admin.office.positions.store') }}" id="positionForm" data-parsley-validate="">
                            @csrf

                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">اطلاعات پایه</h3>
                                        <div class="section-subtitle">معلومات اصلی بست وظیفوی</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Parent Position -->
                                        <div class="form-group @error('parent_id') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-level-up-alt mr-2 text-primary"></i>
                                                @lang('pages.positions.underHand')
                                            </label>
                                            <select id="parent_id" name="parent_id" class="form-control select2 @error('parent_id') is-invalid @enderror" required>
                                                <option value="">انتخاب بست مافوق...</option>
                                                <option value="">ریاست (بدون مافوق)</option>
                                                @foreach($positions as $position)
                                                    <option value="{{ $position->id }}" {{ old('parent_id') == $position->id ? 'selected' : '' }}>
                                                        {{ $position->title }}
                                                        @if($position->place)
                                                            <small class="text-muted">({{ $position->place->name }})</small>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">بست مافوق این موقعیت را انتخاب کنید</div>
                                        </div>

                                        <!-- Title -->
                                        <div class="form-group @error('title') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-heading mr-2 text-primary"></i>
                                                @lang('form.title')
                                            </label>
                                            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                                                   name="title" value="{{ old('title') }}" required
                                                   placeholder="عنوان بست وظیفوی" maxlength="255">
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">عنوان کامل بست را وارد کنید (حداکثر ۲۵۵ کاراکتر)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Place -->
                                        <div class="form-group @error('place_id') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                                موقعیت
                                            </label>
                                            <select id="place_id" name="place_id" class="form-control select2 @error('place_id') is-invalid @enderror" required>
                                                <option value="">انتخاب موقعیت...</option>
                                                @foreach($places as $place)
                                                    <option value="{{ $place->id }}" {{ old('place_id') == $place->id ? 'selected' : '' }}>
                                                        {{ $place->name }}
                                                        @if($place->custom_code)
                                                            <small class="text-muted">({{ $place->custom_code }})</small>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('place_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">موقعیت جغرافیایی یا اداری بست را انتخاب کنید</div>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group @error('desc') has-danger @enderror">
                                            <label class="form-label">
                                                <i class="fas fa-sticky-note mr-2 text-primary"></i>
                                                @lang('form.extraInfo')
                                            </label>
                                            <textarea name="desc" class="form-control @error('desc') is-invalid @enderror"
                                                      rows="3" placeholder="توضیحات اضافی درباره این بست">{{ old('desc') }}</textarea>
                                            @error('desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">هرگونه توضیحات اضافی درباره مسئولیت‌ها یا ویژگی‌های این بست</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Position Details Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">جزئیات موقعیت</h3>
                                        <div class="section-subtitle">تنظیمات درجه و تعداد بست</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Position Number (Grade) -->
                                        <div class="form-group @error('position_number') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-sort-numeric-up mr-2 text-primary"></i>
                                                @lang('pages.positions.positionNumber')
                                            </label>
                                            <div class="position-relative">
                                                <input type="number" id="position_number" class="form-control @error('position_number') is-invalid @enderror"
                                                       name="position_number" value="{{ old('position_number', 1) }}" required
                                                       min="1" max="10" placeholder="درجه بست">
                                                <span class="number-input-hint">۱ تا ۱۰</span>
                                            </div>
                                            @error('position_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">درجه بست را مشخص کنید (۱ = پایین‌ترین، ۱۰ = بالاترین)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Number of Positions -->
                                        <div class="form-group @error('num_of_pos') has-danger @enderror">
                                            <label class="form-label">
                                                <span class="required">*</span>
                                                <i class="fas fa-layer-group mr-2 text-primary"></i>
                                                @lang('form.num_of_pos')
                                            </label>
                                            <input type="number" id="num_of_pos" class="form-control @error('num_of_pos') is-invalid @enderror"
                                                   name="num_of_pos" value="{{ old('num_of_pos', 1) }}" required
                                                   min="1" max="50" placeholder="تعداد بست‌ها">
                                            @error('num_of_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="help-text">تعداد کل بست‌های مجاز برای این موقعیت</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Position Codes Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div>
                                        <h3 class="section-title">کدهای بست</h3>
                                        <div class="section-subtitle">تعریف کدهای منحصر به فرد برای بست‌ها</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <span class="required">*</span>
                                        <i class="fas fa-hashtag mr-2 text-primary"></i>
                                        کدهای بست
                                    </label>
                                    <div class="code-input-container">
                                        <div id="codeInputsContainer">
                                            <!-- Initial code input -->
                                            <div class="code-input-group">
                                                <div class="input-group">
                                                    <input type="text" name="codes[]"
                                                           class="form-control code-input"
                                                           placeholder="مثال: P001"
                                                           data-index="0"
                                                           required>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-success add-code-btn" title="افزودن کد جدید">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Code Preview -->
                                        <div class="code-preview">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">پیش نمایش کدها:</span>
                                                <span class="badge badge-light" id="codeCount">۱ کد</span>
                                            </div>
                                            <div id="codePreview"></div>
                                        </div>
                                    </div>

                                    <div class="help-text">
                                        کدهای منحصر به فرد برای هر بست وارد کنید. حداقل یک کد الزامی است.
                                    </div>

                                    <!-- Hidden field for JSON codes -->
                                    <input type="hidden" name="codes_json" id="codesJson">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger">
                                            <i class="fas fa-redo"></i> پاک کردن فرم
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.office.positions.index') }}" class="btn btn-outline-secondary mr-2">
                                            <i class="fas fa-times"></i> انصراف
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> ذخیره بست جدید
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap',
                width: '100%',
                placeholder: 'انتخاب کنید...',
                allowClear: true
            });

            let codeIndex = 0;
            const codes = [];

            // Initialize first code input
            updateCodePreview();

            // Add new code input
            $(document).on('click', '.add-code-btn', function() {
                codeIndex++;
                const newInput = `
                    <div class="code-input-group" data-index="${codeIndex}">
                        <div class="input-group">
                            <input type="text" name="codes[]"
                                   class="form-control code-input"
                                   placeholder="مثال: P${String(codeIndex + 1).padStart(3, '0')}"
                                   data-index="${codeIndex}"
                                   required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-code-btn" title="حذف این کد">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#codeInputsContainer').append(newInput);
                updateCodeCount();
            });

            // Remove code input
            $(document).on('click', '.remove-code-btn', function() {
                const $inputGroup = $(this).closest('.code-input-group');
                const index = $inputGroup.data('index');

                // Don't remove if it's the last input
                if ($('.code-input-group').length > 1) {
                    $inputGroup.remove();
                    removeCodeFromArray(index);
                    updateCodeCount();
                    updateCodePreview();
                } else {
                    showToast('حداقل یک کد باید وجود داشته باشد', 'warning');
                }
            });

            // Update code array on input change
            $(document).on('keyup', '.code-input', function() {
                const index = $(this).data('index');
                const value = $(this).val().trim();

                if (value) {
                    updateCodeInArray(index, value);
                } else {
                    removeCodeFromArray(index);
                }
                updateCodePreview();
            });

            // Form validation before submit
            $('#positionForm').on('submit', function(e) {
                // Validate at least one code
                const validCodes = codes.filter(code => code.value);
                if (validCodes.length === 0) {
                    e.preventDefault();
                    showToast('حداقل یک کد معتبر وارد کنید', 'danger');
                    return false;
                }

                // Validate unique codes
                const codeValues = validCodes.map(code => code.value);
                const uniqueCodes = [...new Set(codeValues)];
                if (uniqueCodes.length !== codeValues.length) {
                    e.preventDefault();
                    showToast('کدهای وارد شده باید منحصر به فرد باشند', 'danger');
                    return false;
                }

                // Update JSON field
                $('#codesJson').val(JSON.stringify(validCodes));

                // Validate num_of_pos vs number of codes
                const numOfPos = parseInt($('#num_of_pos').val()) || 0;
                if (validCodes.length > numOfPos) {
                    e.preventDefault();
                    showToast('تعداد کدها نمی‌تواند بیشتر از تعداد بست‌ها باشد', 'danger');
                    return false;
                }
            });

            // Preview button
            $('#previewBtn').on('click', function() {
                const formData = {
                    parent: $('#parent_id option:selected').text(),
                    title: $('#title').val(),
                    place: $('#place_id option:selected').text(),
                    grade: $('#position_number').val(),
                    totalPositions: $('#num_of_pos').val(),
                    description: $('#desc').val(),
                    codes: codes.filter(c => c.value)
                };

                showPreview(formData);
            });

            // Auto-suggest code based on title and place
            $('#title, #place_id').on('change', function() {
                autoGenerateCodes();
            });

            // Helper functions
            function updateCodeInArray(index, value) {
                const existingIndex = codes.findIndex(code => code.index == index);
                if (existingIndex > -1) {
                    codes[existingIndex].value = value;
                } else {
                    codes.push({ index: index, value: value });
                }
            }

            function removeCodeFromArray(index) {
                const codeIndex = codes.findIndex(code => code.index == index);
                if (codeIndex > -1) {
                    codes.splice(codeIndex, 1);
                }
            }

            function updateCodeCount() {
                const count = $('.code-input-group').length;
                $('#codeCount').text(count + ' کد');
            }

            function updateCodePreview() {
                const $preview = $('#codePreview');
                $preview.empty();

                const validCodes = codes.filter(code => code.value);
                if (validCodes.length === 0) {
                    $preview.html('<span class="text-muted">کدی وارد نشده است</span>');
                    return;
                }

                validCodes.forEach(code => {
                    $preview.append(`<span class="code-badge">${code.value}</span>`);
                });
            }

            function autoGenerateCodes() {
                const title = $('#title').val();
                const place = $('#place_id option:selected').text();
                const numOfPos = parseInt($('#num_of_pos').val()) || 1;

                if (title && place && $('.code-input').length === 1 && !$('.code-input').first().val()) {
                    // Generate suggested codes
                    const placeCode = place.substring(0, 2).toUpperCase();
                    const titleCode = title.substring(0, 2).toUpperCase();

                    // Clear existing codes
                    codes.length = 0;

                    // Update first input
                    const firstInput = $('.code-input').first();
                    const suggestedCode = `${placeCode}${titleCode}001`;
                    firstInput.val(suggestedCode);
                    updateCodeInArray(0, suggestedCode);

                    // Add additional inputs if needed
                    for (let i = 1; i < numOfPos; i++) {
                        $('.add-code-btn').click();
                        const codeNumber = String(i + 1).padStart(3, '0');
                        const code = `${placeCode}${titleCode}${codeNumber}`;
                        $(`.code-input[data-index="${i}"]`).val(code);
                        updateCodeInArray(i, code);
                    }

                    updateCodePreview();
                    updateCodeCount();
                }
            }

            function showToast(message, type = 'info') {
                // Simple toast implementation
                const toast = $(`
                    <div class="toast-alert alert alert-${type} alert-dismissible fade show position-fixed"
                         style="bottom: 20px; left: 20px; z-index: 1050;">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                `);
                $('body').append(toast);
                setTimeout(() => toast.alert('close'), 5000);
            }

            function showPreview(data) {
                // Create preview modal
                const previewHtml = `
                    <div class="modal fade" id="previewModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">پیش نمایش بست جدید</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>عنوان:</strong> ${data.title || '-'}</p>
                                            <p><strong>بست مافوق:</strong> ${data.parent || 'ریاست'}</p>
                                            <p><strong>موقعیت:</strong> ${data.place || '-'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>درجه:</strong> ${data.grade || '1'}</p>
                                            <p><strong>تعداد بست‌ها:</strong> ${data.totalPositions || '1'}</p>
                                            <p><strong>کدها:</strong> ${data.codes.length} کد</p>
                                        </div>
                                    </div>
                                    ${data.description ? `<p><strong>توضیحات:</strong><br>${data.description}</p>` : ''}
                                    ${data.codes.length > 0 ? `
                                        <div class="mt-3">
                                            <strong>کدهای تعریف شده:</strong>
                                            <div class="mt-2">${data.codes.map(code => `<span class="code-badge">${code.value}</span>`).join('')}</div>
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('body').append(previewHtml);
                $('#previewModal').modal('show');
                $('#previewModal').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            }
        });
    </script>
@endsection
<!--/==/ End of Extra Scripts -->
