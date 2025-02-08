<!-- New item -->
<div class="modal" id="new_item{{ $tariff->id }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <!-- Modal Header -->
            <div class="modal-header">
                <h6 class="modal-title">@lang('global.new')</i></h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>

            <!-- Form -->
            <form method="post" action="{{ route('admin.examination.preferential_tariffs.new_item', $tariff->id) }}" data-parsley-validate="">
                @csrf
                <div class="modal-body">
                    <!-- Good Name -->
                    <div class="form-group">
                        <label for="good_name">اسم جنس</label>
                        <input type="text" id="good_name" name="good_name" class="form-control" value="{{ old('good_name') }}" placeholder="اسم جنس" required>
                    </div>
                    <!-- HS Code -->
                    <div class="form-group">
                        <label for="hs_code">کد تعرفه (HS CODE)</label>
                        <input type="number" id="hs_code" name="hs_code" class="form-control" value="{{ old('hs_code') }}" placeholder="کد تعرفه (HS CODE)" required>
                    </div>
                    <!-- Total Packages -->
                    <div class="form-group">
                        <label for="total_packages">مجموع بسته ها</label>
                        <input type="number" id="total_packages" name="total_packages" class="form-control" value="{{ old('total_packages') }}" placeholder="مجموع بسته ها" required>
                    </div>
                    <!-- Weight -->
                    <div class="form-group">
                        <label for="weight">وزن به کیلوگرام</label>
                        <input type="number" id="weight" name="weight" class="form-control" value="{{ old('weight') }}" placeholder="وزن به کیلوگرام" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit">@lang('global.yes')</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">@lang('global.no')</button>
                </div>
            </form>
            <!--/==/ End of Form -->
        </div>
    </div>
</div>
<!--/==/ End of New item -->
