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
  <div class="card-header">
    <div class="card-title">
      @can('create-salaries')
        <a href="{{ route('salaries.create') }}" class="btn btn-primary">Generate Salary</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $employees->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('salaries.index') }}">
      <div class="row px-1">
        @can('view-all-salaries')
        <div class="col-8">
        @else
        <div class="col-11">
        @endcan
          <input type="text" name="search" class="form-control" placeholder="Search Employees" value="{{ request('search') }}">
        </div>
        @can('view-all-salaries')
        @php
        $selected_department = [request('department_id')];
        @endphp
        <div class="col-3">
          <x-adminlte-select2 name="department_id" enable-old-support>
            <x-adminlte-options empty-option="Select by departments" :options="$departments" :selected="$selected_department" />
          </x-adminlte-select2>
        </div>
        @endcan
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
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($employees as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>@isset($item->department) {{ $item->department->name }} @else {{ "Empty Department" }} @endisset</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('salaries.show-by-employee', ['employee' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
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