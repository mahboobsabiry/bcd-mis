@extends('layouts.admin.master')
<!-- Title -->
@section('title', 'مکتوب های دریافتی - ' . $position->title)

<!-- Extra Styles -->
@section('extra_css')
    <!---DataTables css-->
    <link href="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Select 2 -->
    <link href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Sumoselect css-->
    <link href="{{ asset('backend/assets/plugins/sumoselect/sumoselect.css') }}" rel="stylesheet">

    <style>
        table thead tr .tblBorder {
            border: 1px solid #ddd;
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
                <h2 class="main-content-title tx-24 mg-b-5">مکاتیب</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard.dashboard')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">مکاتیب دریافتی</li>
                </ol>
            </div>

            <!-- Btn List -->
            <div class="btn btn-list">

            </div>
        </div>
        <!--/==/ End of Page Header -->

        <!-- Data Table -->
        <div class="row">
            @if(!auth()->user()->isAdmin())
                <div class="col-lg-3 col-md-12">
                    <!-- Profile Main Info -->
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            <div class="main-profile-overview widget-user-image text-center">
                                <div class="main-img-user">
                                    @if($position->num_of_pos == 1)
                                        <a href="{{ $position->employees->first()->image ?? asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                            <img alt="avatar" src="{{ $position->employees->first()->image ?? asset('assets/images/avatar-default.jpeg') }}">
                                        </a>
                                    @else
                                        <a href="{{ asset('assets/images/avatar-default.jpeg') }}" target="_blank">
                                            <img alt="avatar" src="{{ asset('assets/images/avatar-default.jpeg') }}">
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="item-user pro-user">
                                <h4 class="pro-user-username text-dark mt-2 mb-0">
                                    @if($position->num_of_pos == 1)
                                        <span>{{ $position->employees->first()->name ?? trans('global.empty') }} {{ $position->employees->first()->last_name ?? '' }}</span>
                                    @else
                                        <span>{{ $position->title }}</span>
                                    @endif

                                </h4>

                                <p class="pro-user-desc text-muted mb-1">{{ $position->title }}</p>
                                @if($position->position_number == 2 || $position->position_number == 3)
                                @else
                                    <p class="pro-user-desc text-primary mb-1">({{ $position->place->name ?? '' }})</p>
                                @endif
                                <!-- Position Star -->
                                <p class="user-info-rating">
                                    @for($i=1; $i<=$position->position_number; $i++)
                                        <a href="javascript:void(0);"><i class="fa fa-star text-warning"> </i></a>
                                    @endfor
                                </p>
                                <!--/==/ End of Position Star -->
                            </div>
                        </div>
                    </div>
                    <!--/==/ End of Profile Main Info -->

                    <!-- Contact Information -->
                    @if($position->num_of_pos == 1)
                        <div class="card custom-card">
                            <div class="card-header custom-card-header">
                                <div>
                                    <h6 class="card-title mb-0">
                                        @lang('pages.users.contactInfo')
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="main-profile-contact-list main-profile-work-list">
                                    <!-- Phone Number -->
                                    <div class="media">
                                        <div class="media-logo bg-light text-dark">
                                            <i class="fe fe-smartphone"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>@lang('form.phone')</span>
                                            <div>
                                                <a href="callto:{{ $position->employees->first()->phone ?? '' }}"
                                                   class="ctd">{{ $position->employees->first()->phone ?? '--- ---- ---' }}</a>
                                                <a href="callto:{{ $position->employees->first()->phone2 ?? '' }}"
                                                   class="ctd">{{ $position->employees->first()->phone2 ?? '' }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/==/ End of Phone Number -->

                                    <!-- Email Address -->
                                    <div class="media">
                                        <div class="media-logo bg-light text-dark">
                                            <i class="fe fe-mail"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>@lang('form.email')</span>
                                            <div>
                                                <a href="mailto:{{ $position->employees->first()->email ?? '' }}"
                                                   class="ctd">{{ $position->employees->first()->email ?? '----@---.--' }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/==/ End of Email Address -->
                                </div>
                            </div>
                        </div>
                    @endif
                    <!--/==/ End of Contact Information -->
                </div>
            @endif
            <div class="{{ auth()->user()->isAdmin() ? 'col-lg-12' : 'col-lg-9' }} col-md-12">
                <!-- Success Message -->
                @include('admin.inc.alerts')

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header font-weight-bold">
                        مجموع مکاتیب دریافتی ({{ count($documents) }})
                    </div>

                    <!-- Table Card Body -->
                    <div class="card-body">
                        <!-- Employees -->
                        <div class="">
                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable export-table border-top key-buttons display text-nowrap w-100" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>مرسل</th>
                                        <th>مرسل الیه</th>
                                        <th>کاپی به</th>
                                        <th>موضوع</th>
                                        <th>نوع</th>
                                        <th>نوع فعالیت</th>
                                        <th>نمبر</th>
                                        <th>تاریخ</th>
                                        <th>ضمایم</th>
                                        <th>@lang('form.status')</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <!-- ID -->
                                            <td class="@if(\Illuminate\Support\Facades\Session::has($document->subject)) tx-bold tx-danger @endif">{{ $loop->iteration }}</td>
                                            <!-- Sender -->
                                            <td>
                                                <a class="@if(session()->has($document->subject)) tx-bold tx-danger @endif" href="{{ route('admin.office.positions.show', $document->position->id) }}">{{ $document->position->title }}</a>
                                            </td>
                                            <!-- Receiver -->
                                            <td>
                                                @php $receiver_pos = \App\Models\Office\Position::where('title', $document->receiver)->first(); @endphp
                                                <a href="{{ route('admin.office.positions.show', $receiver_pos->id) }}" class="{{ $document->receiver == auth()->user()->employee->position->title ? 'font-weight-bold text-success' : '' }}">{{ $document->receiver ?? '' }}</a>
                                            </td>
                                            <!-- CC -->
                                            <td>
                                                @foreach(explode(', ', $document->cc) as $cc)
                                                    - <span class="{{ $cc == auth()->user()->employee->position->title ? 'font-weight-bold text-success' : '' }}">{{ $cc }}</span>
                                                @endforeach
                                            </td>
                                            <!-- Subject -->
                                            <td>
                                                <a href="{{ route('admin.documents.show', $document->id) }}">{{ $document->subject }}</a>
                                            </td>
                                            <td>{{ $document->type ?? '' }}</td>
                                            <td>{{ $document->doc_type ?? '' }}</td>
                                            <td>{{ $document->doc_number ?? '' }}</td>
                                            <td>{{ $document->doc_date ?? '' }}</td>
                                            <td>{{ $document->appendices ?? '' }}</td>
                                            <td>{{ $document->status == 1 ? trans('global.active') : trans('global.inactive') }}</td>
                                            <td>{{ $document->info ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--/==/ End of Table -->
                        </div>
                        <!-- End of Employees -->
                    </div>
                    <!--/==/ End of Table Card Body -->
                </div>
                <!--/==/ End of Table Card -->
            </div>
        </div>
        <!--/==/ End of Data Table -->
    </div>
@endsection
<!--/==/ End of Page Content -->

<!-- Extra Scripts -->
@section('extra_js')
    <!-- Data Table js -->
    <script src="{{ asset('backend/assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatable/fileexport/buttons.colVis.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/datatable.js') }}"></script>
@endsection
<!--/==/ End of Extra Scripts -->
