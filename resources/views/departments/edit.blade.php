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
        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Roles</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection
@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('departments.update', ['department' => $department]) }}" method="POST">
    @csrf
    @method('PUT')
    <x-adminlte-input name="name" label="Department Name" type="text" id="name" placeholder="Department Name" value="{{ $department->name }}" enable-old-support/>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection