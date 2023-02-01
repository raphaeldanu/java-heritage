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
      @can('create-departments')
        <a href="{{ route('departments.create') }}" class="btn btn-primary">Create New Department</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $departments->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('departments.index') }}">
      <div class="row">
        <div class="col-11 pr-0">
          <input type="text" name="search" class="form-control" placeholder="Search department" value="{{ request('search') }}">
        </div>
        <div class="col-1 pl-0">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($departments->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Department Name</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($departments as $department)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $department->name }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('departments.show', ['department' => $department]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $department)
              <a href="{{ route('departments.edit', ['department' => $department]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $department)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $department->id }}" theme="danger"/>
              <form method="post" action="{{ route('departments.destroy', ['department' => $department]) }}">
                <x-adminlte-modal id="modalDelete{{ $department->id }}" title="Delete Department" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete {{ $department->name }}?
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
      Department Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection