@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Editar Usuario</h1>
        <form action="{{ route('cardknox.update', $settings->id) }}" method="POST">
  @csrf
  <div class="form-group">
    <label for="name">Nombre:</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ $settings->name }}">
  </div>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ $settings->email }}">
  </div>
  <div class="form-group">
    <label for="tipo_usuario">Tipo de Usuario:</label>
    <select name="tipo_usuario" id="tipo_usuario" class="form-control">
      <option value="1" {{ $settings->tipo_usuario === '1' ? 'selected' : '' }}>Desarrollador</option>
      <option value="2" {{ $settings->tipo_usuario === '2' ? 'selected' : '' }}>Administrador</option>
      <option value="3" {{ $settings->tipo_usuario === '3' ? 'selected' : '' }}>Lector</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
  <a href="{{ url()->previous() }}" class="btn btn-primary">Regresar</a>
</form>

    </div>
@endsection
