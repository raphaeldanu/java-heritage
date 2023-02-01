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
        <li class="breadcrumb-item"><a href="{{ route('ptkps.index') }}">PTKP Fee</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('ptkps.store') }}" method="POST">
    @csrf
    <x-adminlte-select2 name="tax_status" label="Tax Status" enable-old-support>
      <x-adminlte-options empty-option="Select Tax Status" :options="$statusPajak"/>
    </x-adminlte-select2>
    <x-adminlte-input name="fee" label="Fee" type="number" id="fee" placeholder="Fee" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection