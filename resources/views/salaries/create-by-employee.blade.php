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
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.TempusDominusBs4', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('salaries.check-workdays') }}">
    <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->id }}">
    <x-adminlte-input-date id="month_and_year" name="month_and_year" label="For Month of Year " :config="$dateConfig" placeholder="Choose month and year for this salary roll" enable-old-support required>
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-button type="submit" label="Next" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection