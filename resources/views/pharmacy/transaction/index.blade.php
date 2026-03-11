@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4 class="mb-4">
        <i class="bi bi-receipt"></i> Stock Transactions
        </h4>

        <a href="{{ route('pharmacy.transactions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Transaction
        </a>
    </div>

    <div class="card shadow-sm p-4">

        <div class="table-responsive">

            <table class="table table-striped datatable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicines</th>
                        <th>Total Ammount</th>
                        <th>Reference</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach(App\Models\StockTransaction::latest()->get() as $transaction)

                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @foreach($transaction->stockTransactionItems as $item)
                                {{$item->medicineBatch->medicine->name}},
                            @endforeach
                        </td>
                        <td>{{ $transaction->total_amount }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ $transaction->createdBy->name }}</td>
                        
                        <td>
                        <a href="#" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                        </a>

                        <a href="#" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                        </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection