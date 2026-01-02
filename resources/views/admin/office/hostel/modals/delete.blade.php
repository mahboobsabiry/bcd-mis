<div class="modal fade" id="deleteHostelModal{{ $hostel->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">@lang('global.delete') @lang('pages.hostel.hostel')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.office.hostel.destroy', $hostel->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fe fe-alert-triangle tx-60 tx-danger mb-3"></i>
                        <h5>@lang('global.deleteConfirm')</h5>
                        <p class="mb-3">@lang('global.deleteHostelWarning', ['number' => $hostel->number])</p>

                        @if($hostel->employees_count > 0)
                            <div class="alert alert-warning text-right">
                                <h6 class="alert-heading">@lang('global.warning')</h6>
                                <p class="mb-0">
                                    @lang('global.deleteHostelConsequences', ['count' => $hostel->employees_count])
                                </p>
                            </div>
                        @endif

                        <div class="form-group text-right">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="confirmDelete{{ $hostel->id }}" required>
                                <label class="custom-control-label" for="confirmDelete{{ $hostel->id }}">
                                    @lang('global.confirmDelete')
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
                    <button type="submit" class="btn btn-danger">@lang('global.delete')</button>
                </div>
            </form>
        </div>
    </div>
</div>
