@extends('app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Item &raquo; Listing</h1>
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
                        <th>Item Code</th>
                        <th>Item Desciption</th>
                        <th>price</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{$item->item_code}}</td>
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
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