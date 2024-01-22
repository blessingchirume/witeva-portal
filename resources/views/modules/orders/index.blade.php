@extends('app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Order &raquo; Listing</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        <div class="container-fluid">
            @include('partials.alert')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a href="{{ route('user.create') }}" type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                Generate
            </a>
        </div>
        <div class="card-body">
            <table id="employeeTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Reference Number</th>
                        <th>Payment Status</th>
                        <th>Transit Status</th>
                        <th>Delivery status</th>
                        <th>Delivery Date</th>
                        <th>Approval Status</th>
                        <th>Customer</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $user)
                    <tr>
                        <td>{{$user->order_number}}</td>
                        <td>{{$user->order_ref_number}}</td>
                        <td>{{$user->payment_status}}</td>
                        <td>{{$user->customer_delivery_status}}</td>
                        <td>{{$user->admin_delivery_status}}</td>
                        <td>{{$user->delivery_date}}</td>
                        <td>{{$user->approval_status}}</td>
                        <td>{{$user->user}}</td>
                        <td>{{$user->created_at}}</td>
                        <td>{{$user->Updated_at}}</td>
                        <td>
                            <a href="">view</a>
                            <a href="">pdf</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection