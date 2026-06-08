@section('title', 'result entry')
<div class="space-y-12">

<flux:card>

    <flux:heading size="lg">

        Laboratory Result Entry

    </flux:heading>

    <div class="mt-4">

        <flux:input
            wire:model.live.debounce.700ms="bill_number"
            label="Bill Number"
            placeholder="Enter Bill Number"
        />

    </div>

</flux:card>

<div wire:loading wire:target="bill_number">

    <flux:card>

        Pls holdon, we are searching investigation request for you...

    </flux:card>

</div>

    
@if($bill)
<flux:card>
<div class="grid grid-cols-4 gap-4">

    <div>

        <strong>Bill Number</strong>

        <div>{{ $bill->bill_number }}</div>

    </div>

    <div>

        <strong>Total Investigations</strong>

        <div>{{ count($requests) }}</div>

    </div>

    <div>

        <strong>Patient</strong>

        @if($patient)


            <div class="grid grid-cols-1 gap-4 mt-4">

                <div>
                    <strong>Name:</strong>
                    {{ $patient->name() }}
                </div>

                <div>
                    <strong>Gender:</strong>
                    {{ $patient->demographic->gender }}
                </div>

                <div>
                    <strong>Phone Number:</strong>
                    {{ $patient->demographic->phone_number }}
                </div>

            </div>
        @elseif($walkin)
        
            <div class="grid grid-cols-1 gap-4 mt-4">

                <div>
                    <strong>Name:</strong>
                    {{ $walkin->name }}
                </div>

                <div>
                    <strong>Phone:</strong>
                    {{ $walkin->phone_number }}
                </div>

                <div>
                    <strong>Address:</strong>
                    {{ $walkin->address }}
                </div>

            </div>

        @endif

    </div>

    <div>
        @if($loaded)
        <a href="{{ route('lab.requests.results.show', $bill) }}">

            <flux:button variant="primary">

                Print Report

            </flux:button>

        </a>
        @endif
    </div>

</div>

</flux:card>
@endif


@if($loaded && count($requests))
@foreach($requests as $request)
    <flux:card class="mb-4">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div>
                <strong>Lab No</strong>
                <div>
                    <flux:input
                        label="Laboratory Number"
                        wire:model="labNumbers.{{ $request->id }}"
                        placeholder="Enter Lab Number" />
                </div>
            </div>

            <div>
                <strong>Specimen</strong>
                <div>{{ $request->specimen }}</div>
            </div>

            <div>
                <strong>Status</strong>
                <div>{{ $request->status }}</div>
            </div>

            <div>
                <strong>Requested Date</strong>
                <div>
                    {{ optional($request->requested_at)->format('d M Y') }}
                </div>
            </div>

        </div>

        <div class="mt-3 mb-4">

            <strong>Clinical Diagnosis</strong>

            <div class="text-gray-600">

                {{ $request->clinical_diagnoses }}

            </div>

        </div>

        <hr>

            <div class="flex justify-between items-center mt-4">

                <div>

                    <h4 class="font-bold">

                        {{ $request->investigation->name }}

                    </h4>

                    <p class="text-sm text-gray-500">

                        @if($request->status == 'Completed')

                        <span class="badge bg-success">
                            Completed
                        </span>

                        @elseif($request->status == 'Pending')

                        <span class="badge bg-warning">
                            Pending
                        </span>

                        @else

                        <span class="badge bg-secondary">
                            {{ $request->status }}
                        </span>

                        @endif
                    </p>
                    <p class="text-sm text-gray-500">
                        Requested on:
                        {{ $request->created_at->format('M d, Y h:i A') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Requested by:
                        {{ $request->requestedBy->name ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Performed by:
                        {{ $request->performedBy->name ?? 'N/A' }}
                    </p>
                    

                </div>

                <flux:button
                    variant="primary"
                    wire:click="saveInvestigation({{ $request->id }})">

                    Save Result

                </flux:button>

            </div>

            <div class="mt-4">

                <table class="table-auto w-full">

                    <thead>

                        <tr>

                            <th class="text-left p-2">
                                Parameter
                            </th>

                            <th class="text-left p-2">
                                Unit
                            </th>

                            <th class="text-left p-2">
                                Reference
                            </th>

                            <th class="text-left p-2">
                                Value
                            </th>

                            <th class="text-left p-2">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach(
                        $request->investigation->parameters
                        as $parameter
                    )

                        <tr>

                            <td class="p-2">

                                {{ $parameter->name }}

                            </td>

                            <td class="p-2">

                                {{ $parameter->unit }}

                            </td>

                            <td class="p-2">

                                {{ $parameter->reference_range }}

                            </td>

                            <td class="p-2">

                                <flux:input wire:model.live="results.{{ $request->id }}.{{ $parameter->id }}"/>

                            </td>



                            <td class="p-2">

                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="
                                    deleteResult(
                                    {{ $request->id }},
                                    {{ $parameter->id }}
                                    )">

                                    Delete

                                </flux:button>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </flux:card>

    @endforeach

@endif

@if($loaded && count($requests) == 0)

    <flux:card>

        No investigation found for this Lab Number.

    </flux:card>

@endif


</div>
