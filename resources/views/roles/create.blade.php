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

@section('plugins.BootstrapSwitch', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <x-adminlte-input name="name" label="Role Name" type="text" id="name" placeholder="Role Name"/>
    <div class="row">
      @foreach ($permissions as $permission)
      <x-adminlte-input-switch name="{{ $permission->id }}" data-on-text="YES" data-off-text="NO" data-on-color="teal" label="{{ Str::headline($permission->name) }}" fgroup-class="col-sm-3"/>
      @endforeach
    </div>
    <x-slot name="footerSlot">
      <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto"/>
    </x-slot>
  </form>
</x-adminlte-card>
@endsection