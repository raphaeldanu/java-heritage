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
        <li class="breadcrumb-item"><a href="{{ route('employees.show', ['employee' => $employee]) }}">{{ Str::before($employee->name, ' ') }}</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card title="Edit Leave Quota For {{ $employee->name }}" theme="teal" theme-mode="outline">
  <form action="{{ route('employees.update-leave', ['employee' => $employee, 'leave' => $leave]) }}" method="POST">
    @csrf
    @method('PUT')
    <x-adminlte-input name="annual" type="number" placeholder="Annual Leave" label="Annual Leave" value="{{ $leave->annual }}" enable-old-support/>
    <x-adminlte-button type="submit" label="Update" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection