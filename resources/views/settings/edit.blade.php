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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Quickbooks Settings Update</h3>
                    </div>

                    <form class="form-horizontal" action="{{ $settings ? route('settings.store', ['id' => $settings->id]) : route('settings.store') }}" method="POST">                        @csrf
                        @method('POST')
                        @include('flash-message')

                        <div class="card-body">
                            <div class="form-group row">
                                <label for="ClientID" class="col-sm-3 col-form-label">Client ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ClientID" name="ClientID" value="{{ $settings ? $settings->ClientID : null }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ClientSecret" class="col-sm-3 col-form-label">Client Secret</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ClientSecret" name="ClientSecret" value="{{ $settings ? $settings->ClientSecret : null }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="RedirectURI" class="col-sm-3 col-form-label">Redirect URL</label>
                                <div class="col-sm-9">
                                    @if($settings && $settings->RedirectURI)
                                        <input type="text" class="form-control" id="RedirectURI" name='RedirectURI' value="{{ $settings->RedirectURI }}" readonly>
                                    @else
                                        <input type="text" class="form-control" id="RedirectURI" name='RedirectURI' value="{{ route('qb.callback', ['user' => auth()->user()->id]) }}" readonly>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="scope" class="col-sm-3 col-form-label">Scope</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="scope" name='scope' value="{{ $settings ? $settings->scope : null }}" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="baseUrl" class="col-sm-3 col-form-label">Base URL</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="baseUrl" name='baseUrl' value="{{ $settings ? $settings->baseUrl : null }}" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="QBORealmID" class="col-sm-3 col-form-label">QBO Realm ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="QBORealmID" name='QBORealmID' value="{{ $settings ? $settings->QBORealmID : null }}" >
                                </div>
                            </div>

{{--                            <div class="form-group row">--}}
{{--                                <label for="transaction_key" class="col-sm-3 col-form-label">Transaction Key</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <input type="text" class="form-control" id="transaction_key" name='transaction_key' value="{{ $settings ? $settings->transaction_key : null }}" >--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group row">--}}
{{--                                <label for="ifield_key" class="col-sm-3 col-form-label">Ifield Key</label>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    <input type="text" class="form-control" id="ifield_key" name='ifield_key' value="{{ $settings ? $settings->QBORealmID : null }}" >--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group row">
                                <label for="others" class="col-sm-3 col-form-label">Others (Optional)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="others" name="others" value="{{ $settings ? $settings->others : null }}">
                                </div>
                            </div>

                            <!-- Add more fields as needed -->

                        </div>

                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.content -->

@endsection
