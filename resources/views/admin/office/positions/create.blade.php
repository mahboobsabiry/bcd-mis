@extends('layouts.admin.master')
<!-- Title -->
@section('title', config('app.name') . ' ~ ' . trans('pages.positions.addPosition'))
<!-- Extra Styles -->
@section('extra_css')

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
                <!-- Back -->
                <a class="btn btn-orange btn-sm btn-with-icon" href="{{ url()->previous() }}">
                    @lang('global.back')
                    <i class="fe fe-arrow-left"></i>
                </a>
            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Main Row -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card custom-card overflow-hidden">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="">
                            <!-- Errors Message -->
                            @include('admin.inc.alerts')

                            <!-- Form Title -->
                            <div>
                                <h6 class="card-title mb-1">@lang('pages.positions.addPosition')</h6>
                                <p class="text-muted card-sub-title">You can add new record here.</p>
                            </div>

                            <!-- Form -->
                            <form method="post" action="{{ route('admin.office.positions.store') }}" data-parsley-validate="">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Under Hand -->
                                        <div class="form-group @error('parent_id') has-danger @enderror">
                                            <p class="mb-2">@lang('pages.positions.underHand'): <span class="tx-danger">*</span></p>

                                            <select id="parent_id" name="parent_id" class="form-control select2 @error('parent_id') form-control-danger @enderror">
                                                <option selected>Choose one</option>
                                                @foreach($positions as $position)
                                                    <option value="{{ $position->id }}">{{ $position->title }}</option>
                                                    @foreach($position->children as $admin)
                                                        <option value="{{ $admin->id }}" class="text-secondary">- {{ $admin->title }} <span class="small">({{ $admin->type }})</span></option>
                                                        @foreach($admin->children as $mgmt)
                                                            <option value="{{ $mgmt->id }}">-- {{ $mgmt->title }} <span class="small">({{ $mgmt->type }})</span></option>
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
                                            </select>

                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Under Hand -->

                                        <!-- Title -->
                                        <div class="form-group @error('title') has-danger @enderror">
                                            <p class="mb-2">@lang('form.title'): <span class="tx-danger">*</span></p>
                                            <input type="text" id="title" class="form-control @error('title') form-control-danger @enderror" name="title" value="{{ old('title') }}" placeholder="@lang('form.title')" required>

                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Title -->

                                        <!-- Number of Positions -->
                                        <div class="form-group @error('num_of_pos') has-danger @enderror">
                                            <p class="mb-2">@lang('form.num_of_pos'): <span class="tx-danger">*</span></p>
                                            <input type="number" id="num_of_pos" class="form-control @error('num_of_pos') form-control-danger @enderror" name="num_of_pos" value="1" placeholder="@lang('form.num_of_pos')" required>

                                            @error('num_of_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Number of Positions -->
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Position Number -->
                                        <div class="form-group @error('position_number') has-danger @enderror">
                                            <p class="mb-2">@lang('pages.positions.positionNumber'): <span class="tx-danger">*</span></p>
                                            <input type="number" id="position_number" class="form-control @error('position_number') form-control-danger @enderror" name="position_number" value="{{ old('position_number') }}" placeholder="@lang('pages.positions.positionNumber')" required>

                                            @error('position_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Position Number -->

                                        <!-- Type -->
                                        <div class="form-group @error('type') has-danger @enderror">
                                            <p class="mb-2">موقعیت: <span class="tx-danger">*</span></p>

                                            <select id="type" name="type" class="form-control select2 @error('type') form-control-danger @enderror">
                                                <option value="محصولی">محصولی</option>
                                                <option value="سرحدی">سرحدی</option>
                                                <option value="نایب آباد">نایب آباد</option>
                                                <option value="میدان هوایی">میدان هوایی</option>
                                                <option value="مراقبت سیار">مراقبت سیار</option>
                                            </select>

                                            @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Type -->

                                        <!-- Description -->
                                        <div class="form-group @error('desc') has-danger @enderror">
                                            <p class="mb-2">@lang('form.extraInfo'):</p>
                                            <textarea name="desc" class="form-control @error('desc') form-control-danger @enderror" placeholder="@lang('form.extraInfo')">{{ old('desc') }}</textarea>

                                            @error('desc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!--/==/ End of Description -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn ripple btn-primary rounded-2" type="submit">@lang('global.save')</button>
                                </div>
                            </form>
                            <!--/==/ End of Form -->
                        </div>
                    </div>
                    <!--/==/ End of Card Body -->
                </div>
                <!--/==/ End of Card -->
            </div>
        </div>
        <!--/==/ End of Main Row -->
    </div>
@endsection
<!--/==/ End of Main Content of The Page -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/advanced-form-elements.js') }}"></script>

    <!-- Form-elements js-->
    <script src="{{ asset('backend/assets/js/form-elements.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
