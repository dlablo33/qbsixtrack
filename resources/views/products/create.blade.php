@extends('layouts.master')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-info">
                        <div class="card-header text-center">
                            <h3 class="card-title">Create Product</h3>
                        </div>

                        <form class="card-body" style="margin: 10px" action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @include('qb-flash-message')

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Item Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Item Name" required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Unit Pice</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="unit_price" id="unit_price" placeholder="Unit Price" required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="type" class="col-sm-3 col-form-label"> Type</label>
                                <div class="col-sm-9">
                                    <select class="form-control" required="" id="type" name="type">
                                        <option value="Service">Service</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- /.content -->

@endsection
