@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">{{ $title }}</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Training</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <div class="row">
      <div class="col-9">
        <dt>Month and Year</dt>
        <dd>{{ date_format($schedule->month_and_year, 'F Y') }}</dd>
      </div>
      <div class="col-3 d-flex justify-content-end align-items-center">
        <a href="{{ route('schedules.show-by-employee', ['employee' => $schedule->employee]) }}" class="btn bg-teal">Go back</a>
      </div>
    </div>
    <dt>Workdays</dt>
    <dd>{{ $schedule->workdays }}</dd>
    <dt>Employee Name</dt>
    <dd>{{ $schedule->employee->name }}</dd>
  </dl>
</x-adminlte-card>
@endsection