<!-- Edit Place Modal for {{ $place->id }} -->
<div class="modal fade" id="editPlaceModal{{ $place->id }}" tabindex="-1" role="dialog" aria-labelledby="editPlaceModalLabel{{ $place->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlaceModalLabel{{ $place->id }}">
                    <i class="fe fe-edit mr-2"></i> @lang('global.editPlace')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.places.update', $place->id) }}" method="POST" id="editPlaceForm{{ $place->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_name_{{ $place->id }}">@lang('form.name') *</label>
                                <input type="text" class="form-control" id="edit_name_{{ $place->id }}"
                                       name="name" value="{{ $place->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_code_{{ $place->id }}">@lang('form.code') *</label>
                                <input type="text" class="form-control" id="edit_code_{{ $place->id }}"
                                       name="code" value="{{ $place->code }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_custom_code_{{ $place->id }}">@lang('form.customCode')</label>
                                <input type="text" class="form-control" id="edit_custom_code_{{ $place->id }}"
                                       name="custom_code" value="{{ $place->custom_code }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status_{{ $place->id }}">@lang('form.status')</label>
                                <select class="form-control" id="edit_status_{{ $place->id }}" name="status">
                                    <option value="1" {{ $place->status ? 'selected' : '' }}>@lang('global.active')</option>
                                    <option value="0" {{ !$place->status ? 'selected' : '' }}>@lang('global.inactive')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_info_{{ $place->id }}">@lang('form.extraInfo')</label>
                        <textarea class="form-control" id="edit_info_{{ $place->id }}" name="info" rows="4">{{ $place->info }}</textarea>
                    </div>

                    <!-- Statistics -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">@lang('global.statistics'):</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small>@lang('global.totalPositions'): <strong>{{ $place->positions_count }}</strong></small>
                            </div>
                            <div class="col-md-4">
                                <small>@lang('global.positionCodes'): <strong>{{ $place->positions_codes_count }}</strong></small>
                            </div>
                            <div class="col-md-4">
                                <small>@lang('global.created'): <strong>{{ $place->created_at->format('Y-m-d') }}</strong></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fe fe-x mr-1"></i> @lang('global.cancel')
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fe fe-save mr-1"></i> @lang('global.update')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
