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
        <li class="breadcrumb-item"><a href="{{ route('training-menus.index') }}">Training Menu</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('training-menus.update', ['training_menu' => $training_menu]) }}" method="POST">
    @csrf
    @method('put')
    <x-adminlte-input name="title" label="Training Menu Title" type="text" id="name" placeholder="Training Menu Title" enable-old-support value="{{ $training_menu->title }}"/>
    <x-adminlte-select2 name="training_subject_id" label="Training Subject" enable-old-support>
      <x-adminlte-options empty-option="Select Training Subject" :options="$subjects" :selected="$training_menu->training_subject_id"/>
    </x-adminlte-select2>
    <x-adminlte-select2 name="department_id" label="Department (leave it empty if not departmental training)" enable-old-support>
      <x-adminlte-options empty-option="Select Department" :options="$departments" :selected="$training_menu->department_id"/>
    </x-adminlte-select2>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection