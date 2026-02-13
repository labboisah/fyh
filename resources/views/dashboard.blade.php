@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if(auth()->user()->hasRole('record_officer'))
        @include('record_officer.dashboard')
    @endif

    @if(auth()->user()->hasRole('doctor'))
        @include('doctor.dashboard')
    @endif

    @if(auth()->user()->hasRole('nurse'))
        @include('nurse.dashboard')
    @endif

    @if(auth()->user()->hasRole('pharmacist'))
        @include('pharmacist.dashboard')
    @endif

    @if(auth()->user()->hasRole('lab_technician'))
        @include('lab.dashboard')
    @endif

    @if(Auth::user()->hasRole('accountant'))
        @include('accountant.dashboard')
    @endif

    @if(auth()->user()->hasRole('administrator'))
        @include('admin.dashboard')
    @endif   

@endsection

