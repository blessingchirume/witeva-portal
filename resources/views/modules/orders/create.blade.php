@extends('app')

@section('content')
<section class="content">
<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Employee &raquo; Create</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">


                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        <div class="container-fluid">
            @include('partials.alert')
        </div>
    </div>

    <div class="card">
        <form method="post" action="{{route('user.store')}}">
            <div class="card-header">
                <a href="{{ route('user.index') }}" type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    back
                </a>
            </div>
            <div class="card-body">

                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input class="form-control" id="name" name="name" type="text" placeholder="First Name" value="{{old('name')}}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input class="form-control" id="last_name" name="surname" type="text" placeholder="Last Name" value="{{old('surname')}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="useer_role">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text" placeholder="Phone" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Designation</label>
                            <input class="form-control" id="role" name="designation" type="text" placeholder="role" value="{{ old('designation') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group pull-left">
                    <button type="submit" class="btn btn-secondary  float-right mr-2" data-bs-toggle="modal" data-bs-target="#delete-employee-modal">submit</button>
                    <button type="reset" class="btn btn-danger float-right mr-2">Reset form</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection