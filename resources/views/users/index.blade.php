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
      @can('create-users')
        <a href="{{ url('users/create') }}" class="btn btn-primary">Create New User</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $users->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ url('users') }}">
      <div class="row">
        <div class="col-7">
          <input type="text" name="search" class="form-control" placeholder="Search username" value="{{ request('search') }}">
        </div>
        @php
          $selected1 = [request('status')];
        @endphp
        <div class="col-2">
          <x-adminlte-select name="status" enable-old-support>
            <x-adminlte-options empty-option="Search by status" :options="['active' => 'Active', 'inactive' => 'Inactive']" :selected="$selected1" />
        </x-adminlte-select>
        </div>
        @php
          $options = $roles->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
          $selected2 = [request('role')];
        @endphp
        <div class="col-2">
          <x-adminlte-select name="role" enable-old-support>
            <x-adminlte-options empty-option="Select by role" :options="$options" :selected="$selected2" />
        </x-adminlte-select>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($users->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Username</th>
          <th></th>
          @can('activate-users')
          <th>Activation</th>
          @endcan
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $user->username }}</td>
          <td>@if ($user->active) Active @else Inactive @endif</td>
          @can('activate-users')
          <td>
            @can('activate', $user)
              @if ($user->active)
              <x-adminlte-button label="Deactivate" data-toggle="modal" data-target="#modalActivate{{ $user->id }}" theme="danger"/>
              @else
              <x-adminlte-button label="Activate" data-toggle="modal" data-target="#modalActivate{{ $user->id }}" theme="success"/>
              @endif
              <form method="post" action="/users/{{ $user->id }}/change-status">
                <x-adminlte-modal id="modalActivate{{ $user->id }}" title="Change User Active Status?" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to change {{ $user->username }} status?
                      @csrf @method('put')
                      <x-slot name="footerSlot">
                        <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                        <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                    </x-slot>
                </x-adminlte-modal>
              </form>
            @endcan
          </td>
          @endcan
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="/users/{{ $user->id }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $user)
              <a href="/users/{{ $user->id }}/edit" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $user)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $user->id }}" theme="danger"/>
              <form method="post" action="/users/{{ $user->id }}">
                <x-adminlte-modal id="modalDelete{{ $user->id }}" title="Delete Role" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete {{ $user->username }}?
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
      Users Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection