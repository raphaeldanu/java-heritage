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
        <li class="breadcrumb-item"><a href="{{ route('trainings.index') }}">Training</a></li>
        @if ($breadcrumb == 'show')
        <li class="breadcrumb-item"><a href="{{ route('trainings.show', ['training' => $training]) }}">Training Detail</a></li>
        @else
        <li class="breadcrumb-item"><a href="{{ route('trainings.edit', ['training' => $training]) }}">Training Edit</a></li>
        @endif
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
      <h5 class="mb-0">Attendants</h5>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0 px-1">
    @if ($attendants->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Full Name</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($attendants as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>
            <form method="post" action="@if($breadcrumb == 'show') {{ route('trainings.remove-attendants', ['training' => $training, 'employee'=> $item]) }} @else {{ route('trainings.remove-edit-attendants', ['training' => $training, 'employee'=> $item]) }} @endif">
              <x-adminlte-button label="Remove" data-toggle="modal" data-target="#modalActivate{{ $item->id }}" theme="danger"/>
              <x-adminlte-modal id="modalActivate{{ $item->id }}" title="Remove Employee from attendant?" theme="teal"
                  icon="fas fa-bolt" size='lg' disable-animations>
                  Are you sure you want to remove {{ $item->name }} from attendants?
                    @csrf @method('put')
                    <x-slot name="footerSlot">
                      <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                      <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                  </x-slot>
              </x-adminlte-modal>
            </form>
          </td>
        </tr>
        @endforeach 
      </tbody>
    </table>
    @else
    <dt class="p-3">
      Attendant is empty
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>

<div class="card card-outline card-teal">
  <div class="card-header border-bottom-0">
    <div class="card-title">
      <h5 class="mb-0">Employees</h5>
    </div>
    <div class="card-tools">
      {{ $employees->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0 px-1">
    <form action="@if($breadcrumb == 'show') {{ route('trainings.add-attendants', ['training' => $training]) }} @else {{ route('trainings.edit-attendants', ['training' => $training]) }} @endif">
      <div class="row">
        <div class="col-11">
          <input type="text" name="search" class="form-control" placeholder="Search name" value="{{ request('search') }}">
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
          <th>Full Name</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($employees as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->name }}</td>
          <td>
            <x-adminlte-button label="Add" data-toggle="modal" data-target="#modalActivate{{ $item->id }}" theme="success"/>
            <form method="post" action="@if($breadcrumb == 'show') {{ route('trainings.store-attendants', ['training' => $training, 'employee'=> $item]) }} @else {{ route('trainings.store-edit-attendants', ['training' => $training, 'employee'=> $item]) }} @endif">
              <x-adminlte-modal id="modalActivate{{ $item->id }}" title="Add Employee as attendant?" theme="teal"
                  icon="fas fa-bolt" size='lg' disable-animations>
                  Are you sure you want to add {{ $item->name }} as attendants?
                    @csrf
                    <x-slot name="footerSlot">
                      <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                      <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                  </x-slot>
              </x-adminlte-modal>
            </form>
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