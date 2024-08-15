<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoCambio;

class TipoCambioController extends Controller
{
    public function index()
    {
        $tiposCambio = TipoCambio::all();

        $data = [];
        $data['menu'] = 'tiposCambio';
        $data['submenu'] = '';
        $data['tiposCambio'] = $tiposCambio;
        
        return view('tipocambio.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['menu'] = 'tiposCambio';
        $data['submenu'] = 'crearTipoCambio';
        return view('tipocambio.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_cambio_mxn' => 'required|numeric',
            'tipo_cambio_usd' => 'required|numeric',
            'tipo_conversion' => 'required|in:mxn_to_usd,usd_to_mxn',
        ]);

        TipoCambio::create([
            'fecha' => now()->toDateString(), // Fecha del día actual
            'tipo_cambio_mxn' => $request->input('tipo_cambio_mxn'),
            'tipo_cambio_usd' => $request->input('tipo_cambio_usd'),
            'tipo_conversion' => $request->input('tipo_conversion'),
        ]);

        return redirect()->route('tipocambio.index')->with('success', 'Tipo de cambio registrado exitosamente.');
    }
}
