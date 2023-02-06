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
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content') 
<x-adminlte-card title="Change Password" theme="teal" theme-mode="outline">
  <form action="{{ route('profile.update-password') }}" method="POST">
    @csrf
    @method("put")
    <x-adminlte-input name="old_password" label="Old Password" type="password" id="password" placeholder="Old Password"/>
    <x-adminlte-input name="new_password" label="New Password" type="password" id="password" placeholder="New Password"/>
    <x-adminlte-input name="new_password_confirmation" label="New Password Confirmation" type="password" id="password_confirmation" placeholder="New Password Confirmation"/>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection