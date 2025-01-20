<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="{{ route('website.index') }}" target="_blank">
            <span class="text-capitalize">BCD-MIS</span>
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img icon-logo" alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img desktop-logo theme-logo"
                 alt="logo">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="header-brand-img icon-logo theme-logo" alt="logo">
        </a>
    </div>

    <div class="main-sidebar-body">
        <ul class="nav">
            <!-- Dashboard -->
            <li class="nav-label">@lang('admin.dashboard.dashboard')</li>
            <li class="nav-item {{ request()->url() == route('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fe fe-airplay"></i><span class="sidemenu-label">@lang('admin.dashboard.dashboard')</span>
                </a>
            </li>

            <!-- Places -->
            @can('place_view')
                <li class="nav-item {{ request()->is('admin/places') || request()->is('admin/places/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.places.index') }}">
                        <i class="fa fa-map-marker"></i>
                        <span class="sidemenu-label">
                        @lang('pages.dashboard.places')
                        ({{ \App\Models\Place::all()->count() }})
                    </span>
                    </a>
                </li>
            @endcan

            <!-- Documents -->
            @can('docs_view')
                @if(auth()->user()->isAdmin())
                    <li class="nav-item {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.documents.index') }}">
                            <i class="fe fe-file-text"></i>
                            <span class="sidemenu-label">
                                @lang('admin.sidebar.documents')
                                ({{ \App\Models\Document::all()->count() }})
                            </span>
                        </a>
                    </li>
                @else
                    <li class="nav-item {{ request()->is('admin/documents') ||
                    request()->is('admin/documents/*') ||
                    request()->is('admin/received-documents') ? 'active show' : '' }}">

                        <!-- Main T -->
                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fe fe-file-text"></i>

                            <span class="sidemenu-label">@lang('admin.sidebar.documents')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <!-- PHP -->
                        @php
                            $auth_user_pos = auth()->user()->employee->position;
                        @endphp

                            <!-- Sub Items -->
                        <ul class="nav-sub">
                            <!-- Sent Documents -->
                            <li class="nav-sub-item {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.documents.index') }}">
                                    @lang('admin.sidebar.sentDocs')
                                    @if($auth_user_pos)
                                        ({{ count(\App\Models\Document::all()->where('position_id', $auth_user_pos->id)) }}
                                        )
                                    @endif
                                </a>
                            </li>

                            <!-- Received Documents -->
                            <li class="nav-sub-item {{ request()->is('admin/received-documents') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.documents.received') }}">
                                    @php $pos_docs = \App\Models\Document::where('position_id', auth()->user()->employee->position->id)->select('cc')->get(); @endphp
                                    @lang('admin.sidebar.receivedDocs')
                                    (<?php
                                         // Check if Authenticated Employee is On Duty or not
                                         if (\Illuminate\Support\Facades\Auth::user()->employee->on_duty == 1) {
                                             // Count all Received Documents
                                             \App\Models\Document::all()->where('receiver', \Illuminate\Support\Facades\Auth::user()->employee->duty_position)->count();
                                             +\App\Models\Document::all()->where('cc', strpos(\Illuminate\Support\Facades\Auth::user()->employee->duty_position, $pos_docs))->count();
                                         } else {
                                             // Count all Received Documents
                                             \App\Models\Document::all()->where('receiver', $auth_user_pos->title)->count();
                                             +\App\Models\Document::all()->where('cc', str()->contains(\App\Models\Document::all()->pluck('cc'), $auth_user_pos->title))->count();
                                         }
                                         ?>)
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan

            <!-- Asycuda -->
            @can('asycuda_view')
                <li class="nav-label">@lang('admin.sidebar.systemGenMgmt')</li>

                <li class="nav-item {{ request()->is('admin/asycuda/users') ||
                    request()->is('admin/asycuda/users/*') ||
                    request()->is('admin/asycuda/inactive-users') ||
                    request()->is('admin/asycuda/coal') ||
                    request()->is('admin/asycuda/coal/*') ||
                    request()->is('admin/asycuda/expired-coal') ? 'active show' : '' }}">

                    <a class="nav-link with-sub" href="javascript:void(0)">
                        <i class="ion ion-ios-desktop"></i>
                        {{--                        @if(count(\App\Models\Asycuda\COAL::all()->where('expire_date', "<=", today())->where('status', 1)) >= 1)--}}
                        {{--                            <span class="pulse"></span>--}}
                        {{--                        @endif--}}
                        <span class="sidemenu-label">@lang('admin.sidebar.systemGenMgmt')</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="nav-sub">
                        <!-- Employees Users -->
                        @can('asy_user_view')
                            <li class="nav-sub-item {{ request()->is('admin/asycuda/users') || request()->is('admin/asycuda/users/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.asycuda.users.index') }}">
                                    @lang('admin.sidebar.asyUserAccs')
                                    ({{ count(\App\Models\Asycuda\AsycudaUser::all()->where('status', 1)) }})
                                </a>
                            </li>

                            <!-- Employees Inactive Users -->
                            <li class="nav-sub-item {{ request()->is('admin/asycuda/inactive-users') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.asycuda.users.inactive') }}">
                                    @lang('admin.sidebar.asyInactiveUserAccs')
                                    ({{ count(\App\Models\Asycuda\AsycudaUser::all()->where('status', 0)) }})
                                </a>
                            </li>
                        @endcan

                        <!-- Companies Activity License -->
                        @can('asy_coal_view')
                            <li class="nav-sub-item {{ request()->is('admin/asycuda/coal') || request()->is('admin/asycuda/coal/*') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.asycuda.coal.index') }}">
                                    @lang('admin.sidebar.companiesAL')
                                    ({{ count(\App\Models\Asycuda\COAL::all()->where('status', 1)) }})
                                    {{--                                    @if(count(\App\Models\Asycuda\COAL::all()->where('expire_date', "<=", today())->where('status', 1)) >= 1)--}}
                                    {{--                                        &nbsp;<span class="fas fa-building fa-pulse text-danger"></span>--}}
                                    {{--                                    @endif--}}
                                </a>
                            </li>

                            <!-- Expired Companies Activity License -->
                            <li class="nav-sub-item {{ request()->is('admin/asycuda/expired-coal') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.asycuda.coal.expired') }}">
                                    @lang('admin.sidebar.expiredAL')
                                    ({{ count(\App\Models\Asycuda\COAL::all()->where('status', 0)) }})
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            <!--/==/ End of Asycuda -->

            <!-- Office Routes -->
            @can('office_view')
                <li class="nav-label">@lang('admin.sidebar.offFGenMgmt')</li>

                <!-- Finance -->
                @can('office_finance_view')
                    <li class="nav-item {{ request()->is('admin/office/budgets') ||
                    request()->is('admin/office/budgets/*') ? 'active show' : '' }}">

                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fas fa-dollar-sign"></i>
                            <span class="sidemenu-label">@lang('admin.sidebar.finance')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Budgets -->
                            <li class="nav-sub-item {{ request()->is('admin/office/budgets') ||
                                request()->is('admin/office/budgets/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.budgets.index') }}">
                                    @lang('admin.sidebar.budgets')
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <!--/==/ End of Finance -->

                <!-- Agents -->
                @can('office_agent_view')
                    <li class="nav-item {{ request()->is('admin/office/agents') ||
                    request()->is('admin/office/agents/*') ||
                    request()->is('admin/office/agent/add-company/*') ||
                    request()->is('admin/office/agent/add-colleague/*') ||
                    request()->is('admin/office/inactive-agents') ||
                    request()->is('admin/office/agent-colleagues') ||
                    request()->is('admin/office/agent-colleagues/*') ||
                    request()->is('admin/office/inactive-agent-colleagues') ? 'active show' : '' }}">

                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fa fa-user-tie"></i>
                            <span class="sidemenu-label">@lang('pages.companies.agents')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Agents -->
                            <li class="nav-sub-item {{ request()->is('admin/office/agents') ||
                            request()->is('admin/office/agents/*') ||
                            request()->is('admin/office/agent/add-company/*') ||
                            request()->is('admin/office/agent/add-colleague/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.agents.index') }}">
                                    @lang('pages.companies.agents')
                                    ({{ \App\Models\Office\Agent::all()->where('status', 1)->count() }})
                                </a>
                            </li>

                            <!-- Inactive Agents -->
                            <li class="nav-sub-item {{ request()->is('admin/office/inactive-agents') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.agents.inactive') }}">
                                    @lang('admin.sidebar.inactiveAgents')
                                    ({{ count(\App\Models\Office\Agent::all()->where('status', 0)) }})
                                </a>
                            </li>

                            <!-- Agent Colleagues -->
                            <li class="nav-sub-item {{ request()->is('admin/office/agent-colleagues') ||
                            request()->is('admin/office/agent-colleagues/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.agent-colleagues.index') }}">
                                    @lang('admin.sidebar.agentColleagues')
                                    ({{ count(\App\Models\Office\AgentColleague::all()->where('status', 1)) }})
                                </a>
                            </li>

                            <!-- Agent Inactive Colleagues -->
                            <li class="nav-sub-item {{ request()->is('admin/office/inactive-agent-colleagues') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.agent-colleagues.inactive') }}">
                                    @lang('admin.sidebar.agentColleagues') (@lang('global.inactive'))
                                    ({{ count(\App\Models\Office\AgentColleague::all()->where('status', 0)) }})
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <!--/==/ End of Agents -->

                <!-- Companies -->
                @can('office_company_view')
                    <li class="nav-item {{ request()->is('admin/office/companies') ||
                    request()->is('admin/office/companies/*') ||
                    request()->is('admin/office/inactive-companies') ? 'active show' : '' }}">

                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fa fa-shopping-bag"></i>
                            <span class="sidemenu-label">@lang('admin.sidebar.companies')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Active Companies -->
                            <li class="nav-sub-item {{ request()->is('admin/office/companies') ||
                        request()->is('admin/office/companies/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.companies.index') }}">
                                    @lang('admin.sidebar.companies')
                                    ({{ count(\App\Models\Office\Company::all()->where('status', 1)) }})
                                </a>
                            </li>

                            <!-- Inactive Companies -->
                            <li class="nav-sub-item {{ request()->is('admin/office/inactive-companies') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.companies.inactive') }}">
                                    @lang('admin.sidebar.inactiveCompanies')
                                    ({{ count(\App\Models\Office\Company::all()->where('status', 0)) }})
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <!--/==/ End of Companies -->

                <li class="nav-label">@lang('admin.sidebar.hrMgmt')</li>

                <!-- Positions -->
                @can('office_position_view')
                    <li class="nav-item {{ request()->is('admin/office/positions') ||
                    request()->is('admin/office/positions/*') ||
                    request()->is('admin/office/appointment-positions') ||
                    request()->is('admin/office/empty-positions') ||
                    request()->is('admin/office/inactive-positions') ? 'active show' : '' }}">

                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fe fe-life-buoy"></i>
                            <span class="sidemenu-label">@lang('admin.sidebar.positions')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Positions -->
                            <li class="nav-sub-item {{ request()->is('admin/office/positions') ||
                                request()->is('admin/office/positions/*') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.positions.index') }}">
                                    {<span class="small text-sm-center tx-danger">M</span>}
                                    @lang('pages.positions.allPositions')
                                    ({{ \App\Models\Office\Position::all()->sum('num_of_pos') }})
                                </a>
                            </li>

                            <!-- Appointment Positions -->
                            <li class="nav-sub-item {{ request()->is('admin/office/appointment-positions') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.positions.appointment') }}">
                                    @lang('pages.positions.appointmentPositions')
                                    ({{ \App\Models\Office\PositionCode::whereHas('employee')->get()->count() }})
                                </a>
                            </li>

                            <!-- Empty Positions -->
                            <li class="nav-sub-item {{ request()->is('admin/office/empty-positions') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.positions.empty') }}">
                                    @lang('pages.positions.emptyPositions')
                                    ({{ \App\Models\Office\PositionCode::whereDoesntHave('employee')->get()->count() }})
                                </a>
                            </li>

                            <!-- Inactive Positions -->
                            <li class="nav-sub-item {{ request()->is('admin/office/inactive-positions') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.positions.inactive') }}">
                                    @lang('pages.positions.inactivePositions')
                                    ({{ \App\Models\Office\Position::all()->where('status', 0)->count() }})
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <!--/==/ End of Position -->

                <!-- Employees -->
                @can('office_employee_view')
                    <li class="nav-item {{ request()->is('admin/office/employees') ||
                    request()->is('admin/office/employees/*') ||
                    request()->is('admin/office/main-employees') ||
                    request()->is('admin/office/on-duty-employees') ||
                    request()->is('admin/office/position-conversion-employees') ||
                    request()->is('admin/office/fired-employees') ||
                    request()->is('admin/office/suspended-employees') ||
                    request()->is('admin/office/retired-employees') ||
                    request()->is('admin/office/ocustom-duty-employees') ||
                    request()->is('admin/office/employee/*/resumes') ||
                    request()->is('admin/office/employee/*/add-duty-position') ||
                    request()->is('admin/office/employee/*/change-to-main-position') ||
                    request()->is('admin/office/employee/*/leaves') ||
                    request()->is('admin/office/employee/*/leaves/create') ? 'active show' : '' }}">

                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fa fa-user-tie"></i>
                            @if(count(\App\Models\Office\Employee::all()->where('status', 4)) >= 1)
                                <span class="pulse"></span>
                            @endif
                            <span class="sidemenu-label">@lang('admin.sidebar.employees')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- All Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/employees') ||
                                request()->is('admin/office/employees/*') ||
                                request()->is('admin/office/employee/*/resumes') ||
                                request()->is('admin/office/employee/*/add-duty-position') ||
                                request()->is('admin/office/employee/*/change-to-main-position') ||
                                request()->is('admin/office/employee/*/leaves') ||
                                request()->is('admin/office/employee/*/leaves/create') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.index') }}">
                                    {<span class="small text-sm-center tx-danger">M</span>}
                                    @lang('admin.sidebar.allCurEmployees')
                                    ({{ count(\App\Models\Office\Employee::all()->whereNotNull('position_id')) }})
                                </a>
                            </li>

                            <!-- Main Position Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/main-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.main') }}">
                                    @lang('pages.employees.mainPosition')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 0)->count() }}
                                    )
                                </a>
                            </li>

                            <!-- On Duty Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/on-duty-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.on_duty') }}">
                                    @lang('pages.employees.onDuty')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 0)->where('on_duty', 1)->count() }}
                                    )
                                </a>
                            </li>

                            <!-- Retired Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/retired-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.retired_employees') }}">
                                    @lang('admin.sidebar.retired')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 1)->count() }})
                                </a>
                            </li>

                            <!-- Fired Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/fired-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.fired_employees') }}">
                                    @lang('admin.sidebar.fired')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 2)->count() }})
                                </a>
                            </li>

                            <!-- Position Conversion Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/position-conversion-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.office.employees.position_conversion_employees') }}">
                                    @lang('admin.sidebar.converted')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 3)->count() }})
                                </a>
                            </li>

                            <!-- Suspended Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/suspended-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.office.employees.suspended_employees') }}">
                                    <span class="text-secondary">@lang('admin.sidebar.suspended') </span>&nbsp;
                                    ({{ \App\Models\Office\Employee::all()->where('status', 4)->count() }})
                                    @if(count(\App\Models\Office\Employee::all()->where('status', 4)) >= 1)
                                        &nbsp;<span class="fas fa-user-tie fa-pulse text-danger"></span>
                                    @endif
                                </a>
                            </li>

                            <!-- Other Custom Duty Employees -->
                            <li class="nav-sub-item {{ request()->is('admin/office/ocustom-duty-employees') ? 'active' : '' }}">
                                <a class="nav-sub-link" href="{{ route('admin.office.employees.oc_duty_employees') }}">
                                    @lang('admin.sidebar.onDutyOC')
                                    ({{ \App\Models\Office\Employee::all()->where('status', 5)->count() }})
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                <!--/==/ End of Employees -->

                <!-- Hostel -->
                @can('office_hostel_view')
                    <li class="nav-item {{ request()->url() == route('admin.office.hostel.index') || request()->is('admin/office/hostel/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.office.hostel.index') }}">
                            <i class="fa fa-building"></i><span
                                class="sidemenu-label">@lang('pages.hostel.hostel')</span>
                        </a>
                    </li>
                @endcan
            @endcan

            <!-- General Management Of PreferentialTariff Examination -->
            @can('examination_view')
                <li class="nav-label">@lang('admin.sidebar.exPropGenMgmt')</li>
                @can('examination_pt_view')
                    <li class="nav-item {{ request()->is('admin/examination/preferential_tariffs') ||
                        request()->is('admin/examination/preferential_tariffs/*') ? 'active show' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fa fa-file-alt"></i>
                            <span class="sidemenu-label">@lang('admin.sidebar.preferredTarrif')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Properties -->
                            <li class="nav-sub-item {{ request()->is('admin/examination/preferential_tariffs') ||
                                request()->is('admin/examination/preferential_tariffs/*') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.examination.preferential_tariffs.index') }}">@lang('admin.sidebar.propertyEstate')
                                    ({{ count(\App\Models\Examination\PreferentialTariff::all()->where('status', 1)) }}
                                    )</a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan
            <!--/==/ End of General Management Of PreferentialTariff Examination -->

            <!-- General Management Of Warehouses -->
            @can('warehouse_view')
                <li class="nav-label">@lang('admin.sidebar.warehousesGenMgmt')</li>
                @can('warehouse_assurance_view')
                    <li class="nav-item {{ request()->is('admin/warehouse/assurances') ||
                        request()->is('admin/warehouse/assurances/*') ||
                        request()->is('admin/warehouse/returned-assurances') ||
                        request()->is('admin/warehouse/absolute-assurances') ? 'active show' : '' }}">
                        <a class="nav-link with-sub" href="javascript:void(0)">
                            <i class="fe fe-dollar-sign"></i>
                            <span class="sidemenu-label">@lang('admin.sidebar.assurance')</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                        <ul class="nav-sub">
                            <!-- Current Assurance -->
                            <li class="nav-sub-item {{ request()->is('admin/warehouse/assurances') || request()->is('admin/warehouse/assurances/*') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.warehouse.assurances.index') }}">@lang('admin.sidebar.onGoing')
                                    ({{ count(\App\Models\Warehouse\Assurance::all()->where('status', 1)) }})</a>
                            </li>
                            <!-- Returned Assurance -->
                            <li class="nav-sub-item {{ request()->is('admin/warehouse/returned-assurances') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.warehouse.assurances.returned') }}">@lang('admin.sidebar.returned')
                                    ({{ count(\App\Models\Warehouse\Assurance::all()->where('status', 2)) }})</a>
                            </li>
                            <!-- Absolute Assurance -->
                            <li class="nav-sub-item {{ request()->is('admin/warehouse/absolute-assurances') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.warehouse.assurances.absolute') }}">@lang('admin.sidebar.absolute')
                                    ({{ count(\App\Models\Warehouse\Assurance::all()->where('status', 3)) }})</a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endcan
            <!--/==/ End of General Management Of Warehouses -->

            <!-- Website -->
            @can('website_view')
                <li class="nav-label">@lang('global.website')</li>

                <!-- Website -->
                @can('website_announcement_view')
                    <li class="nav-item {{ request()->is('admin/website/announcements') || request()->is('admin/website/announcements/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.website.announcements.index') }}">
                            <i class="fa fa-bell"></i>
                            <span class="sidemenu-label">
                                @lang('pages.website.announcements')
                            </span>
                        </a>
                    </li>
                @endcan
            @endcan

            <!-- Applications -->
            <li class="nav-label">@lang('admin.sidebar.applications')</li>

            <!-- User Management -->
            @can('user_view')
                <li class="nav-item {{ request()->is('admin/permissions') ||
                    request()->is('admin/permissions/*') ||
                    request()->is('admin/roles') ||
                    request()->is('admin/roles/*') ||
                    request()->is('admin/users') ||
                    request()->is('admin/users/*') ||
                    request()->is('admin/active-users') ||
                    request()->is('admin/inactive-users') ? 'active show' : '' }}">
                    <a class="nav-link with-sub" href="javascript:void(0)">
                        <i class="fe fe-users"></i>
                        <span class="sidemenu-label">@lang('admin.sidebar.userManagement')</span>
                        <i class="angle fe fe-chevron-right"></i>
                    </a>

                    <ul class="nav-sub">
                        @can('user_mgmt')
                            <!-- Permissions -->
                            <li class="nav-sub-item {{ request()->is('admin/permissions') ||
                            request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.permissions.index') }}">@lang('admin.sidebar.permissions')</a>
                            </li>

                            <!-- Roles -->
                            <li class="nav-sub-item {{ request()->is('admin/roles') ||
                            request()->is('admin/roles/*') ? 'active' : '' }}">
                                <a class="nav-sub-link"
                                   href="{{ route('admin.roles.index') }}">@lang('admin.sidebar.roles')</a>
                            </li>
                        @endcan

                        <!-- Users -->
                        <li class="nav-sub-item {{ request()->is('admin/users') ||
                            request()->is('admin/users/*') ||
                            request()->is('admin/active-users') ||
                            request()->is('admin/inactive-users') ? 'active' : '' }}">
                            <a class="nav-sub-link"
                               href="{{ route('admin.users.index') }}">@lang('admin.sidebar.users')</a>
                        </li>
                    </ul>
                </li>
            @endif
            <!--/==/ End of User Management -->

            <!-- Settings -->
            @can('setting_mgmt')
                <li class="nav-item {{ request()->url() == route('admin.settings.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.settings.index') }}">
                        <i class="fe fe-settings"></i><span
                            class="sidemenu-label">@lang('admin.sidebar.settings')</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
