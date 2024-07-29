<?php

namespace App\Http\Controllers;

use App\PrecioMolecula;
use App\Logistica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Molecula;

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

    public function molecula1()
    {
        $molecula1Records = DB::table('molecula1')->get();

        $molecula2Records = Molecula::all();
        $totalPendiente = $molecula2Records->where('estatus', 'pendiente')->sum('total');

        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "molecula1";
        $data['molecula1Records'] = $molecula1Records;
        $data['totalPendiente'] = $totalPendiente;

        return view('moleculas.molecula1', $data);
    }

    public function migrateLogisticaToMolecula1()
    {
        
        $rateModel = PrecioMolecula::latest()->first();

        if (!$rateModel) {
            return redirect()->route('moleculas.index')->with('error', 'No hay un rate vigente en la tabla de PreciosMolecula.');
        }

        $rate = $rateModel->precio;

        $logisticaRecords = Logistica::whereNotNull('cliente')
                                     ->whereNotNull('precio')
                                     ->get();

        foreach ($logisticaRecords as $record) {
            $total = $record->litros * $rate;

            DB::table('molecula1')->insert([
                'bol_number' => $record->bol,
                'litros' => $record->litros,
                'rate' => $rate,
                'total' => $total,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('moleculas.molecula1')->with('success', 'Datos migrados a molecula 1 con éxito.');
    }

    public function calculateBestOptions(Request $request)
    {
        $budget = $request->input('budget');

        // Obtener todas las facturas pendientes
        $pendingInvoices = DB::table('molecula1')->where('estatus', 'pendiente')->get();

        // Implementación del algoritmo de "knapsack"
        $n = $pendingInvoices->count();
        $bestCombination = collect();
        $bestTotal = 0;

        for ($i = 0; $i < (1 << $n); $i++) {
        $currentTotal = 0;
        $currentCombination = collect();
        
        for ($j = 0; $j < $n; $j++) {
            if ($i & (1 << $j)) {
                $currentTotal += $pendingInvoices[$j]->total;
                $currentCombination->push($pendingInvoices[$j]);
            }
        }
        
        if ($currentTotal <= $budget && $currentTotal > $bestTotal) {
            $bestTotal = $currentTotal;
            $bestCombination = $currentCombination;
        }
        }

        $html = view('moleculas.best_options', compact('bestCombination', 'bestTotal'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

}