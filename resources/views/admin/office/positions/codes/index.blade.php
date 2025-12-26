@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">مدیریت کدهای بست‌ها</h1>
            <a href="{{ route('admin.office.positions.codes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> ایجاد کد جدید
            </a>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">فیلترها</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.office.positions.codes.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>وضعیت</label>
                            <select name="status" class="form-control">
                                <option value="">همه</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>اشغال شده</label>
                            <select name="occupied" class="form-control">
                                <option value="">همه</option>
                                <option value="1" {{ request('occupied') == '1' ? 'selected' : '' }}>اشغال شده</option>
                                <option value="0" {{ request('occupied') == '0' ? 'selected' : '' }}>خالی</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>بست</label>
                            <select name="position_id" class="form-control">
                                <option value="">همه بست‌ها</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                        {{ $position->title }} (درجه {{ $position->position_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter"></i> فیلتر
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    کل کدها</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-key fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    اشغال شده</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['occupied'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    خالی</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['empty'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    غیرفعال</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Codes Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">لیست کدها</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>کد</th>
                            <th>بست</th>
                            <th>وضعیت</th>
                            <th>کارمند</th>
                            <th>تاریخ ایجاد</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($codes as $code)
                            <tr>
                                <td>
                                    <strong>{{ $code->code }}</strong>
                                    @if($code->info)
                                        <br><small class="text-muted">{{ $code->info }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $code->position->title }}
                                    <br><small class="text-muted">درجه {{ $code->position->position_number }}</small>
                                </td>
                                <td>
                                    {!! $code->getStatusBadge() !!}
                                </td>
                                <td>
                                    @if($code->employee)
                                        <a href="{{ route('admin.office.employees.show', $code->employee->id) }}">
                                            {{ $code->employee->name }} {{ $code->employee->last_name }}
                                        </a>
                                        <br><small class="text-muted">{{ $code->employee->emp_number }}</small>
                                    @else
                                        <span class="text-muted">بدون کارمند</span>
                                    @endif
                                </td>
                                <td>
                                    {{ jdate($code->created_at)->format('Y/m/d') }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.office.positions.codes.show', $code->id) }}"
                                           class="btn btn-sm btn-info" title="مشاهده">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.office.positions.codes.edit', $code->id) }}"
                                           class="btn btn-sm btn-warning" title="ویرایش">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                data-toggle="modal"
                                                data-target="#deleteCode{{ $code->id }}"
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @include('admin.office.positions.codes.delete', ['code' => $code])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $codes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
