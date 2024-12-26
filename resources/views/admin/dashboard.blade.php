@extends('layouts.admin.master')
@section('title', config('app.name') . ' ~ ' . trans('admin.dashboard.dashboard'))
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">@lang('admin.dashboard.welcomeToBCHS')!</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('website.index') }}" target="_blank">@lang('global.home')</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('admin.dashboard.dashboard')</li>
                </ol>
            </div>

            <div class="d-flex">
                <div class="mr-2">

                </div>
                <div class="">

                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Date -->
        <div class="card custom-card">
            <div class="card-body bg-primary-transparent" style="border: 1px solid #0f0373;">
                <span class="font-weight-bold">@lang('pages.dashboard.todaysDate'):</span> {{ date_format(now(), 'Y-M-d') }} <span class="font-weight-bold">@lang('pages.dashboard.coincidingWith'):</span> {{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime(now())) }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="font-weight-bold">@lang('global.day'):</span> {{ \Morilog\Jalali\CalendarUtils::strftime('%A', strtotime(now())) }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="font-weight-bold">@lang('global.hour'):</span> {{ \Morilog\Jalali\CalendarUtils::strftime('h:i A', strtotime(now())) }}
            </div>
        </div>
        <!--/==/ End of Date -->

        <!-- First Cards Row -->
        <div class="row row-sm">
            <!-- Users Card -->
            @can('user_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('admin.sidebar.users')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fas fa-users fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\User::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar bg-dark progress-bar-xs wd-100p" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.users.activeUsers')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                <i class="fas fa-caret-{{ \App\Models\User::all()->where('status', 1)->count() > \App\Models\User::all()->where('status', 0)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>{{ \App\Models\User::all()->where('status', 1)->count() }}
                            </span>
                                <span class="font-weight-bold">@lang('pages.users.inactiveUsers')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                <i class="fas fa-caret-{{ \App\Models\User::all()->where('status', 0)->count() > \App\Models\User::all()->where('status', 1)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>{{ \App\Models\User::all()->where('status', 0)->count() }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Users Card -->

            <!-- Positions -->
            @can('office_position_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('admin.sidebar.positions')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fe fe-activity fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\PositionCode::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.positions.appointed')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ $appointment_positions > $empty_positions ? 'up' : 'down' }} text-dark"></i>
                                    {{ $appointment_positions }}
                                </span>
                                <span class="font-weight-bold">@lang('global.empty')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ $empty_positions > $appointment_positions ? 'up' : 'down' }} text-dark"></i>
                                    {{ $empty_positions }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Positions -->

            <!-- Employees -->
            @can('office_employee_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('admin.sidebar.employees')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fa fa-user-tie fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\Employee::all()->where('status', 0)) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.employees.mainPosition')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 0)->count() > \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 1)->count() ? 'up' : 'down' }} text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 0)->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.employees.onDuty')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 1)->count() > \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 0)->count() ? 'up' : 'down' }} text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 1)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Employees -->

            <!-- Places -->
            @can('place_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('pages.dashboard.places')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fas fa-map-marker fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Place::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.dashboard.customsDuty')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Place::all()->where('custom_code', '!=', null)->count() > \App\Models\Place::all()->where('custom_code', '=', null)->count() ? 'up' : 'down' }} text-dark"></i>
                                    {{ \App\Models\Place::all()->where('custom_code', '!=', null)->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.dashboard.customsNonDuty')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Place::all()->where('custom_code', '=', null)->count() > \App\Models\Place::all()->where('custom_code', '!=', null)->count()? 'up' : 'down' }} text-dark"></i>
                                    {{ \App\Models\Place::all()->where('custom_code', '=', null)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Places -->
        </div>
        <!--/==/ End of First Cards Row -->

        <!-- Second Cards Row -->
        <div class="row row-sm">
            <!-- Positions Card -->
            @can('office_position_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('pages.dashboard.acInPositions')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fas fa-users fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\PositionCode::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.dashboard.activePositions')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Position::all()->where('status', 1)->count() > \App\Models\Office\Position::all()->where('status', 0)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>{{ \App\Models\Office\Position::all()->where('status', 1)->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.dashboard.inactivePositions')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Position::all()->where('status', 0)->count() > \App\Models\Office\Position::all()->where('status', 1)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>{{ \App\Models\Office\Position::all()->where('status', 0)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Positions Card -->

            <!-- Employees Change Position/Main Position -->
            @can('office_employee_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('pages.dashboard.empBStatus')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fe fe-activity fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\Employee::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.dashboard.currentEmps')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 0)->count() > \App\Models\Office\Employee::all()->where('status', 1)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 0)->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.dashboard.convertedEmps')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 1)->count() > \App\Models\Office\Employee::all()->where('status', 0)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 1)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Employees Change Position/Main Position -->

            <!-- Employees Have Home/Hostel -->
            @can('office_hostel_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('pages.dashboard.empBHousing')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fas fa-building fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\Employee::all()->where('status', 0)) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.dashboard.empsAtHome')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 0)->whereNull('hostel_id')->count() > \App\Models\Office\Employee::all()->where('status', 0)->whereNotNull('hostel_id')->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 0)->whereNull('hostel_id')->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.dashboard.empsAtHostel')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Employee::all()->where('status', 0)->whereNotNull('hostel_id')->count() > \App\Models\Office\Employee::all()->where('status', 0)->whereNull('hostel_id')->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Employee::all()->where('status', 0)->whereNotNull('hostel_id')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Employees Have Home/Hostel -->

            <!-- Companies -->
            @can('office_company_view')
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-body dash1">
                            <div class="d-flex">
                                <p class="mb-1 tx-inverse font-weight-bold">@lang('admin.sidebar.companies')</p>
                                <div class="{{ app()->getLocale() == 'en' ? 'ml-auto' : 'mr-auto' }}">
                                    <i class="fas fa-shopping-bag fs-20 text-dark"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="dash-25">{{ count(\App\Models\Office\Company::all()) }}</h3>
                            </div>
                            <div class="progress mb-1">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                     class="progress-bar progress-bar-xs wd-100p bg-dark" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex">
                                <span class="font-weight-bold">@lang('pages.companies.import')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Company::all()->where('type', 0)->count() > \App\Models\Office\Company::all()->where('type', 1)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Company::all()->where('type', 0)->count() }}
                                </span>
                                <span class="font-weight-bold">@lang('pages.companies.export')&nbsp;&nbsp;</span>
                                <span class="{{ app()->getLocale() == 'en' ? 'mr-auto' : 'ml-auto' }}">
                                    <i class="fas fa-caret-{{ \App\Models\Office\Company::all()->where('type', 1)->count() > \App\Models\Office\Company::all()->where('type', 0)->count() ? 'up' : 'down' }} mr-1 text-dark"></i>
                                    {{ \App\Models\Office\Company::all()->where('type', 1)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--/==/ End of Companies -->
        </div>
        <!--/==/ End of Second Cards Row -->

        <!-- Row -->
        @can('site_admin')
            <div class="row row-sm">
                <!-- Activity -->
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="card-title mb-1">
                                    <div class="row">
                                        <div class="col-md-6">@lang('global.activity')</div>
                                        <div class="col-md-6 {{ app()->getLocale() == 'en' ? 'text-right' : 'text-left' }}">
                                            <a href="{{ route('admin.activities') }}">
                                                @lang('global.activities')
                                            </a>
                                        </div>
                                    </div>
                                </h6>
                                <p class="text-muted mb-0 card-sub-title">@lang('admin.dashboard.usersAcDetails')</p>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="activity-block">
                                <ul class="task-list">
                                    @foreach($logActivities as $activity)
                                        <li>
                                            <i class="task-icon @if($activity->log_name == 'added') bg-info @elseif($activity->log_name == 'updated') bg-primary  @elseif($activity->log_name == 'deleted') bg-danger @else bg-success @endif"></i>
                                            <h6>{{ $activity->description }}
                                                <small class="{{ app()->getLocale() == 'en' ? 'float-right' : 'float-left' }} text-muted tx-11">
                                                    @if(app()->getLocale() == 'en')
                                                        {{ date_format($activity->created_at, 'Y-M-d') }}
                                                    @else
                                                        <span class="text-muted tx-sm-12">
                                                    @php
                                                        $date = \Morilog\Jalali\CalendarUtils::strftime('Y-M-d', strtotime($activity->created_at)); // 1395-02-19
                                                        echo \Morilog\Jalali\CalendarUtils::convertNumbers($date);
                                                    @endphp
                                                    </span>
                                                    @endif
                                                </small>
                                            </h6>
                                            <span class="text-muted tx-12">@lang('global.by'):
                                            @php
                                                $user = \App\Models\User::where('id', $activity->causer_id)->first();
                                                echo $user->name;
                                            @endphp
                                        </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Activity -->

                <!-- Top Users Based on their activities -->
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="card-title mb-1">@lang('admin.dashboard.activeUsers')</h6>
                                <p class="text-muted mb-0 card-sub-title">@lang('admin.dashboard.usersWithMostAc')</p>
                            </div>
                        </div>
                        <div class="user-manager scroll-widget border-top">
                            <div class="table-responsive">
                                <table class="table mg-b-0">
                                    <tbody>
                                    @foreach($top_users as $user)
                                        <tr>
                                            <td class="bd-t-0">
                                                <div class="main-img-user">
                                                    <img alt="avatar"
                                                         src="{{ $user->image ? $user->image : asset('assets/images/avatar-default.jpeg') }}">
                                                </div>
                                            </td>

                                            <td class="bd-t-0">
                                                <h6 class="mg-b-0">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                       target="_blank">{{ $user->name }}</a>
                                                </h6>
                                                <small class="tx-11 tx-gray-500">{{ $user->roles->first()->name ?? '' }}</small>
                                            </td>
                                            <td class="bd-t-0">
                                                <h6 class="mg-b-0 font-weight-bold">
                                                    @if(count($user->activities()) > 0)
                                                        {{ round((count($user->activities()) / count(\Spatie\Activitylog\Models\Activity::all())) * 100) }}
                                                        %
                                                    @else
                                                        0%
                                                    @endif
                                                </h6>
                                                <small class="tx-11 tx-gray-500">@lang('pages.dashboard.activityRate')</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Top Users Based on their activities -->

                <!-- Mini CHARTS -->
                <div class="col-sm-12 col-lg-4 col-xl-4">
                    <!-- First Chart -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div><h6 class="card-title mb-1">@lang('pages.dashboard.bcd')</h6>
                                <p class="text-muted card-sub-title">مجموع تشکیلات و موقعیت ها.</p></div>
                            <div class="row">
                                <!-- Organization -->
                                <div class="col-6 col-md-6 text-center">
                                    <div class="mb-2">
                                        <span class="peity-donut d-none" data-peity="{ &quot;fill&quot;: [&quot;#eb6f33&quot;, &quot;#e1e6f1&quot;], &quot;innerRadius&quot;: 14, &quot;radius&quot;: 20 }">4/7</span>
                                        <svg class="peity" height="40" width="40">
                                            <path
                                                d="M 20 0 A 20 20 0 1 1 11.322325217648839 38.01937735804839 L 13.925627652354187 32.61356415063387 A 14 14 0 1 0 20 6"
                                                data-value="4" fill="#02c792ff"></path>
                                            <path
                                                d="M 11.322325217648839 38.01937735804839 A 20 20 0 0 1 19.999999999999996 0 L 19.999999999999996 6 A 14 14 0 0 0 13.925627652354187 32.61356415063387"
                                                data-value="3" fill="#e1e6f1"></path>
                                        </svg>
                                    </div>
                                    <p class="mb-1 tx-inverse">@lang('global.organization')</p><h5 class="mb-1">{{ \App\Models\Office\Employee::all()->count() }}</h5>
                                </div>
                                <!--/==/ End of Organization -->

                                <!-- Places -->
                                <div class="col-6 col-md-6 text-center">
                                    <div class="mb-2">
                                        <span class="peity-donut d-none" data-peity="{ &quot;fill&quot;: [&quot;#01b8ff&quot;, &quot;#e1e6f1&quot;], &quot;innerRadius&quot;: 14, &quot;radius&quot;: 20 }">2/7</span>
                                        <svg class="peity" height="40" width="40">
                                            <path
                                                d="M 20 0 A 20 20 0 0 1 39.498558243636474 24.450418679126287 L 33.64899077054553 23.1152930753884 A 14 14 0 0 0 20 6"
                                                data-value="2" fill="#02c792ff"></path>
                                            <path
                                                d="M 39.498558243636474 24.450418679126287 A 20 20 0 1 1 19.999999999999996 0 L 19.999999999999996 6 A 14 14 0 1 0 33.64899077054553 23.1152930753884"
                                                data-value="5" fill="#e1e6f1"></path>
                                        </svg>
                                    </div>
                                    <p class="mb-1 tx-inverse">@lang('pages.dashboard.places')</p><h5 class="mb-1">{{ count(\App\Models\Place::all()) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Chart -->
                    <div class="card custom-card">
                        <div class="card-body">
                            <div><h6 class="card-title mb-1">@lang('global.organization')</h6>
                                <p class="text-muted mb-2 card-sub-title">@lang('pages.dashboard.bcd')</p></div>
                            <h4><span>$</span>22,534</h4>
                            <div class="clearfix mb-2">
                                <div class="clearfix"><span class="float-start text-muted">This Month</span> <span
                                        class="float-end">75%</span></div>
                                <div class="progress progress-xs  mb-1" role="progressbar" aria-label="Basic example"
                                     aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="clearfix"><span class="float-start text-muted">Last Month</span> <span
                                        class="float-end">50%</span></div>
                                <div class="progress progress-xs  mb-1" role="progressbar" aria-label="Basic example"
                                     aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-success" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/==/ End of Mini CHARTS -->
            </div>
        @endcan
        <!-- End Row -->
    </div>
@endsection
