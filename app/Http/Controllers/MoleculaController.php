<?php

namespace App\Http\Controllers;

use App\Destino;
use App\PrecioMolecula;
use App\Logistica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Molecula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Tarifa;
use App\Molecula2;
use App\Customer;
use App\PaymentBatch;
use App\PaymentBatchItem;


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
        // Obtener el último rate vigente
        $rateModel = PrecioMolecula::latest()->first();
    
        if (!$rateModel) {
            return redirect()->route('moleculas.index')->with('error', 'No hay un rate vigente en la tabla de PreciosMolecula.');
        }
    
        $rate = $rateModel->precio;
    
        // Obtener los registros de logistica
        $logisticaRecords = Logistica::whereNotNull('cliente')
                                     ->whereNotNull('precio')
                                     ->get();
    
        // Transacción para asegurar la atomicidad
        DB::transaction(function () use ($logisticaRecords, $rate) {
            foreach ($logisticaRecords as $record) {
                try {
                    DB::table('molecula1')
                        ->updateOrInsert(
                            ['bol_number' => $record->bol, 'rate' => $rate],
                            [
                                'litros' => $record->litros,
                                'total' => $record->litros * $rate,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                } catch (\Illuminate\Database\QueryException $e) {
                    // Capturamos la excepción si se produce un duplicado
                    if ($e->getCode() == 23000) { // Código 23000 es para violaciones de integridad de clave única
                        // Ignoramos el duplicado y continuamos con el siguiente registro
                        \Log::warning('Duplicado encontrado: ' . $record->bol . ' - ' . $rate);
                    } else {
                        // Si el error no es por duplicado, relanzamos la excepción
                        throw $e;
                    }
                }
            }
        });
    
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

        $data = [];
        $data['menu'] = "molecula";
        $data['menu_sub'] = "";
        $data['bestCombination'] = $bestCombination;
        $data['bestTotal'] = $bestTotal;
        $data['budget'] = $budget;

        $html = view('moleculas.best_options', $data)->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    public function processPaymentBatch(Request $request)
    {
        $selectedRecords = $request->input('selected_records');
    
        // Valida si se seleccionaron registros
        if (empty($selectedRecords)) {
            return redirect()->back()->with('error', 'No se han seleccionado registros.');
        }
    
        // Encuentra los registros seleccionados en la base de datos
        $records = Molecula::whereIn('id', $selectedRecords)->get();
    
        // Verifica si hay registros para procesar
        if ($records->isEmpty()) {
            return redirect()->back()->with('error', 'No se encontraron registros válidos.');
        }
    
        // Genera un número de lote único para batch_number
        $batchNumber = 'BATCH-' . strtoupper(uniqid());
    
        // Calcular el monto total para el lote
        $totalAmount = $records->sum('total'); // Asegúrate de que 'total' sea un campo en tu modelo Molecula
    
        // Crear un nuevo registro en la tabla payment_batches
        $paymentBatch = PaymentBatch::create([
            'batch_number' => $batchNumber,
            'total_amount' => $totalAmount,
        ]);
    
        // Procesar cada registro y vincularlo al lote de pagos en la tabla payment_batch_items
        $records->each(function ($record) use ($paymentBatch) {
            // Actualizar el estatus de la factura a "pagado"
            $record->estatus = 'pagado';
            $record->save();
    
            // Crear un nuevo registro en la tabla payment_batch_items
            PaymentBatchItem::create([
                'batch_id' => $paymentBatch->id,
                'bol_number' => $record->bol_number, // Suponiendo que bol_number existe en Molecula
                'amount' => $record->total, // Asegúrate de que 'total' sea un campo en tu modelo Molecula
            ]);
        });
    
        // Genera el PDF con los registros seleccionados
        $pdf = PDF::loadView('moleculas.pdf', ['records' => $records]);
    
        // Guarda el PDF en el servidor
        $pdfPath = 'pdfs/pago_' . $paymentBatch->id . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
    
        // Descarga automática del PDF
        return $pdf->download('pago_' . $paymentBatch->id . '.pdf');

    }

    // =======================================================================================================================================

    public function molecula2()
    {
            // Obtiene todos los registros de la tabla molecula2
            $records = Molecula2::all();
            $destinos = Destino::all();
            $clientes = Customer::all();

            $data = [];
            $data['menu'] = "molecula";
            $data['menu_sub'] = "";
            $data['records'] = $records;
            $data['destinos'] = $destinos;
            $data['clientes'] = $clientes;

            // Retorna la vista molecula2 con los registros obtenidos
            return view('moleculas.molecula2', $data);
    }
    
    public function storeMolecula2(Request $request)
    {
            // Valida los datos de entrada si es necesario
            $data = $request->validate([
                'bol' => 'required|numeric',
                'order_number' => 'required|integer',
                'semana' => 'required|integer',
                'fecha' => 'required|date',
                'linea' => 'required|string|max:50',
                'no_pipa' => 'nullable|string',
                'cliente' => 'required|string|max:100',
                'destino' => 'required|string|max:100',
                'transportista_id' => 'required|integer',
                'destino_id' => 'required|integer',
                'status' => 'required|string|max:50',
                'cruce' => 'required|string|max:50',
                'litros' => 'required|numeric',
                'precio' => 'required|numeric',
                'fecha_salida' => 'nullable|date',
                'fecha_entrega' => 'nullable|date',
                'fecha_descarga' => 'nullable|date',
                'pedimento' => 'nullable|string',
            ]);
    
            // Crea un nuevo registro en la tabla molecula2 con los datos validados
            Molecula2::create($data);
    
            // Redirige de vuelta a la vista de molecula2 con un mensaje de éxito
            return redirect()->route('moleculas.molecula2')->with('success', 'Registro guardado exitosamente.');
    }
    
    public function processMolecula2(Request $request)
        {
            // Obtiene los IDs de los registros seleccionados
            $selectedRecords = $request->input('selected_records');
    
            // Procesa cada registro seleccionado (ejemplo: cambia el estado a "pagado" o calcula el total a pagar)
            foreach ($selectedRecords as $recordId) {
                $record = Molecula2::find($recordId);
    
                if ($record) {
                    // Lógica para calcular el precio o realizar la acción deseada
                    $record->status = 'pagado'; // Ejemplo: actualizar el estado a pagado
                    $record->save();
                }
            }
    
            // Redirige de vuelta a la vista de molecula2 con un mensaje de éxito
            return redirect()->route('moleculas.molecula2')->with('success', 'Proceso completado.');
    }

    public function migrateToMolecula2()
        {
            // Obtener los registros de la tabla Logistica (asumiendo que Logistica es la tabla origen)
            $logisticaRecords = Logistica::whereNotNull('transportista_id')
                ->whereNotNull('destino_id')
                ->where('destino_id', '!=', 'FOB')
                ->get();
        
            // Recorrer los registros para migrarlos a Molecula2
            foreach ($logisticaRecords as $logistica) {
                // Obtener el precio correspondiente del transporte desde la tabla de tarifas
                $tarifa = Tarifa::where('transportista_id', $logistica->transportista_id)
                    ->where('destino_id', $logistica->destino_id)
                    ->first();
        
                // Si se encuentra una tarifa correspondiente, crear un nuevo registro en Molecula2
                if ($tarifa) {
                    Molecula2::create([
                        'bol' => $logistica->bol_number,
                        'order_number' => $logistica->order_number,
                        'semana' => $logistica->semana,
                        'fecha' => $logistica->fecha,
                        'linea' => $logistica->linea,
                        'no_pipa' => $logistica->no_pipa,
                        'cliente' => $logistica->cliente,
                        'destino' => $logistica->destino,
                        'transportista_id' => $logistica->transportista_id,
                        'destino_id' => $logistica->destino_id,
                        'status' => 'pendiente',
                        'cruce' => $logistica->cruce,
                        'litros' => $logistica->litros,
                        'precio' => $tarifa->moneda === 'MXN' ? $tarifa->tar_mex : $tarifa->tar_usa,
                        'fecha_salida' => $logistica->fecha_salida,
                        'fecha_entrega' => $logistica->fecha_entrega,
                        'fecha_descarga' => $logistica->fecha_descarga,
                        'pedimento' => $logistica->pedimento,
                    ]);
                }
            }
        
            // Redirigir a la vista de molecula2 con un mensaje de éxito
            return redirect()->route('moleculas.molecula2')->with('success', 'Datos migrados exitosamente.');
    }
 
    public function migrateDataForMolecula2()
    {
        // Obtener los registros de la tabla Logistica donde los campos necesarios no son nulos
        $logisticaRecords = Logistica::whereNotNull('transportista_id')
            ->whereNotNull('destino_id')
            ->get();
    
        // Revisar si existen registros en la consulta
        if ($logisticaRecords->isEmpty()) {
            return redirect()->route('moleculas.molecula2')->with('error', 'No se encontraron registros para migrar.');
        }
    
        // Transacción para asegurar la atomicidad
        DB::transaction(function () use ($logisticaRecords) {
            foreach ($logisticaRecords as $logistica) {
                // Obtener la tarifa correspondiente del transporte desde la tabla de tarifas
                $tarifa = Tarifa::where('transportista_id', $logistica->transportista_id)
                    ->where('destino_id', $logistica->destino_id)
                    ->first();
    
                // Verificar si la tarifa existe antes de proceder
                if ($tarifa) {
                    // Verificar si el registro ya existe en la tabla Molecula2
                    $existingRecord = Molecula2::where('bol', $logistica->bol)
                        ->where('order_number', $logistica->order_number)
                        ->where('destino_id', $logistica->destino_id)
                        ->first();
    
                    if (!$existingRecord) {
                        // Crear el nuevo registro en la tabla Molecula2
                        Molecula2::create([
                            'bol' => $logistica->bol,
                            'order_number' => $logistica->order_number,
                            'semana' => $logistica->semana,
                            'fecha' => $logistica->fecha,
                            'linea' => $logistica->linea,
                            'no_pipa' => $logistica->no_pipa,
                            'cliente' => $logistica->cliente,
                            'destino' => $logistica->destino, // Asegúrate de que esto esté correcto según tu esquema
                            'transportista_id' => $logistica->transportista_id,
                            'destino_id' => $logistica->destino_id,
                            'status' => 'pendiente', // Puedes ajustar este valor si es necesario
                            'cruce' => $logistica->cruce,
                            'litros' => $logistica->litros,
                            'precio' => $tarifa->iva,
                            'fecha_salida' => $logistica->fecha_salida,
                            'fecha_entrega' => $logistica->fecha_entrega,
                            'fecha_descarga' => $logistica->fecha_descarga,
                            'pedimento' => $logistica->pedimento,
                        ]);
                    }
                }
            }
        });
    
        // Redirigir a la vista de molecula2 con un mensaje de éxito
        return redirect()->route('moleculas.molecula2')->with('success', 'Datos migrados exitosamente.');
    }
    

} 