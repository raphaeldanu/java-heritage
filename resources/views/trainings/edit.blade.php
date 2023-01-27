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
  <form action="{{ route('trainings.update', ['training' => $training]) }}" method="POST">
    @csrf @method('PUT')
    <x-adminlte-select2 name="training_menu_id" label="Training Menu" enable-old-support>
      <x-adminlte-options empty-option="Select Training Menu" :options="$menus" :selected="$training->training_menu_id"/>
    </x-adminlte-select2>
    <x-adminlte-input name="trainers_name" label="Trainer's Name" type="text" id="trainers_name" placeholder="Trainer's Name" value="{{ $training->trainers_name }}" enable-old-support/>
    <x-adminlte-input name="training_venue" label="Training Venue" type="text" id="training_venue" placeholder="Training Venue" value="{{ $training->training_venue }}" enable-old-support/>
    <x-adminlte-input-date name="training_date" label="Training Date" :config="$dateConfig" placeholder="Choose first join date" value="{{ date_format($training->training_date, 'Y-m-d') }}" enable-old-support>
    <x-slot name="appendSlot">
      <div class="input-group-text bg-dark">
        <i class="fas fa-calendar-day"></i>
      </div>
    </x-slot>
    </x-adminlte-input-date>
    <x-adminlte-input name="training_length" label="Training Length (Hours)" type="number" id="training_length" placeholder="Training Length (Hours)" value="{{ $training->training_length }}" enable-old-support/>
    <x-adminlte-input name="cost_per_participant" label="Cost Per Participant" type="number" id="cost_per_participant" placeholder="Cost Per Participant" value="{{ number_format($training->cost_per_participant, 0, '', '') }}" enable-old-support>
      <x-slot name="prependSlot">
        <div class="input-group-text">
          Rp
        </div>
      </x-slot>
    </x-adminlte-input>
    <x-adminlte-button type="submit" label="Save" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
<div class="card card-outline card-teal">
  <div class="card-header">
    <div class="d-flex align-items-center justify-content-between">
      <div class="card-title">
        <h5 class="mb-0">Attendants</h5>
      </div>
      <div class="card-tools">
        @can('update', $training)
        <a href="{{ route('trainings.edit-attendants', ['training' => $training]) }}" class="btn bg-teal">Edit Attendants</a>
        @endcan
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    @if ($training->employees->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Fullname</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($training->employees as $employee)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $employee->name }}</td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <div class="d-flex justify-content-center">
      @can('update', $training)
      <a href="{{ route('trainings.add-attendants', ['training' => $training]) }}" class="btn bg-teal">Add Attendants</a>
      @endcan
    </div>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection