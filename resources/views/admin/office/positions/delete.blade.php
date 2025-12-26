<!-- Simple Delete Position Modal -->
<div class="modal fade" id="deletePosition{{ $position->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash mr-2"></i>
                    حذف بست وظیفوی
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    آیا مطمئن هستید که می‌خواهید این بست را حذف کنید؟
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <strong>عنوان:</strong><br>
                        {{ $position->title }}
                    </div>
                    <div class="col-6">
                        <strong>درجه:</strong><br>
                        درجه {{ $position->position_number }}
                    </div>
                </div>

                @if($position->children()->count() > 0 || $position->employees()->count() > 0)
                    <div class="alert alert-danger small">
                        <strong>هشدار:</strong>
                        <ul class="mb-0 mt-2">
                            @if($position->children()->count() > 0)
                                <li>این بست دارای {{ $position->children()->count() }} زیرمجموعه است</li>
                            @endif
                            @if($position->employees()->count() > 0)
                                <li>این بست دارای {{ $position->employees()->count() }} کارمند است</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    انصراف
                </button>
                <form method="POST" action="{{ route('admin.office.positions.destroy', $position->id) }}"
                      class="d-inline" id="deleteForm{{ $position->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#deleteForm{{ $position->id }}').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const button = form.find('button[type="submit"]');
            const originalText = button.html();

            // Show loading
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> در حال حذف...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Close modal
                    $('#deletePosition{{ $position->id }}').modal('hide');

                    // Show success message
                    showToast(response.message || 'بست با موفقیت حذف شد', 'success');

                    // Reload page after 1.5 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    button.prop('disabled', false).html(originalText);

                    let errorMessage = 'خطا در حذف بست';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    showToast(errorMessage, 'error');
                }
            });
        });
    });

    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = `<div class="alert alert-${type} alert-dismissible fade show position-fixed"
                   style="bottom: 20px; left: 20px; z-index: 9999; max-width: 300px;">
                   ${message}
                   <button type="button" class="close" data-dismiss="alert">
                       <span>&times;</span>
                   </button>
               </div>`;

        $('body').append(toast);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }
</script>
