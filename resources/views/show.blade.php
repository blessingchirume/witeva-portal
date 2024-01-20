@extends('employee.app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Employee &raquo; Edit</h1>
                </div><!-- /.col -->
            </div>
        </div>
        <div class="container-fluid">
            @include('partials.alert')
        </div>
    </div>

    <div class="card">
        <form id="update-employee-form" method="post" action="{{route('employee.update', $employee)}}">
            <div class="card-header">
                <a href="{{ route('employee.index') }}" type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    back
                </a>
            </div>
            <div class="card-body">

                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input class="form-control" id="name" name="name" type="text" placeholder="First Name" value="{{$employee->name}}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input class="form-control" id="last_name" name="surname" type="text" placeholder="Last Name" value="{{$employee->surname}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="useer_role">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text" placeholder="Phone" value="{{$employee->phone}}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="{{$employee->email}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Designation</label>
                            <input class="form-control" id="role" name="role" type="text" placeholder="role" value="{{ $employee->designation }}" readonly required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group pull-left">
                    <button type="button" class="btn btn-secondary float-right mr-2" data-bs-toggle="modal" data-bs-target="#update-employee-modal">Update Details</button>
                    <button type="button" class="btn btn-danger  float-right mr-2" data-bs-toggle="modal" data-bs-target="#delete-employee-modal">Delete Employee</button>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="modal fade" id="update-employee-modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">update Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="alert alert-danger"><strong>Warning</strong> You are about to update <strong>{{ $employee->name }} {{$employee->surname}}</strong>, this action cannot be reversed</div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" onclick="$('#update-employee-form').submit()">update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-employee-modal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <form method="post" id="delete-employee-form" action="{{ route('employee.delete', $employee) }}" enctype="">
                        {{ csrf_field() }}
                        <div class="alert alert-danger"><strong>Warning</strong> You are about to remove <strong>{{ $employee->name }} {{$employee->surname}}</strong>, this action cannot be reversed</div>
                    </form>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" onclick="$('#delete-employee-form').submit()">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection