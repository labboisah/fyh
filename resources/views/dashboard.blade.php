@extends('layouts.app')
@section('content')

    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-muted">Patients</div>
                            <h3 class="mb-0">--</h3>
                        </div>
                        <div class="text-success fs-3"> <i class="bi bi-people-fill"></i> </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-muted">Records</div>
                            <h3 class="mb-0">--</h3>
                        </div>
                        <div class="text-primary fs-3"> <i class="bi bi-folder-fill"></i> </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-muted">Users</div>
                            <h3 class="mb-0">{{ App\Models\User::count() }}</h3>
                        </div>
                        <div class="text-warning fs-3"> <i class="bi bi-person-badge-fill"></i> </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small text-muted">Appointments</div>
                            <h3 class="mb-0">--</h3>
                        </div>
                        <div class="text-danger fs-3"> <i class="bi bi-calendar2-check-fill"></i> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recent Activity</h5>
                    <p class="text-muted">No activity to show yet. When the system has records, recent actions will appear here.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ url('/patients/create') }}" class="btn btn-accent btn-sm">New Patient</a>
                        <a href="{{ url('/records/create') }}" class="btn btn-outline-secondary btn-sm">New Record</a>
                        @if(Auth::check() && Auth::user()->hasRole('administrator'))
                            <a href="{{ url('/admin') }}" class="btn btn-outline-dark btn-sm">Admin Panel</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

