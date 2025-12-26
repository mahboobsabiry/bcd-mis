<style>
    #addCodeModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    #addCodeModal .modal-header {
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
    }

    #addCodeModal .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    #addCodeModal .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
    }

    .suggested-code {
        transition: all 0.3s;
    }

    .suggested-code:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }

    .suggested-code:active {
        transform: translateY(0);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label .required {
        color: #dc3545;
        margin-right: 0.25rem;
    }
</style>
<!-- Add Code Modal -->
<div class="modal fade" id="addCodeModal" tabindex="-1" role="dialog" aria-labelledby="addCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.office.positions.codes.store', $position->id) }}" id="addCodeForm">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addCodeModalLabel">
                        <i class="fas fa-plus-circle mr-2"></i>افزودن کد جدید به بست
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Position Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x mr-3"></i>
                            <div>
                                <strong>بست: {{ $position->title }}</strong>
                                <div class="small mt-1">
                                    {{ $position->codes->count() }} کد از {{ $position->num_of_pos }} بست تعریف شده است.
                                    @if($position->num_of_pos - $position->codes->count() > 1)
                                        می‌توانید {{ $position->num_of_pos - $position->codes->count() }} کد دیگر اضافه کنید.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Code Input -->
                    <div class="form-group">
                        <label for="code" class="form-label font-weight-bold">
                            <i class="fas fa-hashtag mr-2 text-primary"></i>کد بست
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input type="text"
                                   class="form-control @error('code') is-invalid @enderror"
                                   id="code"
                                   name="code"
                                   value="{{ old('code') }}"
                                   placeholder="مثال: P001"
                                   required
                                   maxlength="20">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" id="generateCodeBtn">
                                    <i class="fas fa-magic"></i> تولید خودکار
                                </button>
                            </div>
                        </div>

                        <div class="mt-2">
                            <small class="text-muted">
                                کد باید منحصر به فرد باشد. می‌توانید از دکمه تولید خودکار استفاده کنید.
                            </small>
                        </div>

                        <!-- Code Suggestions -->
                        <div id="codeSuggestions" class="mt-3" style="display: none;">
                            <label class="small text-muted d-block mb-2">پیشنهادات:</label>
                            <div class="d-flex flex-wrap gap-2" id="suggestedCodes"></div>
                        </div>

                        <!-- Existing Codes Warning -->
                        @if($position->codes->count() > 0)
                            <div class="mt-3">
                                <label class="small text-muted d-block mb-2">کدهای موجود:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($position->codes as $existingCode)
                                        <span class="badge badge-light border">
                                            {{ $existingCode->code }}
                                            @if($existingCode->employee)
                                                <i class="fas fa-user-check text-success ml-1"></i>
                                            @else
                                                <i class="fas fa-user-times text-danger ml-1"></i>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Selection -->
                    <div class="form-group">
                        <label for="status" class="form-label font-weight-bold">
                            <i class="fas fa-toggle-on mr-2 text-primary"></i>وضعیت
                        </label>
                        <select class="form-control @error('status') is-invalid @enderror"
                                id="status"
                                name="status">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                        <small class="text-muted">کدهای غیرفعال قابل تخصیص به کارمندان نخواهند بود</small>
                    </div>

                    <!-- Additional Info -->
                    <div class="form-group">
                        <label for="info" class="form-label font-weight-bold">
                            <i class="fas fa-sticky-note mr-2 text-primary"></i>توضیحات
                        </label>
                        <textarea class="form-control @error('info') is-invalid @enderror"
                                  id="info"
                                  name="info"
                                  rows="3"
                                  placeholder="توضیحات اضافی درباره این کد (اختیاری)">{{ old('info') }}</textarea>
                    </div>

                    <!-- Assign Employee (Optional) -->
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-user-check mr-2 text-primary"></i>تخصیص به کارمند
                        </label>
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <div class="small">
                                    <strong>توجه:</strong> می‌توانید بعداً از صفحه مدیریت کدها، این کد را به کارمند تخصیص دهید.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> انصراف
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> ذخیره کد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Generate code automatically
        $('#generateCodeBtn').on('click', function() {
            generateSuggestedCodes();
        });

        // Auto-generate on modal show
        $('#addCodeModal').on('show.bs.modal', function() {
            generateSuggestedCodes();
        });

        // Form validation
        $('#addCodeForm').on('submit', function(e) {
            const code = $('#code').val().trim();

            if (!code) {
                e.preventDefault();
                showToast('لطفاً کد را وارد کنید', 'danger');
                return false;
            }

            // Check if code already exists
            const existingCodes = @json($position->codes->pluck('code')->toArray());
            if (existingCodes.includes(code)) {
                e.preventDefault();
                showToast('این کد قبلاً ثبت شده است', 'danger');
                return false;
            }

            // Validate code format (optional)
            if (!/^[A-Za-z0-9_-]+$/.test(code)) {
                e.preventDefault();
                showToast('کد فقط می‌تواند شامل حروف، اعداد و خط تیره باشد', 'danger');
                return false;
            }
        });

        function generateSuggestedCodes() {
            const positionTitle = '{{ $position->title }}';
            const placeName = '{{ $position->place->name ?? "" }}';
            const existingCodes = @json($position->codes->pluck('code')->toArray());

            // Generate base code from position and place
            let placeCode = placeName ? placeName.substring(0, 2).toUpperCase() : 'PO';
            let titleCode = positionTitle.substring(0, 2).toUpperCase();

            // Clean codes
            placeCode = placeCode.replace(/[^A-Z]/g, '');
            titleCode = titleCode.replace(/[^A-Z]/g, '');

            if (!placeCode) placeCode = 'PO';
            if (!titleCode) titleCode = 'PS';

            // Generate 5 unique suggestions
            let suggestions = [];
            let counter = 1;

            while (suggestions.length < 5 && counter < 100) {
                // Format 1: Place + Title + Number
                let code1 = `${placeCode}${titleCode}${String(counter).padStart(3, '0')}`;

                // Format 2: Title + Place + Number
                let code2 = `${titleCode}${placeCode}${String(counter).padStart(3, '0')}`;

                // Format 3: Simple sequential
                let code3 = `P${String({{ $position->codes->count() }} + counter).padStart(3, '0')}`;

                // Check uniqueness
                [code1, code2, code3].forEach(code => {
                    if (!existingCodes.includes(code) && !suggestions.includes(code) && suggestions.length < 5) {
                        suggestions.push(code);
                    }
                });

                counter++;
            }

            // Display suggestions
            if (suggestions.length > 0) {
                $('#suggestedCodes').empty();
                suggestions.forEach(code => {
                    $('#suggestedCodes').append(`
                    <button type="button" class="btn btn-sm btn-outline-primary suggested-code"
                            data-code="${code}">
                        ${code}
                    </button>
                `);
                });
                $('#codeSuggestions').show();
            }
        }

        // Click on suggested code
        $(document).on('click', '.suggested-code', function() {
            const code = $(this).data('code');
            $('#code').val(code).focus();
        });

        // Auto-focus on code input when modal opens
        $('#addCodeModal').on('shown.bs.modal', function() {
            $('#code').focus();
        });

        // Clear form when modal closes
        $('#addCodeModal').on('hidden.bs.modal', function() {
            $('#code').val('');
            $('#info').val('');
            $('#status').val('1');
        });

        function showToast(message, type = 'info') {
            // Your existing toast function
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
    });
</script>
