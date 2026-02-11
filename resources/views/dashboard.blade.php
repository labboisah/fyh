@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if(auth()->user()->hasRole('record_officer'))
        @include('record_officer.dashboard')
    @elseif(auth()->user()->hasRole('doctor'))
        @include('doctor.dashboard')
    @elseif(auth()->user()->hasRole('nurse'))
        @include('nurse.dashboard')
    @elseif(auth()->user()->hasRole('pharmacist'))
        @include('pharmacist.dashboard')
    @elseif(auth()->user()->hasRole('lab_technician'))
        @include('lab_technician.dashboard')
    @elseif(auth()->user()->hasRole('accountant'))
        @include('accountant.dashboard')
    @elseif(auth()->user()->hasRole('administrator'))
        @include('admin.dashboard')
    @endif   

@endsection

