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
        <li class="breadcrumb-item"><a href="{{ route('approve-leave-requests.index') }}">Approve Leave Requests</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <dt>Requested By</dt>
    <dd>{{ $leaveRequest->employee->name }}</dd>
    <dt>Leave Type</dt>
    <dd>{{ Str::headline($leaveRequest->leave_type->name) }}</dd>
    @isset($leaveRequest->end_date)
    <dt>Date</dt>
    <dd>{{ date_format($leaveRequest->start_date, "d F Y").' - '.date_format($leaveRequest->end_date, "d F Y") }}</dd>
    <dt>Days</dt>
    <dd>{{ $days }}</dd>
    @else
    <dt>Date</dt>
    <dd>{{ date_format($leaveRequest->start_date, 'd F Y') }}</dd>
    @endisset
    <dt>Status</dt>
    <dd>{{ Str::headline($leaveRequest->status->name) }}</dd>
    @isset($leaveRequest->approved_by)
    <dt>Approved By</dt>
    <dd>{{ $leaveRequest->approver->employee->name }}</dd>
    @endisset
    @isset($leaveRequest->note)
    <dt>Note</dt>
    <dd>{{ $leaveRequest->note }}</dd>
    @endisset
    @isset($leaveRequest->note_from_approver)
    <dt>Note From Approver</dt>
    <dd>{{ $leaveRequest->note_from_approver }}</dd>
    @endisset
  </dl>
  <div class="row">
    <div class="col d-flex justify-content-end">
      <a href="{{ route('approve-leave-requests.index') }}" class="btn bg-teal">Go Back</a>
    </div>
  </div>
</x-adminlte-card>
@endsection