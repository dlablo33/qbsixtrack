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
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-md-6">
                          <div class="card">
                              <div class="card-header">
                                  <h3 class="card-title">Settings</h3>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                  @if($settings)
                                      <p><strong>Sandbox/Production:</strong> {{ $settings['baseUrl'] }}</p>
                                      <p><strong>Secret Id:</strong> {{ $settings['ClientID'] }}</p>
                                      <p><strong>Token:</strong> {{ $settings['ClientSecret'] }}</p>
                                      <p><strong>Redirect URL:</strong> {{ $settings['RedirectURI'] }}</p>
                                      <p><strong>QB RealmID:</strong> {{ $settings['QBORealmID'] }}</p>
{{--                                      <p><strong>Transaction Key:</strong> {{ $settings['transaction_key'] }}</p>--}}
{{--                                      <p><strong>Ifields Key:</strong> {{ $settings['ifield_key'] }}</p>--}}
                                      <p><strong>Others:</strong> {{ $settings['others'] }}</p>
                                      <div class="text-center mt-4">
                                          <a href="{{$qbauth}}" class="btn btn-info" target="_blank">Authenticate Quickbook</a>
                                          @if(isset($settings['id']))
                                              <a href="{{ route('settings.edit',['id' => $settings['id'] ])}}" class="btn btn-success ml-2">Edit</a>
                                          @endif
                                      </div>
                                  @else


                                      <div class="text-center mt-4">
                                          <p>No settings data available.</p>
                                          <a href="{{$qbauth}}" class="btn btn-info" target="_blank">Authenticate Quickbook</a>
                                          <a href="{{ route('settings.edit')}}" class="btn btn-success ml-2">Add</a>


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
