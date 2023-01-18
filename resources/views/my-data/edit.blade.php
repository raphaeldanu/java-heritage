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
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)
@section('plugins.TempusDominusBs4', true)

@section('content')
<x-adminlte-card title="Edit Employee For {{ $employee->name }}" theme="teal" theme-mode="outline">
  <form action="{{ route('employees.update', ['employee' => $employee]) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="user_id" value="{{ $employee->user_id }}">
    <x-adminlte-input name="nip" label="NIP" type="text" id="nip" placeholder="NIP" enable-old-support value="{{ $employee->nip }}" disabled />
    <x-adminlte-input name="name" label="Full Name" type="text" id="name" placeholder="Full Name" enable-old-support value="{{ $employee->name }}" />
    <x-adminlte-input name="nik" label="NIK" type="text" id="nik" placeholder="NIK" enable-old-support value="{{ $employee->nik }}" />
    <x-adminlte-input name="bpjs_tk_number" label="BPJS Tenaga Kerja Number" type="text" id="bpjs_tk_number" placeholder="BPJS Tenaga Kerja Number" enable-old-support value="{{ $employee->bpjs_tk_number }}"/>
    <x-adminlte-input name="bpjs_kes_number" label="BPJS Kesehatan Number" type="text" id="bpjs_kes_number" placeholder="BPJS Kesehatan Number" enable-old-support value="{{ $employee->bpjs_kes_number }}"/>
    <x-adminlte-input name="npwp_number" label="NPWP Number" type="text" id="npwp_number" placeholder="Employee NPWP Number" enable-old-support value="{{ $employee->npwp_number }}"/>
    <x-adminlte-select2 name="tax_status" label="Tax Status" enable-old-support>
      <x-adminlte-options empty-option="Select Tax Status" :options="$statusPajak" :selected="$employee->tax_status" />
    </x-adminlte-select2>
    <x-adminlte-select2 name="employment_status" label="Employment Status" enable-old-support>
      <x-adminlte-options empty-option="Select Employment Status" :options="$employmentStatus" :selected="$employee->employment_status"/>
    </x-adminlte-select2>
    <x-adminlte-select2 name="position_id" label="Position" enable-old-support>
      <x-adminlte-options empty-option="Select Position" :options="$positions" :selected="$employee->position_id"/>
    </x-adminlte-select2>
    <x-adminlte-select2 name="salary_range_id" label="Salary Range" enable-old-support>
      <x-adminlte-options empty-option="Select Salary Range" :options="$salaryRanges" :selected="$employee->salary_range_id"/>
    </x-adminlte-select2>
    <x-adminlte-input-date name="first_join" label="First Join Date" :config="$dateConfig" placeholder="Choose first join date" enable-old-support value="{{ $employee->first_join }}">
    <x-slot name="appendSlot">
      <div class="input-group-text bg-dark">
        <i class="fas fa-calendar-day"></i>
      </div>
    </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input-date name="last_contract_start" label="Last Contract Start Date" :config="$dateConfig" placeholder="Choose first join date" enable-old-support value="{{ $employee->last_contract_start }}">
    <x-slot name="appendSlot">
      <div class="input-group-text bg-dark">
        <i class="fas fa-calendar-day"></i>
      </div>
    </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input-date name="last_contract_end" label="Last Contract End Date" :config="$lastContractEndDateConfig" placeholder="Choose last contract start date" value="{{ $employee->last_contract_end }}" enable-old-support>
    <x-slot name="appendSlot">
      <div class="input-group-text bg-dark">
        <i class="fas fa-calendar-day"></i>
      </div>
    </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="birth_place" label="Birth Place" type="text" id="birth_place" placeholder="Birth Place" enable-old-support value="{{ $employee->birth_place }}"/>
    <x-adminlte-input-date name="birth_date" label="Birth Date" :config="$birthDateConfig" placeholder="Choose Birth date" value="{{ $employee->birth_date }}" enable-old-support>
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="phone_number" label="Active Phone Number" type="text" id="phone_number" placeholder="Active Phone Number" enable-old-support value="{{ $employee->phone_number }}"/>
    <x-adminlte-select2 name="gender" label="Gender" enable-old-support>
      <x-adminlte-options empty-option="Select Gender" :options="$genders" :selected="$employee->gender"/>
    </x-adminlte-select2>
    <x-adminlte-textarea name="address_on_id" placeholder="Address on KTP" label="Address on KTP" rows=3 enable-old-support>{{ $employee->address_on_id }}</x-adminlte-textarea>
    <x-adminlte-select2 name="blood_type" label="Gender" enable-old-support>
      <x-adminlte-options empty-option="Select Blood Type" :options="$bloodTypes" :selected="$employee->blood_type"/>
    </x-adminlte-select2>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection