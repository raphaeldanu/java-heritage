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
        <a href="{{ route('training-menus.create') }}" class="btn btn-primary">Create New Training Menu</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $training_menus->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ route('training-menus.index') }}">
      <div class="row">
        <div class="col-5">
          <input type="text" name="search" class="form-control" placeholder="Search positions" value="{{ request('search') }}">
        </div>
        @php
            $selected_training_subject = [request('training_subject_id')];
        @endphp
        <div class="col-3">
          <x-adminlte-select name="training_subject_id" enable-old-support>
            <x-adminlte-options empty-option="Select by training subject" :options="$subjects" :selected="$selected_training_subject" />
        </x-adminlte-select>
        </div>
        @php
            $selected_department = [request('department_id')];
        @endphp
        <div class="col-3">
          <x-adminlte-select name="department_id" enable-old-support>
            <x-adminlte-options empty-option="Select by department" :options="$departments" :selected="$selected_department" />
        </x-adminlte-select>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($training_menus->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Title</th>
          <th>Subject</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($training_menus as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->title }}</td>
          <td>{{ $item->trainingSubject->subject }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('training-menus.show', ['training_menu' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $item)
              <a href="{{ route('training-menus.edit', ['training_menu' => $item]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $item)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
              <form method="post" action="{{ route('training-menus.destroy', ['training_menu' => $item]) }}">
                <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Role" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete "{{ $item->title }}" training?
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
      Training Menu Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection