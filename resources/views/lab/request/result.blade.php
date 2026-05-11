@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Print Investigation Result
    </h1>
    
</div>
@endsection
@section('content')
    <div id="print" class="p-4">
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="{{asset('images/logo.png')}}" width="100" height="100" alt="">
            </div>
            <div class="col-md-12">
                <h2 class="text text-center text-success" style="transform: scaley(1.5);">FATIMA YAHAYA HOSPITAL, SIFAWA</h2>
                <h4 class="text text-center" style="transform: scaley(1);">DEPARTMENT OF {{strtoupper(auth()->user()->department->name)}}</h4>
                <h6 class="text text-center text-danger"><em>No 5, Birnin Kebbi Road Sifawa, Bodinga LG, Sokoto state</em></h6>
            </div>
        </div>
        <hr>
        <div class="p-4">
            <p class="mb-0 text-muted">
                Patient Name:
                <strong class="">{{ $investigationRequest->patientVisit->patient->demographic->full_name ?? 'Patient Details'}}</strong>
            </p>

            <p class="mb-0 text-muted">
                Hospital Number:
                @if($investigationRequest->patientVisit)
                <strong class="">{{ $investigationRequest->patientVisit->patient->hospital_number }}</strong>
                @else
                <strong class="">Walkin Patient</strong>
                @endif
            </p>
            
            <p class="mb-0 text-muted">
            Requested At:
            <strong class="">{{ date('M d, Y',strtotime($investigationRequest->created_at))}} @ {{ date('h:s A',strtotime($investigationRequest->created_at))}}</strong>
            </p>
            <p class="mb-0 text-muted">
            Requested By:
            <strong class="">{{ $investigationRequest->requestedBy->name ?? ''}}</strong>
            </p>
        </div>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Value</th>
                    <th>Reference Range</th>                    
                </tr>
            </thead>
            <tbody>
                @foreach($investigationRequest->investigationResults as $result)
                <tr>
                    <td>{{$result->parameter->name}}</td>
                    <td>{{$result->value}}</td>
                    <td>{{$result->parameter->reference_range}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a onclick="printDiv('print');" class="btn btn-primary">Print</a>
<script>
    function printDiv(divId) {
        const divContent = document.getElementById(divId).innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = divContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>
@endsection            