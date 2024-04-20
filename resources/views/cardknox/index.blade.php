@extends('layouts.master')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Cardknox Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Cardknox</li>
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
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-md-6">
                          <div class="card">
                              <div class="card-header">
                                  <h3 class="card-title">Cardknox Settings</h3>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                  @if($settings)
                                      <p><strong>Transaction Key:</strong> {{ $settings['transaction_key'] }}</p>
                                      <p><strong>Ifields Key:</strong> {{ $settings['ifield_key'] }}</p>

                                      <div class="text-center mt-4">
                                          @if(isset($settings['id']))
                                              <a href="{{ route('cardknox.edit',['id' => $settings['id'] ])}}" class="btn btn-success ml-2">Edit</a>
                                          @endif
                                      </div>
                                  @else


                                      <div class="text-center mt-4">
                                          <p>No cardknox settings data available.</p>
                                          <a href="{{ route('cardknox.edit')}}" class="btn btn-success ml-2">Add</a>


                                      </div>

                                  @endif
                              </div>
                              <!-- /.card-body -->
                          </div>
                      </div>
                  </div>
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
