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
        <li class="breadcrumb-item"><a href="{{ route('salary-ranges.index') }}">Salary Range</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('salary-ranges.update', ['salary_range' => $salaryRange]) }}" method="POST">
    @csrf
    @method('put')
    <x-adminlte-input name="name" label="Salary Range Name" type="text" id="name" placeholder="Salary Range Name" enable-old-support value="{{ $salaryRange->name }}"/>
    @php
      $options = $levels->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
      $selected = [$salaryRange->level_id];
    @endphp
    <x-adminlte-select2 name="level_id" label="Employee Level" enable-old-support>
      <x-adminlte-options empty-option="Select Employee Level" :options="$options" :selected="$selected"/>
    </x-adminlte-select2>
    <x-adminlte-input name="base_salary" label="Base Salary" type="number" id="base_salary" placeholder="Salary Ranges Name" enable-old-support value="{{ $salaryRange->base_salary }}">
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection