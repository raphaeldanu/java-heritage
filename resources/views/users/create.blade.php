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
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ url('users') }}" method="POST">
    @csrf
    <x-adminlte-input name="username" label="Username" type="text" id="username" placeholder="Username" enable-old-support/>
    <x-adminlte-input name="password" label="Password" type="password" id="password" placeholder="Password"/>
    <x-adminlte-input name="password_confirmation" label="Password Confirmation" type="password" id="password_confirmation" placeholder="Password Confirmation"/>
    @php
      $options = $roles->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
    @endphp
    <x-adminlte-select2 name="role" label="Role" enable-old-support>
      <x-adminlte-options empty-option="Select role" :options="$options"/>
    </x-adminlte-select2>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection