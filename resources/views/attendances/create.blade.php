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
        <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Training</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)
@section('plugins.TempusDominusBs4', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('trainings.store') }}" method="POST">
    @csrf
    <x-adminlte-select2 name="training_menu_id" label="Training Menu" enable-old-support>
      <x-adminlte-options empty-option="Select Training Menu" :options="$menus"/>
    </x-adminlte-select2>
    <x-adminlte-input name="trainers_name" label="Trainer's Name" type="text" id="trainers_name" placeholder="Trainer's Name" enable-old-support/>
    <x-adminlte-input name="training_venue" label="Training Venue" type="text" id="training_venue" placeholder="Training Venue" enable-old-support/>
    <x-adminlte-input-date name="training_date" label="Training Date" :config="$dateConfig" placeholder="Choose first join date" enable-old-support>
    <x-slot name="appendSlot">
      <div class="input-group-text bg-dark">
        <i class="fas fa-calendar-day"></i>
      </div>
    </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="training_length" label="Training Length (Hours)" type="number" id="training_length" placeholder="Training Length (Hours)" enable-old-support/>
    <x-adminlte-input name="cost_per_participant" label="Cost Per Participant" type="number" id="cost_per_participant" placeholder="Cost Per Participant" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection