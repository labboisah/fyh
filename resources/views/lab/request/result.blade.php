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
    <style>

    #print {
        position: relative;
        overflow: hidden;
        background: white;
    }

    /* Watermark Logo */
    .watermark-logo {

        position: absolute;

        top: 50%;
        left: 50%;

        transform: translate(-50%, -50%);

        opacity: 0.08;

        z-index: 0;

        width: 350px;
        height: 350px;

        background-image: url('{{ asset("images/logo.png") }}');

        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;

        pointer-events: none;

    }

    /* Ensure content stays above watermark */
    .print-content {

        position: relative;
        z-index: 2;

    }
    /* =========================
   Prevent Page Breaking
========================= */

@media print {

    /* Avoid breaking inside elements */

    table,
    tr,
    td,
    th,
    thead,
    tbody,
    tfoot,
    img,
    .card,
    .row,
    .col,
    .section,
    .print-section,
    .result-section {

        page-break-inside: avoid !important;
        break-inside: avoid !important;

    }

    /* Keep table headers together */

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    /* Prevent headings from separating */

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {

        page-break-after: avoid !important;
        break-after: avoid !important;

    }

    /* Prevent orphan lines */

    p,
    li {

        orphans: 3;
        widows: 3;

    }

    /* Avoid splitting rows */

    tr {

        page-break-inside: avoid !important;

    }

    /* Optional:
       Force next section to new page */

    .page-break {

        page-break-before: always;

    }

    /* Optional:
       Prevent page break after section */

    .no-break-after {

        page-break-after: avoid;

    }


        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        #print,
        #print * {
            visibility: visible;
        }

        #print {

            position: absolute;
            left: 0;
            top: 0;

            width: 100%;

            background: white;

        }

        .watermark-logo {

            opacity: 0.08 !important;

        }

    }

</style>

<div id="print" style="avoid">

    <!-- Watermark -->
    <div class="watermark-logo"></div>

    <!-- Main Content -->
    <div class="print-content p-4">
        @for($i=1; $i<=2; $i++)
        <!-- Header -->
        <div class="text-center mb-4">

            <h2 class="text-success fw-bold"
                style="transform: scaleY(1.3);">

                FATIMA YAHAYA HOSPITAL, SIFAWA

            </h2>

            <h4>

                DEPARTMENT OF
                {{ strtoupper(auth()->user()->department->name) }}

            </h4>

            <h6 class="text-danger">

                <em>
                    No 5, Birnin Kebbi Road Sifawa,
                    Bodinga LG, Sokoto State
                </em>

            </h6>

        </div>

        <hr>

        <!-- Patient Information -->
        <div class="p-3">

            <p class="mb-1 text-muted">

                Patient Name:

                <strong>

                    {{ $investigationRequest->patientVisit->patient->demographic->full_name ?? strtoupper($investigationRequest->walkinPatient->name) }}

                </strong>

            </p>

            <p class="mb-1 text-muted">

                Hospital Number:

                @if($investigationRequest->patientVisit)

                    <strong>

                        {{ $investigationRequest->patientVisit->patient->hospital_number }}

                    </strong>

                @else

                    <strong>
                        Walk-in Patient
                    </strong>

                @endif

            </p>

            <p class="mb-1 text-muted">

                Requested At:

                <strong>

                    {{ date('M d, Y', strtotime($investigationRequest->created_at)) }}

                    @

                    {{ date('h:i A', strtotime($investigationRequest->created_at)) }}

                </strong>

            </p>

            <p class="mb-1 text-muted">

                Requested By:

                <strong>

                    {{ $investigationRequest->requestedBy->name ?? '' }}

                </strong>

            </p>
            <p class="mb-1 text-muted">

                Performed By:

                <strong>

                    {{ $investigationRequest->performedBy->name ?? '' }}

                </strong>

            </p>

        </div>

        <hr>

        <!-- Results Table -->
        <table class="table table-bordered table-sm">

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

                        <td>
                            {{ $result->parameter->name }}
                        </td>

                        <td>
                            {{ $result->value }}
                        </td>

                        <td>
                            {{ $result->parameter->reference_range }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>
    @endfor
</div>

<button onclick="window.print()"
        class="btn btn-primary">

    Print Result

</button>
@endsection            