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

@section('content')
<div class="card card-outline card-teal">
  <div class="card-header border-bottom-0">
    <div class="card-title">
      <h5 class="mb-0">Attendances</h5>
    </div>
    <div class="card-tools">
      {{ $attendances->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0 px-1">
    @if ($attendances->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Date</th>
          <th>Time</th>
          <th>In & Out</th>
          <th>Machine</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($attendances as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date_format($item->scan_datetime, 'd F Y') }}</td>
          <td>{{ date_format($item->scan_datetime, 'H:i:s') }}</td>
          <td>{{ $item->in_out }}</td>
          <td>{{ $item->machine }}</td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <dt class="p-3">
      Attendances is empty
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection