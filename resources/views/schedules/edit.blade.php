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
        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Schedules</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('schedules.update', ['employee_schedule' => $employeeSchedule]) }}" method="POST">
    @csrf @method('PUT')
    @section('plugins.TempusDominusBs4', true)
    <input type="hidden" name="employee_id" value="{{ $employeeSchedule->employee_id }}">
    <x-adminlte-input-date name="month_and_year" label="Schedule " :config="$dateConfig" placeholder="Choose month and year for this schedule" value="{{ date_format($employeeSchedule->month_and_year, 'Y-m') }}" enable-old-support>
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="workdays" label="Work Days" type="text" id="workdays" value="{{ $employeeSchedule->workdays }}" placeholder="Work Days" enable-old-support/>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection