{{-- dashboard.blade.php --}}
@extends('layouts.admin.master')

@section('title', config('app.name') . ' ~ ' . trans('admin.dashboard.dashboard'))

@section('extra_css')
    <style>
        /* Modern Dashboard Styles */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
            --info-gradient: linear-gradient(135deg, #17ead9 0%, #6078ea 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            --light-gradient: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        }

        /* Modern Card Styles */
        .modern-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            background: white;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 15px 15px 0 0;
        }

        /* Gradient Cards */
        .gradient-card {
            color: white;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 1.5rem;
        }

        .gradient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .gradient-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .gradient-card > * {
            position: relative;
            z-index: 2;
        }

        /* Date Card */
        .date-card {
            background: linear-gradient(135deg, #f6a814 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .date-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: movePattern 20s linear infinite;
        }

        @keyframes movePattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 15px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stat-card-title {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .stat-card-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        .stat-card-detail-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Activity Card */
        .activity-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #f1f3f9;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #f8f9fc;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        /* Top Users Card */
        .user-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .user-item {
            padding: 1rem;
            border-bottom: 1px solid #f1f3f9;
            transition: all 0.3s ease;
        }

        .user-item:hover {
            background: #f8f9fc;
            transform: translateX(5px);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid #f1f3f9;
            transition: all 0.3s ease;
        }

        .user-item:hover .user-avatar {
            border-color: #667eea;
            transform: scale(1.1);
        }

        .user-activity-rate {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        /* Charts Container */
        .chart-container {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .chart-mini {
            padding: 1.5rem;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .chart-mini-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .chart-mini-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Progress Bars */
        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background: #f1f3f9;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .animation-delay-1 { animation-delay: 0.1s; }
        .animation-delay-2 { animation-delay: 0.2s; }
        .animation-delay-3 { animation-delay: 0.3s; }
        .animation-delay-4 { animation-delay: 0.4s; }

        /* Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-success { background: rgba(40, 167, 69, 0.1); color: #28a745; }
        .badge-warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .badge-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .badge-info { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
        .badge-primary { background: rgba(102, 126, 234, 0.1); color: #667eea; }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #f6a814 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card-value {
                font-size: 1.75rem;
            }

            .welcome-section {
                padding: 1.5rem;
            }

            .chart-mini-value {
                font-size: 2rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .modern-card,
            .stat-card,
            .activity-card,
            .user-card,
            .chart-container {
                background: #2d3748;
                color: #e2e8f0;
            }

            .stat-card-value {
                color: #e2e8f0;
            }

            .stat-card-title {
                color: #a0aec0;
            }

            .activity-item:hover,
            .user-item:hover {
                background: #4a5568;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Welcome Header -->
        <div class="welcome-section animate-fade-in-up">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="welcome-content">
                        <h1 class="mb-2">@lang('admin.dashboard.welcomeToBCHS')! 👋</h1>
                        <p class="mb-0 opacity-75">
                            {{ trans('admin.dashboard.welcomeMessage') }}
                        </p>
                        <div class="mt-3 d-flex align-items-center">
                            <div class="welcome-avatar me-3">
                                <img src="{{ auth()->user()->image ?? asset('assets/images/avatar-default.jpeg') }}"
                                     alt="{{ auth()->user()->name }}" class="img-fluid">
                            </div>
                            <div>
                                <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                                <small class="opacity-75">{{ auth()->user()->roles->first()->name ?? trans('global.user') }} - {{ auth()->user()->username }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block p-3 rounded" style="background: rgba(255,255,255,0.1);">
                        <div class="display-4 fw-bold">{{ date('H:i') }}</div>
                        <div class="opacity-75">{{ \Morilog\Jalali\CalendarUtils::strftime('%A', strtotime(now())) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Card -->
        <div class="date-card mb-4 animate-fade-in-up animation-delay-1">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">@lang('pages.dashboard.todaysDate')</h5>
                                <p class="mb-0">
                                    {{ date_format(now(), 'Y-M-d') }}
                                    <span class="opacity-75 mx-2">|</span>
                                    {{ \Morilog\Jalali\CalendarUtils::strftime('Y-F-d', strtotime(now())) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="d-inline-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div class="text-start">
                                <h5 class="mb-1">@lang('global.time')</h5>
                                <p class="mb-0">
                                    {{ \Morilog\Jalali\CalendarUtils::strftime('h:i A', strtotime(now())) }}
                                    <span class="opacity-75 mx-2">|</span>
                                    @lang('global.day'): {{ \Morilog\Jalali\CalendarUtils::strftime('%A', strtotime(now())) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row mb-4">
            @can('user_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-2">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\User::all()) }}</div>
                        <div class="stat-card-title">@lang('admin.sidebar.users')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $activeUsers = \App\Models\User::all()->where('status', 1)->count();
                                $inactiveUsers = \App\Models\User::all()->where('status', 0)->count();
                                $totalUsers = $activeUsers + $inactiveUsers;
                                $activePercentage = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $activePercentage }}%; background: linear-gradient(to right, #667eea, #764ba2);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-circle text-success"></i>
                                <span>{{ $activeUsers }} @lang('global.active')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-circle text-warning"></i>
                                <span>{{ $inactiveUsers }} @lang('global.inactive')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('office_position_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-3">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\PositionCode::all()) }}</div>
                        <div class="stat-card-title">@lang('admin.sidebar.positions')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $filledPositions = $appointment_positions;
                                $emptyPositions = $empty_positions;
                                $totalPositions = $filledPositions + $emptyPositions;
                                $filledPercentage = $totalPositions > 0 ? ($filledPositions / $totalPositions) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $filledPercentage }}%; background: linear-gradient(to right, #f093fb, #f5576c);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-user-check text-success"></i>
                                <span>{{ $filledPositions }} @lang('pages.positions.appointed')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-user-times text-warning"></i>
                                <span>{{ $emptyPositions }} @lang('global.empty')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('office_employee_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-4">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\Employee::all()->where('status', 0)) }}</div>
                        <div class="stat-card-title">@lang('admin.sidebar.employees')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $mainPosition = \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 0)->count();
                                $onDuty = \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 1)->count();
                                $totalEmployees = $mainPosition + $onDuty;
                                $mainPercentage = $totalEmployees > 0 ? ($mainPosition / $totalEmployees) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $mainPercentage }}%; background: linear-gradient(to right, #4facfe, #00f2fe);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-home text-primary"></i>
                                <span>{{ $mainPosition }} @lang('pages.employees.mainPosition')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-exchange-alt text-info"></i>
                                <span>{{ $onDuty }} @lang('pages.employees.onDuty')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('place_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-2">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Place::all()) }}</div>
                        <div class="stat-card-title">@lang('pages.dashboard.places')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $customDuty = \App\Models\Place::all()->where('custom_code', '!=', null)->count();
                                $customNonDuty = \App\Models\Place::all()->where('custom_code', '=', null)->count();
                                $totalPlaces = $customDuty + $customNonDuty;
                                $dutyPercentage = $totalPlaces > 0 ? ($customDuty / $totalPlaces) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $dutyPercentage }}%; background: linear-gradient(to right, #fa709a, #fee140);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-passport text-danger"></i>
                                <span>{{ $customDuty }} @lang('pages.dashboard.customsDuty')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-globe-asia text-warning"></i>
                                <span>{{ $customNonDuty }} @lang('pages.dashboard.customsNonDuty')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Second Row Statistics -->
        <div class="row mb-4">
            @can('office_position_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\PositionCode::all()) }}</div>
                        <div class="stat-card-title">@lang('pages.dashboard.acInPositions')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $activePositions = \App\Models\Office\Position::all()->where('status', 1)->count();
                                $inactivePositions = \App\Models\Office\Position::all()->where('status', 0)->count();
                                $totalPositions = $activePositions + $inactivePositions;
                                $activePosPercentage = $totalPositions > 0 ? ($activePositions / $totalPositions) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $activePosPercentage }}%; background: linear-gradient(to right, #ff0844, #ffb199);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>{{ $activePositions }} @lang('global.active')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-times-circle text-danger"></i>
                                <span>{{ $inactivePositions }} @lang('global.inactive')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('office_employee_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-1">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #17ead9 0%, #6078ea 100%);">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\Employee::all()) }}</div>
                        <div class="stat-card-title">@lang('pages.dashboard.empBStatus')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $currentEmps = \App\Models\Office\Employee::all()->where('status', 0)->count();
                                $convertedEmps = \App\Models\Office\Employee::all()->where('status', 1)->count();
                                $totalEmps = $currentEmps + $convertedEmps;
                                $currentPercentage = $totalEmps > 0 ? ($currentEmps / $totalEmps) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $currentPercentage }}%; background: linear-gradient(to right, #17ead9, #6078ea);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-user-clock text-info"></i>
                                <span>{{ $currentEmps }} @lang('pages.dashboard.currentEmps')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-user-check text-success"></i>
                                <span>{{ $convertedEmps }} @lang('pages.dashboard.convertedEmps')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('office_hostel_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-2">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\Employee::all()->where('status', 0)) }}</div>
                        <div class="stat-card-title">@lang('pages.dashboard.empBHousing')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $empsAtHome = \App\Models\Office\Employee::all()->where('status', 0)->whereNull('hostel_id')->count();
                                $empsAtHostel = \App\Models\Office\Employee::all()->where('status', 0)->whereNotNull('hostel_id')->count();
                                $totalHousing = $empsAtHome + $empsAtHostel;
                                $homePercentage = $totalHousing > 0 ? ($empsAtHome / $totalHousing) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $homePercentage }}%; background: linear-gradient(to right, #2c3e50, #4ca1af);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-house-user text-success"></i>
                                <span>{{ $empsAtHome }} @lang('pages.dashboard.empsAtHome')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-hotel text-primary"></i>
                                <span>{{ $empsAtHostel }} @lang('pages.dashboard.empsAtHostel')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('office_company_view')
                <div class="col-xl-3 col-lg-6 mb-4 animate-fade-in-up animation-delay-3">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%); color: #2c3e50;">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-card-value">{{ count(\App\Models\Office\Company::all()) }}</div>
                        <div class="stat-card-title">@lang('admin.sidebar.companies')</div>
                        <div class="progress-bar-custom mb-3">
                            @php
                                $importCompanies = \App\Models\Office\Company::all()->where('type', 0)->count();
                                $exportCompanies = \App\Models\Office\Company::all()->where('type', 1)->count();
                                $totalCompanies = $importCompanies + $exportCompanies;
                                $importPercentage = $totalCompanies > 0 ? ($importCompanies / $totalCompanies) * 100 : 0;
                            @endphp
                            <div class="progress-bar-fill" style="width: {{ $importPercentage }}%; background: linear-gradient(to right, #fdfcfb, #e2d1c3);"></div>
                        </div>
                        <div class="stat-card-details">
                            <div class="stat-card-detail-item">
                                <i class="fas fa-download text-primary"></i>
                                <span>{{ $importCompanies }} @lang('pages.companies.import')</span>
                            </div>
                            <div class="stat-card-detail-item">
                                <i class="fas fa-upload text-success"></i>
                                <span>{{ $exportCompanies }} @lang('pages.companies.export')</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        @can('site_admin')
            <!-- Activity & Analytics Section -->
            <div class="row">
                <!-- Recent Activity -->
                <div class="col-lg-8 mb-4">
                    <div class="activity-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="card-title mb-1">@lang('global.activity')</h5>
                                    <p class="text-muted mb-0">@lang('admin.dashboard.usersAcDetails')</p>
                                </div>
                                <a href="{{ route('admin.activities') }}" class="btn btn-sm btn-outline-primary">
                                    @lang('global.viewAll') <i class="fas fa-arrow-left ms-1"></i>
                                </a>
                            </div>

                            <div class="activity-list">
                                @foreach($logActivities as $activity)
                                    <div class="activity-item d-flex">
                                        <div class="activity-icon me-3
                                    @if($activity->log_name == 'added') bg-success
                                    @elseif($activity->log_name == 'updated') bg-primary
                                    @elseif($activity->log_name == 'deleted') bg-danger
                                    @else bg-info @endif">
                                            @if($activity->log_name == 'added')
                                                <i class="fas fa-plus"></i>
                                            @elseif($activity->log_name == 'updated')
                                                <i class="fas fa-edit"></i>
                                            @elseif($activity->log_name == 'deleted')
                                                <i class="fas fa-trash"></i>
                                            @else
                                                <i class="fas fa-info-circle"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                                <small class="text-muted">
                                                    @if(app()->getLocale() == 'en')
                                                        {{ date_format($activity->created_at, 'Y-M-d') }}
                                                    @else
                                                        @php
                                                            $date = \Morilog\Jalali\CalendarUtils::strftime('Y-M-d', strtotime($activity->created_at));
                                                            echo \Morilog\Jalali\CalendarUtils::convertNumbers($date);
                                                        @endphp
                                                    @endif
                                                </small>
                                            </div>
                                            <p class="text-muted mb-0 small">
                                                @lang('global.by'):
                                                @php
                                                    $user = \App\Models\User::where('id', $activity->causer_id)->first();
                                                    echo $user->name ?? 'System';
                                                @endphp
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Users & Quick Stats -->
                <div class="col-lg-4 mb-4">
                    <!-- Top Users -->
                    <div class="user-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">@lang('admin.dashboard.activeUsers')</h5>
                            <div class="user-list">
                                @foreach($top_users as $index => $user)
                                    <div class="user-item d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            <img src="{{ $user->image ? $user->image : asset('assets/images/avatar-default.jpeg') }}"
                                                 alt="{{ $user->name }}" class="img-fluid">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                   class="text-decoration-none">
                                                    {{ $user->name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $user->roles->first()->name ?? '' }}</small>
                                        </div>
                                        <div class="user-activity-rate">
                                            @if(count($user->activities()) > 0)
                                                {{ round((count($user->activities()) / count(\Spatie\Activitylog\Models\Activity::all())) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="chart-container">
                        <div class="card-body">
                            <h5 class="card-title mb-4">@lang('pages.dashboard.quickStats')</h5>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="chart-mini text-center">
                                        <div class="chart-mini-value">{{ \App\Models\Office\Employee::all()->count() }}</div>
                                        <div class="chart-mini-label">@lang('global.organization')</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="chart-mini text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <div class="chart-mini-value">{{ count(\App\Models\Place::all()) }}</div>
                                        <div class="chart-mini-label">@lang('pages.dashboard.places')</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chart-mini text-center" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2c3e50;">
                                        <div class="chart-mini-value">{{ count(\App\Models\Office\PositionCode::all()) }}</div>
                                        <div class="chart-mini-label">@lang('pages.dashboard.positions')</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chart-mini text-center" style="background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);">
                                        <div class="chart-mini-value">{{ count(\App\Models\Office\Company::all()) }}</div>
                                        <div class="chart-mini-label">@lang('pages.companies.companies')</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <!-- System Status -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">@lang('pages.dashboard.systemStatus')</h5>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(102, 126, 234, 0.1);">
                                    <i class="fas fa-database fa-2x text-primary mb-2"></i>
                                    <h5 class="mb-1" id="memoryUsage">{{ number_format(memory_get_usage() / 1024 / 1024, 2) }} MB</h5>
                                    <small class="text-muted">@lang('pages.dashboard.memoryUsage')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(40, 167, 69, 0.1);">
                                    <i class="fas fa-server fa-2x text-success mb-2"></i>
                                    <h5 class="mb-1" id="systemLoad">
                                        @php
                                            if (function_exists('sys_getloadavg')) {
                                                $load = sys_getloadavg();
                                                echo round($load[0], 2);
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </h5>
                                    <small class="text-muted">@lang('pages.dashboard.systemLoad')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(255, 193, 7, 0.1);">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5 class="mb-1" id="responseTime">{{ round((microtime(true) - LARAVEL_START) * 1000, 2) }} ms</h5>
                                    <small class="text-muted">@lang('pages.dashboard.responseTime')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(23, 162, 184, 0.1);">
                                    <i class="fas fa-code-branch fa-2x text-info mb-2"></i>
                                    <h5 class="mb-1">{{ config('app.version', '1.0.0') }}</h5>
                                    <small class="text-muted">@lang('pages.dashboard.appVersion')</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional System Info -->
                        <div class="row mt-3 text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(108, 117, 125, 0.1);">
                                    <i class="fas fa-users fa-2x text-secondary mb-2"></i>
                                    <h5 class="mb-1">{{ \App\Models\User::online()->count() }}</h5>
                                    <small class="text-muted">@lang('pages.dashboard.onlineUsers')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(111, 66, 193, 0.1);">
                                    <i class="fas fa-hdd fa-2x text-purple mb-2"></i>
                                    <h5 class="mb-1" id="diskUsage">
                                        @php
                                            $diskTotal = disk_total_space('/');
                                            $diskFree = disk_free_space('/');
                                            $diskUsed = $diskTotal - $diskFree;
                                            $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100, 2) : 0;
                                            echo $diskPercent . '%';
                                        @endphp
                                    </h5>
                                    <small class="text-muted">@lang('pages.dashboard.diskUsage')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                    <h5 class="mb-1">{{ \App\Models\Office\Employee::where('status', 1)->count() }}</h5>
                                    <small class="text-muted">@lang('pages.dashboard.pendingActions')</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(32, 201, 151, 0.1);">
                                    <i class="fas fa-sync-alt fa-2x text-teal mb-2"></i>
                                    <h5 class="mb-1" id="uptime">
                                        @php
                                            if (function_exists('shell_exec')) {
                                                try {
                                                    $uptime = shell_exec('uptime');
                                                    if ($uptime) {
                                                        echo substr($uptime, 0, strpos($uptime, ','));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                } catch (Exception $e) {
                                                    echo 'N/A';
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </h5>
                                    <small class="text-muted">@lang('pages.dashboard.systemUptime')</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        $(document).ready(function() {
            // Animate progress bars on scroll
            function animateProgressBars() {
                $('.progress-bar-fill').each(function() {
                    const width = $(this).attr('style').match(/width: (\d+)%/);
                    if (width) {
                        $(this).css('width', '0%').animate({
                            width: width[1] + '%'
                        }, 1000);
                    }
                });
            }

            // Initialize animations when elements are in view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateProgressBars();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            // Observe the statistics section
            observer.observe(document.querySelector('.row.mb-4'));

            // Update time every minute
            function updateTime() {
                const now = new Date();
                const timeElement = $('.display-4');
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                timeElement.text(`${hours}:${minutes}`);
            }

            setInterval(updateTime, 60000);

            // Update system stats every 30 seconds
            function updateSystemStats() {
                // Update memory usage
                $.ajax({
                    url: '{{ route("admin.system.stats") }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.memory_usage) {
                            $('#memoryUsage').text(data.memory_usage + ' MB');
                        }
                        if (data.response_time) {
                            $('#responseTime').text(data.response_time + ' ms');
                        }
                        if (data.disk_usage) {
                            $('#diskUsage').text(data.disk_usage + '%');
                        }
                    }
                });
            }

            // Call initially and then every 30 seconds
            updateSystemStats();
            setInterval(updateSystemStats, 30000);

            // Card hover effects
            $('.stat-card').hover(
                function() {
                    $(this).find('.stat-card-icon').css('transform', 'scale(1.1) rotate(5deg)');
                },
                function() {
                    $(this).find('.stat-card-icon').css('transform', 'scale(1) rotate(0deg)');
                }
            );

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Add loading animation for stats
            $('.stat-card-value').each(function() {
                const $this = $(this);
                const text = $this.text().trim();
                const target = parseInt(text.replace(/,/g, ''));

                if (!isNaN(target)) {
                    let current = 0;
                    const increment = target / 50;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            $this.text(text);
                            clearInterval(timer);
                        } else {
                            $this.text(Math.floor(current).toLocaleString());
                        }
                    }, 20);
                }
            });

            // Add pulse animation to active items
            setInterval(() => {
                $('.user-item:first-child, .activity-item:first-child').toggleClass('pulse');
            }, 2000);
        });

        // Add pulse animation CSS
        const style = document.createElement('style');
        style.innerHTML = `
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        .user-item, .activity-item {
            position: relative;
            overflow: hidden;
        }

        .user-item.pulse, .activity-item.pulse {
            border-left: 3px solid #667eea;
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
