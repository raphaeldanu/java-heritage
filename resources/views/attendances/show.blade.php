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

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <div class="row">
      <div class="col-9">
        <dt>Training Title</dt>
        <dd>{{ $training->trainingMenu->title }}</dd>
      </div>
      <div class="col-3 d-flex justify-content-end align-items-center">
        <a href="{{ route('trainings.index') }}" class="btn bg-teal">Go back</a>
      </div>
    </div>
    <dt>Training Subject</dt>
    <dd>{{ $training->trainingMenu->trainingSubject->subject }}</dd>
    @isset($training->trainingMenu->department)
    <dt>Department</dt>
    <dd>{{ $training->trainingMenu->department->name }}</dd>
    @endisset
    <dt>Date</dt>
    <dd>{{ date_format($training->training_date, 'd F Y') }}</dd>
    <dt>Trainer's Name</dt>
    <dd>{{ $training->trainers_name }}</dd>
    <dt>Training Venue</dt>
    <dd>{{ $training->training_venue }}</dd>
    <dt>Attendants</dt>
    <dd>{{ $training->attendants }}</dd>
    <dt>Cost per Participant</dt>
    <dd>Rp {{ number_format($training->cost_per_participant, 2, ',', '.') }}</dd>
    @if ($training->attendants > 0)
    <dt>Total Cost</dt>
    <dd>{{ number_format($training->attendants * $training->cost_per_participant, 2, ',', '.') }}</dd>
    @endif
    <dt>Training Length</dt>
    <dd>{{ $training->training_length }} hours</dd>
    @if ($training->attendants > 0)
    <dt>Total Hour</dt>
    <dd>{{ $training->attendants * $training->training_length }} hours</dd>
    @endif
  </dl>
</x-adminlte-card>
<div class="card card-outline card-teal">
  <div class="card-header">
    <div class="d-flex align-items-center justify-content-between">
      <div class="card-title">
        <h5 class="mb-0">Attendants</h5>
      </div>
      <div class="card-tools">
        @if ($training->employees->isNotEmpty())
          @can('update', $training)
          <a href="{{ route('trainings.add-attendants', ['training' => $training]) }}" class="btn bg-teal">Edit Attendants</a>
          @endcan
        @else
          @can('update', $training)
          <a href="{{ route('trainings.add-attendants', ['training' => $training]) }}" class="btn bg-teal">Add Attendants</a>
          @endcan
        @endif
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
      No Attendants Yet
    </div>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection