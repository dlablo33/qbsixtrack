@extends('layouts.master')

@section('content')


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Marchant</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant List</li>
                    </ol>
                </div>

            </div>

        </div>

    </div>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Merchant List</h3>
                        </div>
                        <div class="card-body">
                            <!-- Marchant table goes here -->
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($marchants as $marchant)
                                    <tr>
                                        <td>{{ $marchant->name }}</td>
                                        <td>{{ $marchant->email }}</td>
                                        <td>
                                            <a href="{{ route('marchants.edit',  $marchant->id) }}" class="btn btn-success"><i class="nav-icon fas fa-edit"></i></a>
{{--                                            <form action="{{ route('marchants.destroy' ,$marchant->id) }}" method="POST" style="display:inline;">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this merchant?')">Delete</button>--}}
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

@endsection
