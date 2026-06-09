<nav class="navbar navbar-expand-lg hospital-navbar shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <x-hospital-logo class="me-2" />
                        <div class="ms-2">
                            <div class="fw-bold brand-accent" ><img src="{{asset('images/logo.png')}}" alt="logo" width="60"> <span style="transform: scaleY(2);">FAYHOS</span> </div>
                        </div>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="#mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bi bi-house-fill me-2 text-success"></i>Home</a>
                            </li>

                            @if(Auth::user()->hasRole('record'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('record.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('nurse'))

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('nurse.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>{{ count(auth()->user()->pendingServiceRequests()) }} Patients</a>
                            </li>
                            @endif



                            @if(Auth::user()->hasRole('doctor'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i> {{ count(auth()->user()->pendingServiceRequests()) }} Patients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Admission</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Discharge</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('midwife'))
                            
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('midwife.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i> {{ count(auth()->user()->pendingServiceRequests()) }} ANC Patients</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('midwife'))
                            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-lightning-fill me-2 text-success"></i>
                                        Quick Actions
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.antenatal.index') }}">

                                                <i class="bi bi-heart-pulse-fill me-2 text-primary"></i>
                                                Antenatal Care

                                            </a>
                                        </li>

                                        <!-- Labour -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.labour.index') }}">

                                                <i class="bi bi-activity me-2 text-warning"></i>
                                                Labour Management

                                            </a>
                                        </li>

                                        <!-- Delivery -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.delivery.index') }}">

                                                <i class="bi bi-hospital-fill me-2 text-danger"></i>
                                                Delivery Records

                                            </a>
                                        </li>

                                        <!-- Newborn -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn.index') }}">

                                                <i class="bi bi-bandaid-fill me-2 text-info"></i>
                                                Newborn Records

                                            </a>
                                        </li>

                                        <!-- Newborn Examinations -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn-examination.index') }}">

                                                <i class="bi bi-clipboard2-pulse-fill me-2 text-success"></i>
                                                Newborn Examinations

                                            </a>
                                        </li>

                                        <!-- Postnatal Examination -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.postnatal-examination.index') }}">

                                                <i class="bi bi-journal-medical me-2 text-secondary"></i>
                                                Postnatal Examination

                                            </a>
                                        </li>

                                        <!-- Child Follow Up -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.child-follow-up.index') }}">

                                                <i class="bi bi-arrow-repeat me-2 text-dark"></i>
                                                Child Follow-up

                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            

                            @endif

                            @if(Auth::user()->hasRole('lab_technician') || Auth::user()->hasRole('lab_scientist'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{auth()->user()->department->requestStats()['pending']}} Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            @endif
                            @if(Auth::user()->hasRole('head_of_department'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.consumables.index') }}"><i class="bi bi-box-seam me-2 text-success"></i>Consumables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.stocks.index') }}"><i class="bi bi-boxes me-2 text-success"></i>Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.expenses.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i>Expense</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('radiologist'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiology.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{auth()->user()->department->requestStats()['pending']}} Requests</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiology.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('accountant'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.bills.index') }}"><i class="fa-solid fa-naira-sign me-2 text-success"></i>  Billing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.payments.index') }}"><i class="bi bi-receipt me-2 text-success"></i>Payments</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('administrator'))
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.index') }}"><i class="bi bi-shield-check me-2 text-success"></i>Access Control</a>
                                </li>
                                <!-- Add more admin-specific links here -->
                                 <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.services.index') }}"><i class="bi bi-gear-fill me-2 text-success"></i>Services</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.investigations.index') }}"><i class="bi bi-clipboard2-data me-2 text-success"></i>Investigation</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.departments.index') }}"><i class="bi bi-buildings me-2 text-success"></i>Departments</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.wards.index') }}"><i class="bi bi-buildings me-2 text-success"></i>Wards</a>
                                </li>

                                
                            @endif

                            @if(Auth::user()->hasRole('pharmacist'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Prescriptions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.transactions.index')}}">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Transactions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.medicines.index')}}">
                                        <i class="bi bi-capsule me-1"></i>
                                        Medicine
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.stocks.index')}}">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Stock
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.expiries.index')}}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Expiry Alerts
                                    </a>
                                </li>

                                
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-cash-stack me-2 text-success"></i>
                                    Finance
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                    
                                    @if(Auth::user()->hasRole('administrator'))
                                        <li><a class="dropdown-item" href="{{ route('admin.bills.index') }}"><i class="bi bi-receipt me-2"></i> Bills Management</a></li>
                                        <li><a class="dropdown-item" href="{{ route('reports.finance.index') }}"><i class="bi bi-file-earmark-text me-2"></i> Billing Report</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.expenses.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i> Expenses</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.revenues.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i> Revenues</a></li>
                                    @endif
                                </ul>
                            </li>

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                        @if(Auth::user()->hasRole('administrator'))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.system.update') }}">Check System Update</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.sync.dashboard') }}">Data Synchronization Dashboard</a>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Log Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="btn btn-outline-success ms-2 d-flex align-items-center" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
