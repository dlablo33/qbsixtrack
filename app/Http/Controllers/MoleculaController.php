<?php

namespace App\Http\Controllers;

use App\PrecioMolecula;
use App\Molecula;
use App\Logistica;
use App\Bluewi;
use Illuminate\Http\Request;
use App\customer;
use App\product;
use Ramsey\Uuid\Provider\TimeProviderInterface;
use Carbon\Carbon;

class MoleculaController extends Controller
{
    public function index()
    {
        $moleculas = PrecioMolecula::orderBy('updated_at', 'DESC')->get();
        $clientes = Customer::all();
        $productos = Product::all();
        $logistica = Logistica::all();

        $data = [];
        $data['menu'] = "moleculas";
        $data['menu_sub'] = "index";
        $data['moleculas'] = $moleculas;
        $data['clientes'] = $clientes;
        $data['productos'] = $productos;
        $data['logistica'] = $logistica;
        
        return view('moleculas.index', $data);
    }

    public function create()
    {
        $clientes = Customer::all();
        $productos = Product::all();
        $logistica = Logistica::all();

        $data = [];
        $data['menu'] = "moleculas";
        $data['menu_sub'] = "create";
        $data['clientes'] = $clientes;
        $data['productos'] = $productos;
        $data['logistica'] = $logistica;

        return view('moleculas.create', $data);
    }


    public function transferLogisticaToMolecula()
    {
        
        $data['menu'] = "moleculas";
        $data['menu_sub'] = "transferLogisticaToMolecula";

        ini_set('max_execution_time', 600);

        // Obtener el rate más reciente de la tabla PreciosMolecula
        $rateModel = PrecioMolecula::latest()->first();

        if (!$rateModel) {
            return redirect()->route('moleculas.index')->with('error', 'No hay un rate vigente en la tabla de PreciosMolecula.');
        }

        $rate = $rateModel->rate;

        // Obtener todos los registros de Logistica que tienen un BOL
        $logisticaRecords = Logistica::whereNotNull('bol_number')->get();

        foreach ($logisticaRecords as $record) {
            // Verificar si el registro ya existe en PreciosMolecula
            $exists = PrecioMolecula::where('bol_number', $record->bol_number)
                                    ->where('molecula', 1) // Puedes ajustar esto si hay una lógica diferente para moleculas
                                    ->exists();

            if (!$exists) {
                $total = $record->litros * $rate;

                PrecioMolecula::create([
                    'molecula' => 1, 
                    'bol' => $record->bol_,
                    'litros' => $record->litros,
                    'rate' => $rate,
                    'total' => $total,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('moleculas.molecula1')->with('success', 'Datos transferidos con éxito');
    }

 
}


