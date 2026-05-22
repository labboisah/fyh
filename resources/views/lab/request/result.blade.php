@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-clipboard2-data me-2 text-primary"></i>
            Combined Investigation Results
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

        .print-content {
            position: relative;
            z-index: 2;
        }

        @media print {
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

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            p,
            li {
                orphans: 3;
                widows: 3;
            }

            tr {
                page-break-inside: avoid !important;
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
        <div class="watermark-logo"></div>

        <div class="print-content p-4">
            <div class="text-center mb-4">
                <h2 class="text-success fw-bold" style="transform: scaleY(1.3);">
                    FATIMA YAHAYA HOSPITAL, SIFAWA
                </h2>
                <h4>DEPARTMENT OF {{ strtoupper(auth()->user()->department->name) }}</h4>
                <h6 class="text-danger">
                    <em>No 5, Birnin Kebbi Road Sifawa, Bodinga LG, Sokoto State</em>
                </h6>
            </div>

            <hr>

            <div class="p-3 mb-4">
                <p class="mb-1 text-muted">Patient Name: <strong>{{ $patientName }}</strong></p>
                <p class="mb-1 text-muted">
                    Hospital Number:
                    @if($hospitalNumber)
                        <strong>{{ $hospitalNumber }}</strong>
                    @else
                        <strong>Walk-in Patient</strong>
                    @endif
                </p>
                <p class="mb-1 text-muted">
                    Requested At: <strong>{{ $investigationRequests->first()->created_at->format('d M, Y @ h:i A') }}</strong>
                </p>
                <p class="mb-1 text-muted">
                    Requested By: <strong>{{ $investigationRequests->first()->requestedBy->name ?? 'N/A' }}</strong>
                </p>
                <p class="mb-1 text-muted">
                    Performed By: <strong>{{ $investigationRequests->first()->performedBy->name ?? 'N/A' }}</strong>
                </p>
            </div>

            <hr>

            @foreach($investigationRequests as $investigationRequest)
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">{{ $investigationRequest->investigation->name }}</h5>
                    @if($investigationRequest->investigationResults->isEmpty())
                        <div class="alert alert-warning">No results recorded yet.</div>
                    @else
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
                                        <td>{{ $result->parameter->name ?? 'Parameter' }}</td>
                                        <td>{{ $result->value }}</td>
                                        <td>{{ $result->parameter->reference_range }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Print Combined Results
        </button>
        <a href="{{ route('lab.requests.index') }}" class="btn btn-secondary ms-2">Back to Requests</a>
    </div>
@endsection
