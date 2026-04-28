@extends('layouts.app')

@section('title', 'Newborn Examination Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3"><i class="bi bi-eye"></i> Newborn Examination Details</h1>
            <p class="text-muted">Newborn: {{ $newbornExamination->newborn->newborn_registration_number }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Examination Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>Examination Date/Time:</strong><br>{{ optional($newbornExamination->examination_date_time)->format('M d, Y H:i') }}</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Hours After Birth:</strong><br>{{ $newbornExamination->hours_after_birth }} hours</p>
                </div>
                <div class="col-md-3">
                    <p><strong>Status:</strong><br>
                        @if($newbornExamination->exam_status === 'normal')
                            <span class="badge bg-success">Normal</span>
                        @elseif($newbornExamination->exam_status === 'needs_follow_up')
                            <span class="badge bg-warning">Needs Follow-up</span>
                        @else
                            <span class="badge bg-danger">Referral Needed</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3">
                    <p><strong>Recorded By:</strong><br>{{ $newbornExamination->recordedBy->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Vital Signs & Measurements</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <p><strong>Temperature:</strong><br>{{ $newbornExamination->temperature ?? 'N/A' }} °C</p>
                </div>
                <div class="col-md-2">
                    <p><strong>Heart Rate:</strong><br>{{ $newbornExamination->heart_rate ?? 'N/A' }} bpm</p>
                </div>
                <div class="col-md-2">
                    <p><strong>Respiratory Rate:</strong><br>{{ $newbornExamination->respiratory_rate ?? 'N/A' }}</p>
                </div>
                <div class="col-md-2">
                    <p><strong>Weight:</strong><br>{{ $newbornExamination->weight ?? 'N/A' }} g</p>
                </div>
                <div class="col-md-2">
                    <p><strong>Length:</strong><br>{{ $newbornExamination->length ?? 'N/A' }} cm</p>
                </div>
                <div class="col-md-2">
                    <p><strong>Head Circumference:</strong><br>{{ $newbornExamination->head_circumference ?? 'N/A' }} cm</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Chest Circumference:</strong><br>{{ $newbornExamination->chest_circumference ?? 'N/A' }} cm</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Physical Examination</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>General Appearance:</strong><br>{{ $newbornExamination->general_appearance ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Skin:</strong><br>{{ $newbornExamination->skin_examination ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Head & Neck:</strong><br>{{ $newbornExamination->head_and_neck ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Eyes:</strong><br>{{ $newbornExamination->eyes_examination ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Ears:</strong><br>{{ $newbornExamination->ear_examination ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Mouth & Throat:</strong><br>{{ $newbornExamination->mouth_and_throat ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Heart Sounds:</strong><br>{{ $newbornExamination->heart_sounds ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Breath Sounds:</strong><br>{{ $newbornExamination->breath_sounds ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Abdomen:</strong><br>{{ $newbornExamination->abdomen_shape ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Genitalia:</strong><br>{{ $newbornExamination->genitalia_examination ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Reflex Assessment:</strong><br>{{ $newbornExamination->reflex_assessment ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Muscle Tone:</strong><br>{{ $newbornExamination->muscle_tone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Hip Examination:</strong><br>{{ $newbornExamination->hip_examination ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Feeding & Special Conditions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Feeding Type:</strong><br>{{ $newbornExamination->feeding_type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Feeding Tolerance:</strong><br>{{ $newbornExamination->feeding_tolerance ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Jaundice Present:</strong><br>{{ $newbornExamination->jaundice_present ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Jaundice Level:</strong><br>{{ $newbornExamination->jaundice_level ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Jaundice Management:</strong><br>{{ $newbornExamination->jaundice_management ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Clinical Summary & Follow-up</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Abnormal Findings:</strong><br>{{ $newbornExamination->abnormal_findings ?? 'None' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Clinical Summary:</strong><br>{{ $newbornExamination->clinical_summary ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Follow-up Plans:</strong><br>{{ $newbornExamination->follow_up_plans ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Next Follow-up:</strong><br>{{ optional($newbornExamination->next_follow_up_date)->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('midwife.newborn-examination.edit', $newbornExamination) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('midwife.newborn-examination.destroy', $newbornExamination) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
        <a href="{{ route('midwife.newborn-examination.index', $newbornExamination->newborn) }}" class="btn btn-outline-secondary btn-sm">Back to Examinations</a>
    </div>
</div>
@endsection