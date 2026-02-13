@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="h3 mb-4">Create New Bill</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('accountant.bills.store') }}" method="POST" id="billForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                 <div class="mb-3">
                            <input type="hidden" name="patient_visit_id" value="{{ $patient->currentVisit()->id }}">
                            @if($patient->demographic)
                                <div class="alert alert-info mt-2 small mb-0">
                                    <strong>{{ $patient->demographic->full_name }}</strong> - Hospital #: <strong>{{ $patient->hospital_number }}</strong>
                                </div>
                            @endif

                                                         
                        </div>
                        
                        {{-- Services Selection --}}
                        <div class="mb-4">
                            <label class="form-label mb-3">Select Services <span class="text-danger">*</span></label>

                            <div id="services-container">
                                <div class="service-row mb-3 row g-2" data-service-index="0">
                                    <div class="col-md-8">
                                        <select class="form-control service-select @error('services.0.id') is-invalid @enderror" name="services[0][id]" required>
                                            <option value="">-- Select Service --</option>
                                            @foreach($services as $category => $categoryServices)
                                                <optgroup label="{{ $category }}">
                                                    @foreach($categoryServices as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                            {{ $service->name }} - <span class="fas fa-naira-sign"></span> {{ number_format($service->price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('services.0.id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-danger btn-sm remove-service" onclick="this.closest('.service-row').remove(); calculateTotal();" style="display: none;">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-service-btn" onclick="addServiceRow()">
                                <i class="bi bi-plus-circle"></i> Service
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="issued_date" class="form-label">Issued Date <span class="text-danger">*</span></label>
                                    <input type="date" id="issued_date" name="issued_date" class="form-control @error('issued_date') is-invalid @enderror" value="{{ old('issued_date', date('Y-m-d')) }}" required>
                                    @error('issued_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" id="due_date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                <i class="bi bi-check-circle"></i> Create Bill
                            </button>
                            <a href="{{ route('accountant.bills.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                            </div>
                            <div class="col-md-5">
                                 {{-- Service Summary Table --}}
                                <div id="summary-section" style="display: none;" class="mb-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">Service Summary</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Service</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summary-tbody">
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="fw-bold border-top">
                                                            <td>Total Amount Due:</td>
                                                            <td class="text-end fs-5 text-success">
                                                                <span class="fas fa-naira-sign"></span> <span id="total-amount">0.00</span>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let serviceIndex = 0;

    function addServiceRow() {
        serviceIndex++;
        const html = `
            <div class="service-row mb-3 row g-2" data-service-index="${serviceIndex}">
                <div class="col-md-8">
                    <select class="form-control service-select" name="services[${serviceIndex}][id]" required onchange="calculateTotal()">
                        <option value="">-- Select Service --</option>
                        @foreach($services as $category => $categoryServices)
                            <optgroup label="{{ $category }}">
                                @foreach($categoryServices as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                        {{ $service->name }} - <span class="fas fa-naira-sign"></span> {{ number_format($service->price, 2) }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger btn-sm remove-service" onclick="this.closest('.service-row').remove(); calculateTotal();">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('services-container').insertAdjacentHTML('beforeend', html);
        calculateTotal();
    }

    function calculateTotal() {
        const rows = document.querySelectorAll('.service-row');
        let totalAmount = 0;
        let summaryHtml = '';
        let hasServices = false;

        rows.forEach(row => {
            const select = row.querySelector('.service-select');
            
            if (select.value) {
                hasServices = true;
                const option = select.options[select.selectedIndex];
                const price = parseFloat(option.dataset.price);
                totalAmount += price;

                summaryHtml += `
                    <tr>
                        <td>${option.text.split(' - ')[0]}</td>
                        <td class="text-end fw-bold"><span class="fas fa-naira-sign"></span> ${price.toFixed(2)}</td>
                    </tr>
                `;
            }
        });

        document.getElementById('summary-tbody').innerHTML = summaryHtml;
        document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
        
        const summarySection = document.getElementById('summary-section');
        const submitBtn = document.getElementById('submit-btn');
        
        if (hasServices) {
            summarySection.style.display = 'block';
            submitBtn.disabled = false;
        } else {
            summarySection.style.display = 'none';
            submitBtn.disabled = true;
        }

        // Update remove buttons visibility
        const removeButtons = document.querySelectorAll('.remove-service');
        if (removeButtons.length > 0) {
            removeButtons.forEach((btn, idx) => {
                btn.style.display = (removeButtons.length > 1) ? 'block' : 'none';
            });
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
        
        // Add event listeners to initial select
        document.querySelector('.service-select').addEventListener('change', calculateTotal);
    });
</script>
@endsection
