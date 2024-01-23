@extends('app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Payment &raquo; Listing</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        <div class="container-fluid">
            @include('partials.alert')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            
        </div>
        <div class="card-body">
            <table id="employeeTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Reference Number</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        {{--<th>Customer</th>
                        <th>Created</th>
                        <th>Updated</th>--}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $value)
                    <tr>
                        <td>{{$index}}</td>
                        <td>{{$value->order_number}}</td>
                        <td>{{$value->ref_number}}</td>
                        <td>{{$value->type}}</td>
                        <td>{{$value->amount}}</td>
                        <td>{{$value->payment_date}}</td>
                        {{--<td>{{$value->user_id}}</td>
                        <td>{{$value->created_at}}</td>
                        <td>{{$value->updated_at}}</td>--}}
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