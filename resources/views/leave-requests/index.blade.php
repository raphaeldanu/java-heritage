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
        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">Request For Leave</a>
      @endcan
    </div>
    <div class="card-tools">
      {{ $leave_requests->links() }}
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body p-0">
    <form action="{{ url('leave-requests') }}">
      <div class="row">
        <div class="col-6">
          <input type="text" name="search" class="form-control" placeholder="Search note" value="{{ request('search') }}">
        </div>
        @php
          $selected1 = [request('status')];
        @endphp
        <div class="col-2">
          <x-adminlte-select name="status" enable-old-support>
            <x-adminlte-options empty-option="Search by status" :options="$statuses" :selected="$selected1" />
        </x-adminlte-select>
        </div>
        @php
          $selected2 = [request('type')];
        @endphp
        <div class="col-3">
          <x-adminlte-select name="type" enable-old-support>
            <x-adminlte-options empty-option="Select by leave type" :options="$leave_types" :selected="$selected2" />
        </x-adminlte-select>
        </div>
        <div class="col-1">
          <x-adminlte-button type="submit" icon="fas fa-search" theme="info" class="float-right" class="btn-block"/>
        </div>
      </div>
    </form>
    @if ($leave_requests->isNotEmpty())
    <table class="table">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          @if(auth()->user()->can('approve-all-leave-request') or auth()->user()->can('approve-leave-request'))
          <th>Name</th>
          @endif
          <th>Date</th>
          <th>Type</th>
          <th>Status</th>
          <th class="col-2 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($leave_requests as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          @if(auth()->user()->can('approve-all-leave-request') or auth()->user()->can('approve-leave-request'))
          <td>$item->employee->name</td>
          @endif
          <td>{{ date_format($item->start_date, "d F Y") }}</td>
          <td>{{ Str::headline($item->leave_type->name) }}</td>
          <td>{{ Str::headline($item->status->name) }}</td>
          <td>
            <div class="d-flex justify-content-around align-items-center">
              <a href="{{ route('leave-requests.show', ['leave_request' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
              @can('update', $item)
              <a href="{{ route('leave-requests.edit', ['leave_request' => $item]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
              @endcan
              @can('delete', $item)
              <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
              <form method="post" action="{{ route('leave-requests.destroy', ['leave_request' => $item]) }}">
                <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Role" theme="teal"
                    icon="fas fa-bolt" size='lg' disable-animations>
                    Are you sure you want to delete this request?
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
      Request is empty
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>


@endsection