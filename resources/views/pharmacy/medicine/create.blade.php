@extends('layouts.app')

@section('content')

<div class="container">

<h4 class="mb-4">
<i class="bi bi-capsule"></i> Add Medicine
</h4>

<form method="POST" action="{{ route('pharmacy.medicines.store') }}">

@csrf

<div class="row">

<div class="col-md-6">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control">
</div>

</div>

<div class="col-md-6">

<div class="mb-3">
<label>Generic Name</label>
<input type="text" name="generic_name" class="form-control">
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Form</label>
<select name="form" class="form-control">
<option>Tablet</option>
<option>Capsule</option>
<option>Syrup</option>
<option>Injection</option>
<option>Cream</option>
</select>
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Strength</label>
<input type="text" name="strength" class="form-control">
</div>

</div>

<div class="col-md-4">

<div class="mb-3">
<label>Manufacturer</label>
<input type="text" name="manufacturer" class="form-control">
</div>

</div>

</div>

<button class="btn btn-success">
<i class="bi bi-check-circle"></i> Save
</button>

</form>

</div>

@endsection