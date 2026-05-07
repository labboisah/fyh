@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-4">
                System Update
            </h3>

            <div class="mb-3">
                <strong>Offline Version:</strong>
                <code>{{ $local }}</code>
            </div>

            <div class="mb-4">
                <strong>Online Version:</strong>
                <code>{{ $remote }}</code>
            </div>

            @if($hasUpdate)

                <div class="alert alert-warning">
                    New update available
                </div>

                <form method="POST" action="{{ route('admin.system.update.run') }}">
                    @csrf

                    <button class="btn btn-dark rounded-pill px-4">
                        Update System
                    </button>
                </form>

            @else

                <div class="alert alert-success">
                    System is up to date
                </div>

            @endif

        </div>

    </div>

</div>

@endsection