@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">You are logged in!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <x-adminlte-info-box title="My Total Training Hour" text="{{ $training_hour }}" icon="fas fa-lg fa-dumbbell text-dark" theme="gradient-teal"/>
        </div>
        @can('view-all-employees')
        <div class="col-4">
            <x-adminlte-info-box title="Total Employee" text="{{ $employee_count }}" icon="fas fa-lg fa-users text-dark" theme="gradient-teal"/>
        </div>
        @else
        @can('view-employees')
        <div class="col-4">
            <x-adminlte-info-box title="Total Employee in Department" text="{{ $department_employee_count }}" icon="fas fa-lg fa-eye text-dark" theme="gradient-teal"/>
        </div>
        @endcan
        @endcan
    </div>
@stop
