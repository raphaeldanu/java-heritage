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
        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Schedules</a></li>
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
      @can('create-schedules')
        <a href="{{ route('schedules.create', ['employee' => $employee]) }}" class="btn btn-primary">Create New Schedule</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $schedules->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0 px-1">
    @if ($schedules->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Month</th>
          <th>Year</th>
          <th>Workdays</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($schedules as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date_format($item->month_and_year, 'F') }}</td>
          <td>{{ date_format($item->month_and_year, 'Y') }}</td>
          <td>{{ $item->workdays }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
            <a href="{{ route('schedules.show', ['employee_schedule' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
            @can('update', $item)
            <a href="{{ route('schedules.edit', ['employee_schedule' => $item]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
            @endcan
            @can('delete', $item)
            <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
            <form method="post" action="{{ route('schedules.destroy', ['employee_schedule' => $item]) }}">
              <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Schedule" theme="teal"
                  icon="fas fa-bolt" size='lg' disable-animations>
                  Are you sure you want to delete {{ $item->name }}?
                    @csrf @method('delete')
                    <x-slot name="footerSlot">
                      <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                      <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                  </x-slot>
              </x-adminlte-modal>
            </form>
            @endcan
            </div>
          </td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <dt class="p-3">
      Schedules is empty
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection