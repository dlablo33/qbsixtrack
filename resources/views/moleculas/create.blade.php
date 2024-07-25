@extends('layouts.master')

@section('content')
<div>
    <h1>Añadir Precio de Molécula</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('moleculas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="molecula">Molécula</label>
            <input type="number" class="form-control" id="molecula" name="molecula" required>
        </div>
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="float" class="form-control" id="precio" name="precio" required>
        </div>
        <button type="submit" class="btn btn-primary">Añadir Precio</button>
    </form>
</div>
@endsection
