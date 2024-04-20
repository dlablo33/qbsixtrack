@extends('layouts.master')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
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
                            <h3 class="card-title">Create Customer</h3>
                        </div>

                        <form class="card-body" style="margin: 10px" action="{{ route('customers.store') }}" method="POST">
                            @csrf
                            @include('qb-flash-message')

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Customer Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter Customer Name" required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">Country</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="country" id="country" placeholder="Enter Country">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-sm-3 col-form-label">City</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="Enter City">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="state" class="col-sm-3 col-form-label">State</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="state" id="state" placeholder="Enter State">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip" class="col-sm-3 col-form-label">ZIP Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="Enter ZIP Code">
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
