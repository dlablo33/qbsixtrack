<?php

namespace App\Http\Controllers;

use App\PrecioMolecula;
use App\Logistica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MoleculaController extends Controller
{
    public function index()
    {
        $moleculas = PrecioMolecula::orderBy('updated_at', 'DESC')->get();

        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "";
        $data['moleculas'] = $moleculas;
        return view('moleculas.index', $data);
    }

    public function transferLogisticaToMolecula()
    {
        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "";

        $rateModel = PrecioMolecula::latest()->first();

        if (!$rateModel) {
            return redirect()->route('moleculas.index')->with('error', 'No hay un rate vigente en la tabla de PreciosMolecula.');
        }

        $rate = $rateModel->precio;
        $logisticaRecords = Logistica::whereNotNull('cliente')
                                     ->whereNotNull('precio')
                                     ->get();

        foreach ($logisticaRecords as $record) {
            $exists = PrecioMolecula::where('bol_number', $record->bol)
                                    ->exists();

            if (!$exists) {
                $total = $record->litros * $rate;

                PrecioMolecula::create([
                    'bol_number' => $record->bol,
                    'litros' => $record->litros,
                    'rate' => $rate,
                    'total' => $total,
                ]);
            }
        }

        return redirect()->route('moleculas.index')->with('success', 'Datos transferidos con éxito');
    }

    public function create()
    {
        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "create";

        return view('moleculas.create', $data);
    }

    public function store(Request $request)
    {
        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "";

        $request->validate([
            'molecula' => 'required|integer',
            'precio' => 'required',
        ]);

        PrecioMolecula::create([
            'molecula' => $request->molecula,
            'precio' => $request->precio,
            'usuario' => Auth::user()->name,
        ]);

        return redirect()->route('moleculas.index')->with('success', 'Precio de molécula añadido con éxito');
    }
}
