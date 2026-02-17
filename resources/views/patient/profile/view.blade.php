    <div class="col-lg-12">

        <div class="card border-0 shadow-sm mb-4">

            <!-- Tabs -->
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="tab"
                                data-bs-target="#bio"
                                type="button">
                            <i class="bi bi-person-fill me-1"></i> Bio Data
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#visits"
                                type="button">
                            <i class="bi bi-clock-history me-1"></i> Visits
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#investigations"
                                type="button">
                            <i class="bi bi-vial me-1"></i> Investigations
                        </button>
                    </li>
                    @if(auth()->user()->hasRole('nurse') || auth()->user()->hasRole('doctor'))
                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#observations"
                                type="button">
                            <i class="bi bi-vial me-1"></i> Observations
                        </button>
                    </li>
                    <!-- vital signs -->
                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#vitalsigns"
                                type="button">
                            <i class="bi bi-vial me-1"></i> Vital Signs
                        </button>
                    </li>
                     <!-- drug chart -->
                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#drugchart"
                                type="button">
                            <i class="bi bi-vial me-1"></i> Drug Chart
                        </button>
                    </li>
                    @endif
                    <!-- nursing Note -->
                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#actions"
                                type="button">
                            <i class="bi bi-vial me-1"></i> Quick Actions
                        </button>
                    </li>

                </ul>
            </div>

            <!-- Tab Content -->
            <div class="card-body tab-content">

                <!-- BIO DATA -->
                <div class="tab-pane fade show active" id="bio">

                    @include('patient.profile.infor')

                </div>

                <!-- VISITS -->
                <div class="tab-pane fade" id="visits">

                    @include('patient.profile.visits')

                </div>

                <!-- VITAL SIGNS -->
                <div class="tab-pane fade" id="vitalsigns">

                    @include('patient.profile.vitalsign')

                </div>

                <!--  DRUG CHART -->
                <div class="tab-pane fade" id="drugchart">

                    @include('patient.profile.drugchart')
                </div>
                    <!-- NURSING NOTE -->
                <div class="tab-pane fade" id="observations">
                    
                    @include('patient.profile.observations')
                </div>
                <!-- INVESTIGATIONS -->
                <div class="tab-pane fade" id="investigations">

                    @include('patient.profile.investigations')

                </div>
                <!-- QUICK ACTIONS -->
                <div class="tab-pane fade" id="actions">

                    @include('patient.profile.actions')
                </div>

            </div>
        </div>

    </div>
   