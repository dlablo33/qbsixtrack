@extends('layouts.master')

@section('content')


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
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mr-auto">Customer List</h3>

                            <div class="d-flex">
                                <a href="/customers/syncCustomers" class="btn btn-danger mr-2">Sync Customers</a>
                                <a href="/customers/create" class="btn btn-info">Add Customer</a>
                            </div>

                        </div>
                        <div class="card-body">
                            @include('qb-flash-message')
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <table  id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>ZIP</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->country }}</td>
                                        <td>{{ $customer->city }}</td>
                                        <td>{{ $customer->state }}</td>
                                        <td>{{ $customer->zip }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <a href="{{ route('customers.edit', ['id' => $customer->id]) }}" class="btn btn-success">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
{{--                                            <form action="{{ route('customers.destroy', ['id' => $customer->id]) }}" method="POST" style="display: inline;">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}

{{--                                                <!-- Use confirmation dialog for user confirmation -->--}}
{{--                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">--}}
{{--                                                    <i class="fas fa-trash-alt"></i> Delete--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /.content -->

@endsection
