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

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <dt>Training Title</dt>
    <dd>{{ $training_menu->title }}</dd>
    <dt>Training Subject</dt>
    <dd>{{ $training_menu->trainingSubject->subject }}</dd>
    @isset($training_menu->department)
    <dt>Department</dt>
    <dd>{{ $training_menu->department->name }}</dd>
    @endisset
  </dl>
  <div class="row">
    <div class="col d-flex justify-content-end">
      <a href="{{ route('training-menus.index') }}" class="btn bg-teal">Go back</a>
    </div>
  </div>
</x-adminlte-card>
@endsection