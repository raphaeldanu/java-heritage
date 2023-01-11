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
<x-adminlte-card title="Add Family Member For {{ $employee->name }}" theme="teal" theme-mode="outline">
  <form action="{{ route('families.update', ['employee' => $employee, 'family' => $family]) }}" method="POST">
    @csrf
    @method('PUT')
    <x-adminlte-input name="name" placeholder="Name" label="Name" value="{{ $family->name }}" enable-old-support/>
    <x-adminlte-select2 name="relationship" label="Relation" enable-old-support>
      <x-adminlte-options empty-option="Select Relation" :options="$family_relations" :selected="$family->relationship->value"/>
    </x-adminlte-select2>
    <x-adminlte-button type="submit" label="Update" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection