@extends('app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Item &raquo; Create</h1>
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
        <form method="post" action="{{route('item.store')}}">
            <div class="card-header">
                <a href="{{ route('item.index') }}" type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    back
                </a>
            </div>
            <div class="card-body">

                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <input class="form-control" id="last_name" name="item_description" type="text" placeholder="description" value="{{old('item_description')}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Price</label>
                            <input class="form-control" id="name" name="price" type="text" placeholder="price" value="{{old('price')}}" required>
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