@extends('layouts.app')

@section('content')
<h4>
<i class="bi bi-boxes"></i>
Consumable Stock
</h4>

<table class="table table-striped">

<tr>
<th>Consumable</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Date</th>
</tr>

@foreach($stocks as $stock)

<tr>

<td>{{ $stock->consumable->name }}</td>

<td>{{ $stock->quantity }}</td>

<td>{{ $stock->unit_price }}</td>

<td>{{ $stock->purchase_date }}</td>

</tr>

@endforeach

</table>
@endsection