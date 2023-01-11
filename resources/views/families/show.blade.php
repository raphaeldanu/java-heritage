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

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card title="Family Member of {{ $employee->name }}" theme="teal" theme-mode="outline">
    <dl>
      <dt>Name</dt>
      <dd>{{ $family->name }}</dd>
      <dt>Relation</dt>
      <dd>{{ Str::headline($family->relationship->name) }}</dd>
    </dl>
    <a href="{{ route('employees.show', ['employee' => $employee]) }}" class="btn bg-teal float-right">Go Back</a>
</x-adminlte-card>
@endsection