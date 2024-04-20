@extends('layouts.master')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Horizontal Form -->
                    <div class="card card-info">
                        <div class="card-header" style="margin-bottom:5px">
                            <h3 class="card-title">Create Journal</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form style="margin: 10px" class="form-horizontal" action="{{route('journal.store')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @include('qb-flash-message')
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Select Date</label>
                                        <input type="text" class="form-control datepicker" name="payment_date" placeholder="Choose a date" required="" >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Choose PDF File</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="fileToUpload" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clone">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Item</label>
                                            <select class="form-control" required="" name="product_id[]">
                                                <option value="">Select..</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} - Type: {{$product->type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="number" class="form-control" name="price[]" placeholder="Amount" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Adjustment/Refund</label>
                                            <select class="form-control" required="" name="refund[]">
                                                <!--<option value="">Select..</option>-->
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p><a href="#" class="add btn btn-secondary btn-xs" rel=".clone">Add More Item</a></p>

                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-info">Submit</button>

                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
