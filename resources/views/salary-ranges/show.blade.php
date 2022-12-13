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
        <li class="breadcrumb-item"><a href="{{ route('salary-ranges.index') }}">Salary Ranges</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <dt>Salary Range Name</dt>
    <dd>{{ $salaryRanges->name }}</dd>
    <dt>Employee Level</dt>
    <dd>{{ $salaryRanges->level->name }}</dd>
    <dt>Base Salary</dt>
    <dd>Rp {{ $salaryRange->base_salary }}</dd>
  </dl>
</x-adminlte-card>
@endsection