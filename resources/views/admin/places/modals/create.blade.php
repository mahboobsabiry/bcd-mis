<!-- Create Place Modal -->
<div class="modal fade" id="createPlaceModal" tabindex="-1" role="dialog" aria-labelledby="createPlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPlaceModalLabel">
                    <i class="fe fe-plus-circle mr-2"></i> @lang('global.addNewPlace')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.places.store') }}" method="POST" id="createPlaceForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('form.name') *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       required oninput="generateCodeFromName(this)">
                                <small class="form-text text-muted">@lang('form.placeNameHelp')</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">@lang('form.code') *</label>
                                <input type="text" class="form-control" id="code" name="code"
                                       value="{{ $code }}" required>
                                <small class="form-text text-muted">@lang('form.placeCodeHelp')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="custom_code">@lang('form.customCode')</label>
                                <input type="text" class="form-control" id="custom_code" name="custom_code">
                                <small class="form-text text-muted">@lang('form.customCodeHelp')</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">@lang('form.status')</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1">@lang('global.active')</option>
                                    <option value="0">@lang('global.inactive')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="info">@lang('form.extraInfo')</label>
                        <textarea class="form-control" id="info" name="info" rows="4"
                                  placeholder="@lang('form.placeInfoPlaceholder')"></textarea>
                        <small class="form-text text-muted">@lang('form.placeInfoHelp')</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fe fe-x mr-1"></i> @lang('global.cancel')
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fe fe-save mr-1"></i> @lang('global.save')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Form validation for create modal
    document.getElementById('createPlaceForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const code = document.getElementById('code').value.trim();

        if (!name || !code) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: '@lang("global.validationError")',
                text: '@lang("global.pleaseFillRequiredFields")'
            });
        }
    });
</script>
