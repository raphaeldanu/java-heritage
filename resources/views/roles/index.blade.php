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
      @can('create-roles')
        <a href="{{ url('roles/create') }}" class="btn btn-primary">Create New Role</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $roles->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ url('roles') }}">
      <div class="row">
        <div class="col-11 pr-0">
          <input type="text" name="search" class="form-control" placeholder="Search roles" value="{{ request('search') }}">
        </div>
        <div class="col-1 pl-0">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($roles->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Role Name</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($roles as $role)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $role->name }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="/roles/{{ $role->id }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $role)
              <a href="/roles/{{ $role->id }}/edit" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $role)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $role->id }}" theme="danger"/>
              <form method="post" action="/roles/{{ $role->id }}">
                <x-adminlte-modal id="modalDelete{{ $role->id }}" title="Delete Role" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure?
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
      Roles Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection