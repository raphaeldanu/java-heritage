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
        <li class="breadcrumb-item"><a href="{{ url('roles') }}">Roles</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ url('roles') }}" method="POST">
    @csrf
    <x-adminlte-input name="name" label="Role Name" type="text" id="name" placeholder="Role Name" enable-old-support/>
    <label for="">Permission</label>
    <div class="row">
      @foreach ($permissions as $item) 
      <div class="form-group col-sm-3">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="{{ $item->id }}" name="{{ $item->id }}" @checked(old($item->id))>
          <label class="custom-control-label" for="{{ $item->id }}">{{ Str::headline($item->name) }}</label>
        </div>
      </div>
      @endforeach
    </div>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection