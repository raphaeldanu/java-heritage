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
<div class="card">
  <div class="card-header">
    <div class="card-title">
      @can('create-ptkps')
        <a href="{{ route('ptkps.create') }}" class="btn btn-primary">Create New PTKP Fee</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $ptkps->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('ptkps.index') }}">
      <div class="row">
        <div class="col-11 pr-0">
          <input type="text" name="search" class="form-control" placeholder="Search ptkp" value="{{ request('search') }}">
        </div>
        <div class="col-1 pl-0">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($ptkps->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Tax Status</th>
          <th>Fee</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($ptkps as $ptkp)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ Str::headline($ptkp->tax_status->name) }}</td>
          <td>Rp {{ number_format($ptkp->fee, 2, '.', ',') }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('ptkps.show', ['ptkp' => $ptkp]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $ptkp)
              <a href="{{ route('ptkps.edit', ['ptkp' => $ptkp]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $ptkp)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $ptkp->id }}" theme="danger"/>
              <form method="post" action="{{ route('ptkps.destroy', ['ptkp' => $ptkp]) }}">
                <x-adminlte-modal id="modalDelete{{ $ptkp->id }}" title="Delete PTKP Fee" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete?
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
      PTKP Fee Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection