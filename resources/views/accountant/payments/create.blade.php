@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="h3 mb-4">Record New Payment</h1>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('accountant.payments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select id="patient_id" name="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required onchange="updateOutstandingBills()">
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id || ($selectedBill && $selectedBill->patient_id == $patient->id))>
                                        {{ $patient->name }} ({{ $patient->hospital_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bill_id" class="form-label">Bill (Optional)</label>
                            <select id="bill_id" name="bill_id" class="form-control @error('bill_id') is-invalid @enderror">
                                <option value="">-- Select Outstanding Bill --</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}" @selected(old('bill_id') == $bill->id || ($selectedBill && $selectedBill->id == $bill->id))
                                        data-amount="{{ $bill->balance }}"
                                        data-bill-number="{{ $bill->bill_number }}">
                                        {{ $bill->bill_number }} - {{ $bill->patient->name }} (Balance: {{ number_format($bill->balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select a bill to associate this payment with</small>
                            @if($selectedBill)
                                <div class="alert alert-info mt-2 small mb-0">
                                    <strong>{{ $selectedBill->bill_number }}</strong> - Balance Due: <strong>{{ number_format($selectedBill->balance, 2) }}</strong>
                                </div>
                            @endif
                            @error('bill_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" step="0.01" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select id="payment_method" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required onchange="toggleInsuranceProvider()">
                                <option value="">-- Select Payment Method --</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}" @selected(old('payment_method') == $method)>{{ $method }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="insurance_provider_div" style="display: none;">
                            <label for="insurance_provider" class="form-label">Insurance Provider</label>
                            <input type="text" id="insurance_provider" name="insurance_provider" class="form-control @error('insurance_provider') is-invalid @enderror" placeholder="e.g., NHIS, Allianz, etc." value="{{ old('insurance_provider') }}">
                            <small class="text-muted">Name of the insurance provider</small>
                            @error('insurance_provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" id="reference_number" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" placeholder="e.g., Receipt #, Check #, Transaction ID" value="{{ old('reference_number') }}">
                            <small class="text-muted">Transaction reference or receipt number</small>
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" id="payment_date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Record Payment
                            </button>
                            <a href="{{ route('accountant.payments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleInsuranceProvider() {
        const method = document.getElementById('payment_method').value;
        const insuranceDiv = document.getElementById('insurance_provider_div');
        
        if (method === 'NHIS' || method === 'Private Insurance') {
            insuranceDiv.style.display = 'block';
        } else {
            insuranceDiv.style.display = 'none';
        }
    }

    function updateBillAmount() {
        const billSelect = document.getElementById('bill_id');
        const selectedOption = billSelect.options[billSelect.selectedIndex];
        const amount = parseFloat(selectedOption.dataset.amount);
        
        if (!isNaN(amount) && amount > 0) {
            document.getElementById('amount').value = amount.toFixed(2);
        } else {
            document.getElementById('amount').value = '';
        }
    }

    function updateOutstandingBills() {
        // This could be enhanced with AJAX to fetch bills for selected patient
        const patientId = document.getElementById('patient_id').value;
        // Placeholder for future AJAX implementation
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleInsuranceProvider();
        
        // Add event listener for bill selection changes
        const billSelect = document.getElementById('bill_id');
        billSelect.addEventListener('change', updateBillAmount);
        
        // Initialize amount if a bill is already selected (from query parameter)
        if (billSelect.value) {
            updateBillAmount();
        }
    });
</script>
@endsection
