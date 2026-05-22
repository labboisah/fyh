<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Department Billing Summary</h5>
        <p class="text-muted small mb-0">Each department totals its services and expands to service-level breakdown.</p>
    </div>
    <div class="card-body">
        @if(!empty($data['summary']) && $data['summary']->count())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th class="text-end">Total Billed</th>
                            <th class="text-end">Service Count</th>
                            <th class="text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['summary'] as $index => $department)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->department_name }}</td>
                                <td class="text-end">{{ number_format($department->total_amount, 2) }}</td>
                                <td class="text-end">{{ $department->service_count }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#department-details-{{ $index }}" aria-expanded="false" aria-controls="department-details-{{ $index }}">
                                        Show services
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="department-details-{{ $index }}">
                                <td colspan="5">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Service</th>
                                                    <th class="text-end">Quantity</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($department->services as $service)
                                                    <tr>
                                                        <td>{{ $service->service_name }}</td>
                                                        <td class="text-end">{{ $service->total_quantity }}</td>
                                                        <td class="text-end">{{ number_format($service->service_total, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-secondary mb-0">No department billing data found for the selected period.</div>
        @endif
    </div>
</div>
