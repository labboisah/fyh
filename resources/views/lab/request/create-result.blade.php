@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 d-flex align-items-center mb-0">
            <i class="bi bi-eyedropper me-2 text-primary"></i>
            Send Investigation Result
        </h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <form action="{{ route('lab.requests.storeResult', $investigationRequest) }}" method="POST">
                    @csrf
                    @foreach($investigationRequest->investigation->parameters as $parameter)
                    <div class="mb-3">
                        <label for="parameter_{{ $parameter->id }}" class="form-label">{{ $parameter->name }}</label>
                        <input type="text" name="parameters[{{ $parameter->id }}]" id="parameter_{{ $parameter->id }}" placeholder="Enter result for {{ $parameter->name }} in {{$parameter->unit}}" class="form-control" required>
                    </div>
                    @endforeach
                    

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i> Submit Result</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection