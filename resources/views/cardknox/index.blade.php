@extends('layouts.master')

@section('content')
    <div class="container">
        <h1>Listado de Usuarios</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->email }}</td>
                        <td>{{ $setting->tipo_usuario }}</td>
                        <td>
                            <a href="{{ route('cardknox.edit', $setting->id) }}" class="btn btn-primary btn-sm">Editar</a> <!-- Botón Editar -->
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection



