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
@isset($employee)
@if ($remaining_contract != null and $remaining_contract <= 90)
<x-adminlte-alert theme="warning" title="Warning">
  {{ $employee->name.' contract will end in '.$remaining_contract.' days!' }}
</x-adminlte-alert>
@endif
<div class="row">
  <div class="col-md-6 col-lg-5">
    <x-adminlte-card theme="teal" theme-mode="outline">
        <div class="d-flex justify-content-center">
          <div class="img-circle elevation-2 d-flex bg-dark" style="width:65px;height:65px;">
            <i class="fas fa-2x fa-user text-silver m-auto"></i>
          </div>
        </div>
        <h3 class="profile-username text-center">{{ $employee->name }}</h3>
        <p class="text-muted text-center">{{ $employee->nip }}</p>
        <ul class="list-group list-group-unbordered mb-3">
        <li class="list-group-item">
        <b>NIK</b> <a class="float-right text-dark">{{ $employee->nik }}</a>
        </li>
        <li class="list-group-item">
        <b>NPWP</b> <a class="float-right text-dark">{{ $employee->npwp_number }}</a>
        </li>
        <li class="list-group-item border-0">
        <b>Phone Number</b> <a class="float-right text-dark">{{ $employee->phone_number }}</a>
        </li>
        </ul>
    </x-adminlte-card>

    <x-adminlte-card theme="teal" title="About">
      <strong><i class="fas fa-id-card mr-1"></i> Employment Status</strong>
      <p class="text-muted">
      {{ $employee->employment_status->name }}
      </p>
      <hr>
      <strong><i class="fas fa-id-badge mr-1"></i> Position - Department</strong>
      <p class="text-muted">@isset($employee->position)
        {{ $employee->position->name.' - '.$employee->position->department->name }}
        @else
        {{ 'Not in any position, please contact HRD staff' }}
        @endisset</p>
        
      <hr>
      <strong><i class="fas fas fa-money-bill-wave mr-1"></i> Salary Range</strong>
      <p class="text-muted">@isset($employee->salaryRange)
        {{ $employee->salaryRange->name.' '.$employee->salaryRange->level->name }}
        @else
        {{ "Not in any salary range, please contact HRD staff" }}
      @endisset
      </p>
      <hr>
      <strong><i class="fas fa-venus-mars mr-1"></i> Gender</strong>
      <p class="text-muted">{{ $employee->gender->name }}</p>
    </x-adminlte-card>
  </div>
  <div class="col-md-6 col-lg-7">
    <div class="card card-teal card-tabs">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs">
          <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Details</a></li>
          {{-- @can('view', $employee->residence) --}}
          <li class="nav-item"><a class="nav-link " href="#residence" data-toggle="tab">Residence</a></li>
          {{-- @endcan --}}
          <li class="nav-item"><a class="nav-link " href="#families" data-toggle="tab">Families</a></li>
          <li class="nav-item"><a class="nav-link" href="#leave" data-toggle="tab">Leave</a></li>
        </ul>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content">
          <div class="active tab-pane fade show" id="details">
            <dl>
              <div class="row">
                <div class="col-9">
                  <dt>BPJS Ketenagakerjaan</dt>
                  <dd>{{ $employee->bpjs_kes_number }}</dd>
                </div>
                <div class="col-3 d-flex justify-content-end align-items-center">
                  @can('update', $employee)    
                    <a href="{{ route('my-data.edit') }}" class="btn bg-teal">Edit Data</a>
                    @endcan
                </div>
              </div>
              <dt>BPJS Kesehatan</dt>
              <dd>{{ $employee->bpjs_tk_number }}</dd>
              <dt>First Join Date</dt>
              <dd>{{ date_format($employee->first_join, "d F Y") }}</dd>
              <dt>Last Contract Start Date</dt>
              <dd>{{ date_format($employee->last_contract_start, "d F Y") }}</dd>
              <div class="row">
                <div class="col-md-6">
                  <dt>Last Contract End Date</dt>
                  <dd>{{ date_format($employee->last_contract_end, "d F Y") }}</dd>
                </div>
                @isset($remaining_contract) 
                <div class="col-md-6">
                  <dt>Remaining days</dt>
                  <dd>{{ $remaining_contract }} days left</dd>
                </div>
                @endisset
              </div>
              <div class="row">
                <div class="col-md-6">
                  <dt>Birth Place and Date</dt>
                  <dd>{{ $employee->birth_place.', '.date_format($employee->birth_date, "d F Y") }}</dd>
                </div>
                <div class="col-md-6">
                  <dt>Age</dt>
                  <dd>{{ $employee->birth_date->age }} years old</dd>
                </div>
              </div>
              <dt>Tax Status</dt>
              <dd>{{ $employee->tax_status->name }}</dd>
              <dt>Address on KTP</dt>
              <dd>{{ $employee->address_on_id }}</dd>
              <dt>Blood Type</dt>
              <dd>{{ $employee->blood_type }}</dd>
            </dl>
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane fade" id="residence">
            @if (isset($employee->residence))
            <dl>
              <div class="row">
                <div class="col-8">
                  <dt>Residence Address</dt>
                  <dd>{{ $employee->residence->address }}</dd>
                </div>
                <div class="col-4">
                  <div class="d-flex justify-content-around align-items-center">
                    @can('update', [$employee->residence, $employee])
                    <a href="{{ route('my-data.edit-residence') }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
                    @endcan
                    @can('delete', [$employee->residence, $employee])
                    <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $employee->residence->id }}" theme="danger"/>
                    <form method="post" action="{{ route('my-data.destroy-residence') }}">
                      <x-adminlte-modal id="modalDelete{{ $employee->residence->id }}" title="Delete Employee Residence" theme="teal"
                          icon="fas fa-bolt" size='lg' disable-animations>
                          Are you sure you want to delete {{ $employee->name }} residence address?
                            @csrf @method('delete')
                            <x-slot name="footerSlot">
                              <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
                              <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
                          </x-slot>
                      </x-adminlte-modal>
                      </form>
                    @endcan
                  </div>
                </div>
              </div>
            </dl>
            @else
            <div class="d-flex justify-content-center">
              @can('create', [App\Models\ResidenceAddress::class, $employee])    
              <a href="{{ route('my-data.create-residence') }}" class="btn bg-teal">Add Residence Address</a>
              @else
              <dl>
                <dt>Not authorized</dt>
              </dl>
              @endcan
              </div>
            @endif
            
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane fade" id="families">
            @if ($employee->families->isNotEmpty())
            <div class="d-flex justify-content-end mb-3">
              @can('create', [App\Models\Family::class, $employee])    
                <a href="{{ route('my-data.create-family') }}" class="btn bg-teal">Add Family Members</a>
              @endcan
            </div>
            <table class="table mt-3">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Relation</th>
                  <th class="col-4 text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($employee->families as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->name }}</td>
                  <td>{{ Str::headline($item->relationship->name) }}</td>
                  <td>
                    <div class="d-flex justify-content-around align-items-center">
                      <a href="{{ route('my-data.show-family', ['family' => $item]) }}" class="btn bg-info"><i class="fas fa-info-circle"></i></a>
                      @can('update', $item)
                      <a href="{{ route('my-data.edit-family', ['family' => $item]) }}" class="btn bg-warning"><i class="fas fa-edit"></i></a>
                      @endcan
                      @can('delete', $item)
                      <x-adminlte-button icon="fas fa-trash" data-toggle="modal" data-target="#modalDelete{{ $item->id }}" theme="danger"/>
                      <form method="post" action="{{ route('my-data.destroy-family', ['family' => $item]) }}">
                        <x-adminlte-modal id="modalDelete{{ $item->id }}" title="Delete Family Member" theme="teal"
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
            <div class="d-flex justify-content-center">
              @can('create', [App\Models\Family::class, $employee])    
              <a href="{{ route('my-data.create-family') }}" class="btn bg-teal">Add Family Members</a>
              @else
              <dl>
                <dt>Not authorized</dt>
              </dl>
              @endcan
            </div>
            @endif
            
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane fade" id="leave">
            @isset ($employee->leave)
            <dl>
              <dt>Annual</dt>
              <dd>{{ $employee->leave->annual }}</dd>
              <dt>Day of Payment</dt>
              <dd>{{ $employee->leave->dp }}</dd>
              <dt>Extra Off</dt>
              <dd>{{ $employee->leave->extra_off }}</dd>
            </dl>
            @else
            <div class="d-flex justify-content-center">
              <dl>
                <dt>Please Contact your head of department or HRD Staff</dt>
              </dl>
            </div>
            @endisset
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div><!-- /.card-body -->
    </div>
  </div>
</div>
@else
<x-adminlte-card theme="teal" theme-mode="outline">
  <dl>
    <dd>Your data is empty</dd>
  </dl>
  <div class="d-flex justify-content-center">
    @can('create', App\Models\Employee::class)    
      <a href="{{ route('my-data.create') }}" class="btn bg-teal">Fill Data</a>
    @endcan
  </div>
</x-adminlte-card>
@endisset
@endsection