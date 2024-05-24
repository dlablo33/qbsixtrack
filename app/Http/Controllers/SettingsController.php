<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // Método para mostrar todos los usuarios
    public function index()
    {
        $usuarios = Settings::all();

        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['settings'] = $usuarios;

        return view('cardknox.index', $data);

    }

    public function cardknoxIndex() // Create the new method
    {
        $usuarios = Settings::all();

        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['settings'] = $usuarios;

        return view('cardknox.index', $data);
    }

    public function cardknoxEdit ($id)
    {
        $Settings = Settings::findOrFail($id);

        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['settings'] = $Settings;
        return view('cardknox.edit', $data);
    }

    public function update(Request $request,$id) {
        $Settings = Settings::findOrFail($id);
        
        // Actualiza los campos del cliente con los valores de la solicitud
        $Settings->name = $request->input('name');
        $Settings->email = $request->input('email');
        $Settings->tipo_usuario = $request->input('tipo_usuario');

        $Settings->save();
    
        // Redirige a la página de índice de clientes u otra página según necesites
        return redirect()->route('cardknox.index')->with('success', 'Cliente actualizado correctamente');

    }
}