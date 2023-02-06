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
        <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('attendances.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
    @section('plugins.BsCustomFileInput', true)
    <x-adminlte-input-file name="attendance_files" label="Upload Excel File" placeholder="Choose a file...">
      <x-slot name="prependSlot">
          <div class="input-group-text bg-lightblue">
              <i class="fas fa-upload"></i>
          </div>
      </x-slot>
  </x-adminlte-input-file>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection