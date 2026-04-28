@extends('layouts.app')

@section('title', 'Child Follow-up Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-clipboard-check"></i> Child Follow-up Details</h1>
        <div>
            <a href="{{ route('midwife.child-follow-up.edit', $childFollowUp) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('midwife.child-follow-up.index', $childFollowUp->newborn) }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patient & Follow-up Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Newborn:</strong> {{ $childFollowUp->newborn->newborn_registration_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother:</strong> {{ $childFollowUp->newborn->patient->full_name }}
                        </div>
                        <div class="col-md-4">
                            <strong>Follow-up Date/Time:</strong><br>
                            {{ $childFollowUp->follow_up_date_time?->format('M d, Y H:i') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Days of Life:</strong><br>
                            {{ $childFollowUp->days_of_life }} days
                        </div>
                        <div class="col-md-4">
                            <strong>Period:</strong><br>
                            @switch($childFollowUp->follow_up_period)
                                @case('day_3')
                                    <span class="badge bg-info">Day 3</span>
                                    @break
                                @case('day_7')
                                    <span class="badge bg-info">Day 7</span>
                                    @break
                                @case('day_10')
                                    <span class="badge bg-info">Day 10</span>
                                    @break
                                @case('day_14')
                                    <span class="badge bg-info">Day 14</span>
                                    @break
                                @case('6weeks')
                                    <span class="badge bg-primary">6 Weeks</span>
                                    @break
                                @case('3months')
                                    <span class="badge bg-primary">3 Months</span>
                                    @break
                                @case('6months')
                                    <span class="badge bg-primary">6 Months</span>
                                    @break
                                @case('year1')
                                    <span class="badge bg-success">1 Year</span>
                                    @break
                            @endswitch
                        </div>
                        <div class="col-md-6">
                            <strong>Location:</strong><br>
                            @switch($childFollowUp->location)
                                @case('home')
                                    <span class="badge bg-light text-dark">Home Visit</span>
                                    @break
                                @case('clinic')
                                    <span class="badge bg-info">Clinic</span>
                                    @break
                                @case('hospital')
                                    <span class="badge bg-warning">Hospital</span>
                                    @break
                                @case('other')
                                    <span class="badge bg-secondary">Other</span>
                                    @break
                            @endswitch
                            @if($childFollowUp->location_details)
                                <br><small>{{ $childFollowUp->location_details }}</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Health Status:</strong><br>
                            @if($childFollowUp->health_status === 'normal')
                                <span class="badge bg-success">Normal</span>
                            @elseif($childFollowUp->health_status === 'at_risk')
                                <span class="badge bg-warning">At Risk</span>
                            @elseif($childFollowUp->health_status === 'needs_referral')
                                <span class="badge bg-danger">Needs Referral</span>
                            @else
                                <span class="badge bg-dark">Referred</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Recorded By:</strong><br>
                            {{ $childFollowUp->recordedBy->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Recorded At:</strong><br>
                            {{ $childFollowUp->created_at?->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Feeding Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Feeding Type:</strong><br>
                            @switch($childFollowUp->feeding_type)
                                @case('breastfeeding')
                                    <span class="badge bg-success">Exclusive Breastfeeding</span>
                                    @break
                                @case('bottle_feeding')
                                    <span class="badge bg-info">Bottle Feeding</span>
                                    @break
                                @case('mixed')
                                    <span class="badge bg-warning">Mixed Feeding</span>
                                    @break
                                @case('other')
                                    <span class="badge bg-secondary">Other</span>
                                    @break
                            @endswitch
                        </div>
                        <div class="col-md-6">
                            <strong>How Baby is Feeding:</strong><br>
                            {{ $childFollowUp->how_baby_is_feeding ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Mother's Observations:</strong><br>
                            {{ $childFollowUp->mother_observations ?: 'None recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Vital Signs & Growth</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Temperature:</strong><br>
                            {{ $childFollowUp->temperature ? $childFollowUp->temperature . ' °C' : 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Heart Rate:</strong><br>
                            {{ $childFollowUp->heart_rate ? $childFollowUp->heart_rate . ' bpm' : 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Respiratory Rate:</strong><br>
                            {{ $childFollowUp->respiratory_rate ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Weight:</strong><br>
                            {{ $childFollowUp->weight ? $childFollowUp->weight . ' kg' : 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Length:</strong><br>
                            {{ $childFollowUp->length ? $childFollowUp->length . ' cm' : 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Head Circumference:</strong><br>
                            {{ $childFollowUp->head_circumference ? $childFollowUp->head_circumference . ' cm' : 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Weight Percentile:</strong><br>
                            {{ $childFollowUp->weight_percentile ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Weight Change Since Birth:</strong><br>
                            {{ $childFollowUp->weight_change_since_birth ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Weight Gain Rate:</strong><br>
                            {{ $childFollowUp->weight_gain_rate ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Weight Assessment:</strong><br>
                            @if($childFollowUp->weight_assessment === 'adequate')
                                <span class="badge bg-success">Adequate</span>
                            @elseif($childFollowUp->weight_assessment === 'inadequate')
                                <span class="badge bg-warning">Inadequate</span>
                            @elseif($childFollowUp->weight_assessment === 'excessive')
                                <span class="badge bg-danger">Excessive</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Physical Examination</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>General Appearance:</strong><br>
                            {{ $childFollowUp->general_appearance ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Activity Level:</strong><br>
                            @if($childFollowUp->activity_level === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($childFollowUp->activity_level === 'lethargic')
                                <span class="badge bg-warning">Lethargic</span>
                            @elseif($childFollowUp->activity_level === 'normal')
                                <span class="badge bg-info">Normal</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Alertness:</strong><br>
                            @if($childFollowUp->alertness === 'alert')
                                <span class="badge bg-success">Alert</span>
                            @elseif($childFollowUp->alertness === 'drowsy')
                                <span class="badge bg-warning">Drowsy</span>
                            @elseif($childFollowUp->alertness === 'unresponsive')
                                <span class="badge bg-danger">Unresponsive</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-12">
                            <strong>Skin Examination:</strong><br>
                            {{ $childFollowUp->skin_examination ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Umbilical Cord & Jaundice</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Umbilical Cord Status:</strong><br>
                            {{ $childFollowUp->umbilical_cord_status ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Umbilical Discharge:</strong><br>
                            {{ $childFollowUp->umbilical_discharge ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Signs of Infection:</strong><br>
                            {{ $childFollowUp->signs_of_infection ?: 'None recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Jaundice Present:</strong><br>
                            {{ $childFollowUp->jaundice_present ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Jaundice Level:</strong><br>
                            @if($childFollowUp->jaundice_level === 'mild')
                                <span class="badge bg-warning">Mild</span>
                            @elseif($childFollowUp->jaundice_level === 'moderate')
                                <span class="badge bg-orange">Moderate</span>
                            @elseif($childFollowUp->jaundice_level === 'high')
                                <span class="badge bg-danger">High</span>
                            @elseif($childFollowUp->jaundice_level === 'severe')
                                <span class="badge bg-dark">Severe</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Jaundice Management:</strong><br>
                            {{ $childFollowUp->jaundice_management ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Breastfeeding Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Breast Examination:</strong><br>
                            {{ $childFollowUp->breast_examination ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Latching Quality:</strong><br>
                            @if($childFollowUp->latching_quality === 'good')
                                <span class="badge bg-success">Good</span>
                            @elseif($childFollowUp->latching_quality === 'fair')
                                <span class="badge bg-warning">Fair</span>
                            @elseif($childFollowUp->latching_quality === 'poor')
                                <span class="badge bg-danger">Poor</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Suckling Pattern:</strong><br>
                            @if($childFollowUp->suckling_pattern === 'good')
                                <span class="badge bg-success">Good</span>
                            @elseif($childFollowUp->suckling_pattern === 'fair')
                                <span class="badge bg-warning">Fair</span>
                            @elseif($childFollowUp->suckling_pattern === 'poor')
                                <span class="badge bg-danger">Poor</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Milk Transfer:</strong><br>
                            @if($childFollowUp->milk_transfer === 'good')
                                <span class="badge bg-success">Good</span>
                            @elseif($childFollowUp->milk_transfer === 'fair')
                                <span class="badge bg-warning">Fair</span>
                            @elseif($childFollowUp->milk_transfer === 'poor')
                                <span class="badge bg-danger">Poor</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Bottle Feeding:</strong><br>
                            {{ $childFollowUp->bottle_feeding_if_applicable ?: 'Not applicable' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother Nipple Problems:</strong><br>
                            {{ $childFollowUp->mother_nipple_problems ?: 'None reported' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Feeding & Elimination</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Feeding Frequency:</strong><br>
                            {{ $childFollowUp->feeding_frequency ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Feeding Duration:</strong><br>
                            {{ $childFollowUp->feeding_duration ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Feeding Problems:</strong><br>
                            {{ $childFollowUp->feeding_problems ?: 'None reported' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Urinary Output:</strong><br>
                            {{ $childFollowUp->urinary_output ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Stool Output:</strong><br>
                            {{ $childFollowUp->stool_output ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Stool Characteristics:</strong><br>
                            {{ $childFollowUp->stool_characteristics ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Elimination Problems:</strong><br>
                            {{ $childFollowUp->elimination_problems ?: 'None reported' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Neurological Assessment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Responsiveness:</strong><br>
                            @if($childFollowUp->responsiveness === 'good')
                                <span class="badge bg-success">Good</span>
                            @elseif($childFollowUp->responsiveness === 'fair')
                                <span class="badge bg-warning">Fair</span>
                            @elseif($childFollowUp->responsiveness === 'poor')
                                <span class="badge bg-danger">Poor</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Cry Quality:</strong><br>
                            @if($childFollowUp->cry_quality === 'strong')
                                <span class="badge bg-success">Strong</span>
                            @elseif($childFollowUp->cry_quality === 'weak')
                                <span class="badge bg-warning">Weak</span>
                            @elseif($childFollowUp->cry_quality === 'normal')
                                <span class="badge bg-info">Normal</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Muscle Tone:</strong><br>
                            @if($childFollowUp->muscle_tone === 'normal')
                                <span class="badge bg-success">Normal</span>
                            @elseif($childFollowUp->muscle_tone === 'increased')
                                <span class="badge bg-warning">Increased</span>
                            @elseif($childFollowUp->muscle_tone === 'decreased')
                                <span class="badge bg-danger">Decreased</span>
                            @else
                                Not assessed
                            @endif
                        </div>
                        <div class="col-md-12">
                            <strong>Reflex Assessment:</strong><br>
                            {{ $childFollowUp->reflex_assessment ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Immunizations & Screenings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Immunizations Up to Date:</strong><br>
                            {{ $childFollowUp->immunizations_up_to_date ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Newborn Screening Done:</strong><br>
                            {{ $childFollowUp->newborn_screening_done ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Hearing Screening Done:</strong><br>
                            {{ $childFollowUp->hearing_screening_done ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Hearing Screening Results:</strong><br>
                            {{ $childFollowUp->hearing_screening_results ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Immunizations Given:</strong><br>
                            {{ $childFollowUp->immunizations_given ?: 'None recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Immunizations Planned:</strong><br>
                            {{ $childFollowUp->immunizations_planned ?: 'None planned' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Newborn Screening Results:</strong><br>
                            {{ $childFollowUp->newborn_screening_results ?: 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Development & Concerns</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Developmental Milestones:</strong><br>
                            {{ $childFollowUp->developmental_milestones ?: 'Not assessed' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Developmental Concerns:</strong><br>
                            {{ $childFollowUp->developmental_concerns ?: 'None reported' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother Recovery Status:</strong><br>
                            {{ $childFollowUp->mother_recovery_status ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother Emotional Wellbeing:</strong><br>
                            {{ $childFollowUp->mother_emotional_wellbeing ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother Breastfeeding Support:</strong><br>
                            {{ $childFollowUp->mother_breastfeeding_support ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Baby Concerns:</strong><br>
                            {{ $childFollowUp->baby_concerns ?: 'None reported' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Mother Concerns:</strong><br>
                            {{ $childFollowUp->mother_concerns ?: 'None reported' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Complications Identified:</strong><br>
                            {{ $childFollowUp->complications_identified ?: 'None identified' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Counseling & Education</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Counseling Topics Covered:</strong><br>
                            {{ $childFollowUp->counseling_topics ?: 'None recorded' }}
                        </div>
                        <div class="col-md-2">
                            <strong>Infant Care Advice:</strong><br>
                            {{ $childFollowUp->infant_care_advice_given ? 'Given' : 'Not given' }}
                        </div>
                        <div class="col-md-2">
                            <strong>Feeding Guidance:</strong><br>
                            {{ $childFollowUp->feeding_guidance_given ? 'Given' : 'Not given' }}
                        </div>
                        <div class="col-md-2">
                            <strong>Cord Care Advice:</strong><br>
                            {{ $childFollowUp->cord_care_advice_given ? 'Given' : 'Not given' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Hygiene/Safety Advice:</strong><br>
                            {{ $childFollowUp->hygiene_safety_advice_given ? 'Given' : 'Not given' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Danger Signs Explained:</strong><br>
                            {{ $childFollowUp->danger_signs_explained ? 'Explained' : 'Not explained' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Summary & Plan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Clinical Summary:</strong><br>
                            {{ $childFollowUp->clinical_summary ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Referral Reason:</strong><br>
                            {{ $childFollowUp->referral_reason ?: 'Not applicable' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Referral Destination:</strong><br>
                            {{ $childFollowUp->referral_destination ?: 'Not applicable' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Management Plan:</strong><br>
                            {{ $childFollowUp->management_plan ?: 'Not recorded' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Next Follow-up Date:</strong><br>
                            {{ $childFollowUp->next_follow_up_date?->format('M d, Y') ?: 'Not scheduled' }}
                        </div>
                        <div class="col-md-12">
                            <strong>Next Follow-up Reason:</strong><br>
                            {{ $childFollowUp->next_follow_up_reason ?: 'Not specified' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('midwife.child-follow-up.edit', $childFollowUp) }}" class="btn btn-warning">Edit Follow-up</a>
                        <a href="{{ route('midwife.newborn.show', $childFollowUp->newborn) }}" class="btn btn-info">View Newborn</a>
                        <a href="{{ route('midwife.child-follow-up.index', $childFollowUp->newborn) }}" class="btn btn-outline-secondary">Back to Follow-ups</a>
                    </div>
                </div>
            </div>

            @if($childFollowUp->health_status === 'needs_referral' || $childFollowUp->health_status === 'referred')
            <div class="card mt-3">
                <div class="card-header bg-danger">
                    <h5 class="mb-0 text-white">⚠️ Referral Required</h5>
                </div>
                <div class="card-body">
                    <p>This child requires referral for further evaluation.</p>
                    @if($childFollowUp->referral_destination)
                        <strong>Referral Destination:</strong> {{ $childFollowUp->referral_destination }}
                    @endif
                </div>
            </div>
            @elseif($childFollowUp->health_status === 'at_risk')
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white">⚠️ At Risk</h5>
                </div>
                <div class="card-body">
                    <p>This child is at risk and requires close monitoring.</p>
                </div>
            </div>
            @endif

            @if($childFollowUp->needsPhototherapy())
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white">🍼 Jaundice Treatment Needed</h5>
                </div>
                <div class="card-body">
                    <p>This child may need phototherapy for jaundice treatment.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection