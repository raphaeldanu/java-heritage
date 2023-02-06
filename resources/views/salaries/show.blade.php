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

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <div class="row">
      <div class="col-9">
        <dt>Employee Name</dt>
        <dd>{{ $salary->employee->name }}</dd>
      </div>
      <div class="col-3 d-flex justify-content-end align-items-center">
        <a href="{{ route('salaries.show-by-employee', ['employee' => $salary->employee]) }}" class="btn bg-teal">Go back</a>
      </div>
    </div>
    <dt>Date</dt>
    <dd>{{ date_format($salary->month_and_year, 'F Y') }}</dd>
    <dt>Actual Workdays</dt>
    <dd>{{ $salary->actual_workdays }}</dd>
    <dt>Basic Salary</dt>
    <dd>Rp {{ number_format($salary->employee->salaryRange->base_salary, 2, ',', '.') }}</dd>
    <div class="row">
      <div class="col-2">
        <dt>Actual Salary</dt>
        <dd>Rp {{ number_format($salary->basic_salary, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Service</dt>
        @isset($salary->service)
        <dd>Rp {{ number_format($salary->service, 2, ',', '.') }}</dd>
        @else
        <dd>Rp 0</dd>
        @endisset
      </div>
      <div class="col-2">
        <dt>JKK</dt>
        <dd>Rp {{ number_format($salary->jkk, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>JKM</dt>
        <dd>Rp {{ number_format($salary->jkm, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>BPJS</dt>
        <dd>Rp {{ number_format($salary->bpjs, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Bruto</dt>
        <dd>Rp {{ number_format($salary->gross_salary, 2, ',', '.') }}</dd>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <dt>Bruto One Year</dt>
        <dd>Rp {{ number_format($salary->year_gross_salary, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Position Allowance</dt>
        <dd>Rp {{ number_format($salary->position_allowance, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>JHT One Year</dt>
        <dd>Rp {{ number_format($salary->jht_one_year, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Pension One Year</dt>
        <dd>Rp {{ number_format($salary->pension_one_year, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>One Year Netto</dt>
        <dd>Rp {{ number_format($salary->netto, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>PTKP</dt>
        <dd>Rp {{ number_format($salary->ptkp->fee, 2, ',', '.') }}</dd>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <dt>PKP</dt>
        <dd>Rp {{ number_format($salary->pkp, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>PPh 21 One Year</dt>
        <dd>Rp {{ number_format($salary->pph, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Monthly PPh</dt>
        <dd>Rp {{ number_format($salary->monthly_pph, 2, ',', '.') }}</dd>
      </div>
      <div class="col-2">
        <dt>Pension One Year</dt>
        <dd>Rp {{ number_format($salary->cug_cut, 2, ',', '.') }}</dd>
      </div>
    </div>
    <dt>Salary Received</dt>
    <dd>Rp {{ number_format($salary->salary_received, 2, ',', '.') }}</dd>
  </dl>
  <div class="d-flex justify-content-end align-items-center">
    <a href="{{ route('salaries.export', ['salary' => $salary]) }}" class="btn bg-teal">Export to excel</a>
  </div>
</x-adminlte-card>
@endsection