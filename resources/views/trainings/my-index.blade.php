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
<div class="card">
  <div class="card-header border-bottom-0">
    <div class="card-title">
    </div>
    <div class="card-tools">
      {{ $trainings->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('my-trainings.index') }}">
      <div class="row px-1">
        @php
        $selected_menu = [request('training_menu_id')];
        @endphp
        <div class="col-6">
          <x-adminlte-select2 name="training_menu_id" enable-old-support>
            <x-adminlte-options empty-option="Select by training menu" :options="$menus" :selected="$selected_menu" />
          </x-adminlte-select2>
        </div>
        @php
          $selected_training_subject = [request('training_subject_id')];
        @endphp
        <div class="col-5">
          <x-adminlte-select2 name="training_subject_id" enable-old-support>
            <x-adminlte-options empty-option="Select by training subject" :options="$subjects" :selected="$selected_training_subject" />
        </x-adminlte-select2>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($trainings->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Training Title</th>
          <th>Subject</th>
          <th>Date</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($trainings as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->trainingMenu->title }}</td>
          <td>{{ $item->trainingMenu->trainingSubject->subject }}</td>
          <td>{{ date_format($item->training_date, 'd F Y') }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('my-trainings.show', ['training' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
          </div>
          </td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <dt class="p-3">
      Training Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection