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
        <li class="breadcrumb-item"><a href="{{ route('leave-requests.index') }}">My Leave Requests</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)
@section('plugins.TempusDominusBs4', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('leave-requests.update', ['leave_request' => $leaveRequest]) }}" method="POST">
    @csrf
    @method('PUT')
    <x-adminlte-select2 name="leave_type" label="Leave Type" enable-old-support>
      <x-adminlte-options empty-option="Select leave type" :options="$types" :selected="$leaveRequest->leave_type->value"/>
    </x-adminlte-select2>
    <x-adminlte-input-date name="start_date" label="Start Date" :config="$dateConfig" placeholder="Choose start date" enable-old-support value="{{ date_format($leaveRequest->start_date, 'Y-m-d') }}">
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input-date name="end_date" label="End Date" :config="$dateConfig" placeholder="Choose end date (Leave it empty if only 1 day)" enable-old-support value="{{ date_format($leaveRequest->end_date, 'Y-m-d') }}">
      <x-slot name="appendSlot">
        <div class="input-group-text bg-dark">
          <i class="fas fa-calendar-day"></i>
        </div>
      </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-textarea name="note" placeholder="Note" label="Note" rows=3 enable-old-support>
      {{ $leaveRequest->note }}
    </x-adminlte-textarea>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection