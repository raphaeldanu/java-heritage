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
      @can('create-positions')
        <a href="{{ route('positions.create') }}" class="btn btn-primary">Create New Position</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $positions->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('positions.index') }}">
      <div class="row">
        <div class="col-8">
          <input type="text" name="search" class="form-control" placeholder="Search positions" value="{{ request('search') }}">
        </div>
        @php
          $options = $departments->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
          $selected2 = [request('department_id')];
        @endphp
        <div class="col-3">
          <x-adminlte-select name="department_id" enable-old-support>
            <x-adminlte-options empty-option="Select by department" :options="$options" :selected="$selected2" />
        </x-adminlte-select>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($positions->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Name</th>
          <th>Department</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($positions as $position)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $position->name }}</td>
          <td>{{ $position->department->name }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('positions.show', ['position' => $position]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $position)
              <a href="{{ route('positions.edit', ['position' => $position]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $position)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $position->id }}" theme="danger"/>
              <form method="post" action="{{ route('positions.destroy', ['position' => $position]) }}">
                <x-adminlte-modal id="modalDelete{{ $position->id }}" title="Delete Position" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete {{ $position->name }}?
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
      Positions Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection