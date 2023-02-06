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
        <li class="breadcrumb-item"><a href="{{ route('salaries.index') }}">Salaries</a></li>
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
      @can('create-salaries')
      <a href="{{ route('salaries.create-by-employee', ['employee' => $employee]) }}" class="btn btn-primary">Generate Salary Roll</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $salaries->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0 px-1">
    @if ($salaries->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Date</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($salaries as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ date_format($item->month_and_year, 'F Y') }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
            <a href="{{ route('salaries.show', ['salary' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
            @can('delete', $item)
            <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
            @endcan
            </div>
            @can('delete', $item)
            <form method="post" action="{{ route('salaries.destroy', ['salary' => $item]) }}">
              <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Salaries" theme="teal"
                  icon="fas fa-bolt" size='lg' disable-animations>
                  Are you sure you want to delete this salary?
                    @csrf @method('delete')
                    <x-slot name="footerSlot">
                      <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                      <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                  </x-slot>
              </x-adminlte-modal>
            </form>
            @endcan
          </td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <dt class="p-3">
      Salaries is empty
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>
@endsection