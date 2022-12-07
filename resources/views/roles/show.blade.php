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
  <dl>
    <dt>Role Name</dt>
    <dd>{{ $role->name }}</dd>
    <dt>Permission</dt>
    <ul>
    <div class="row">
        @foreach ($permissions as $item) 
        <div class="col-sm-3">
          <li>{{ Str::headline($item->name) }}</li>
        </div>
        @endforeach
      </div>
    </ul>
  </dl>
</x-adminlte-card>
@endsection