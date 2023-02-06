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
        <li class="breadcrumb-item"><a href="{{ route('salaries.index') }}">Salaries</a></li>
        <li class="breadcrumb-item"><a href="{{ route('salaries.create-by-employee', ['employee' => $employee]) }}">Create Salaries</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.TempusDominusBs4', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('salaries.store') }}" method="POST">
    @csrf
    <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->id }}">
    <x-adminlte-input-date id="month_and_year" name="month_and_year" label="For Month of Year " :config="$dateConfig" placeholder="Choose month and year for this salary roll" value="{{ date_format($month_and_year, 'Y-m') }}" enable-old-support>
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    @isset($schedule)
    <dt>Workdays based on schedules: {{ $schedule->workdays }}</dt>
    @endisset
    <x-adminlte-input name="workdays" label="Work Days (Please edit if not the same as actual workdays)" type="number" id="workdays" placeholder="Work Days" value="{{ $workdays }}" enable-old-support/>
    <x-adminlte-input name="service" label="Service" type="number" id="service" placeholder="Service" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-input name="cug_cut" label="Potongan Closed User Group (Telkomsel)" type="number" id="cug_cut" placeholder="Potongan Closed User Group (Telkomsel)" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-input name="thr" label="THR One Year" type="number" id="thr" placeholder="THR One Year" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <div class="row d-flex justify-content-between">
      <a href="{{ route('salaries.create-by-employee', ['employee' => $employee]) }}" class="btn btn-danger">Go Back</a>
      <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
    </div>
  </form>
</x-adminlte-card>
@endsection