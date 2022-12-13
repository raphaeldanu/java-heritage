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
        <li class="breadcrumb-item"><a href="{{ route('positions.index') }}">Positions</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('positions.store') }}" method="POST">
    @csrf
    <x-adminlte-input name="name" label="Position Name" type="text" id="name" placeholder="Position Name" enable-old-support/>
    @php
      $options = $departments->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
    @endphp
    <x-adminlte-select2 name="department_id" label="Department" enable-old-support>
      <x-adminlte-options empty-option="Select Department" :options="$options"/>
    </x-adminlte-select2>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection