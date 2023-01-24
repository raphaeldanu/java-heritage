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
        <a href="{{ route('employees.pick-user') }}" class="btn btn-primary">Create New Employee</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $employees->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('employees.index') }}">
      <div class="row">
        <div class="col-6">
          <input type="text" name="search" class="form-control" placeholder="Search Employees" value="{{ request('search') }}">
        </div>
        @php
          $options1 = $departments->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
          $selected1 = [request('department_id')];
        @endphp
        <div class="col-3">
          <x-adminlte-select name="department_id" enable-old-support>
            <x-adminlte-options empty-option="Select by department" :options="$options1" :selected="$selected1" />
        </x-adminlte-select>
        </div>
        @php
          $options2 = $levels->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
          $selected2 = [request('level_id')];
        @endphp
        <div class="col-2">
          <x-adminlte-select name="level_id" enable-old-support>
            <x-adminlte-options empty-option="Select by level" :options="$options2" :selected="$selected2" />
        </x-adminlte-select>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($employees->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Name</th>
          <th>Position</th>
          <th>Salary Range</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($employees as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>@isset($item->position) {{ $item->position->name }} @else {{ "Empty Position" }} @endisset</td>
          <td>@isset($item->salaryRange) {{ $item->salaryRange->name.' - '.$item->salaryRange->level->name }} @else {{ "Empty Salary Range" }} @endisset</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('employees.show', ['employee' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $item)
              <a href="{{ route('employees.edit', ['employee' => $item]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $item)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
              <form method="post" action="{{ route('employees.destroy', ['employee' => $item]) }}">
                <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Role" theme="teal"
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
      Employees Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection