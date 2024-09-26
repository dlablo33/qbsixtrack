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
use App\Molecula3;
use App\Customer;
use App\PaymentBatch;
use App\PaymentBatchItem;
use App\invoice;


class MoleculaController extends Controller
{

    // MOLECULA 1 
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

    // MOLECULA 2

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
                'moneda' => 'nullable|string'
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
                            'moneda' => $tarifa->moneda,
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
    
    public function procesarPagos(Request $request)
{
    $clientes = Customer::all();
    $destinos = Destino::all();
    $selectedRecords = $request->input('selected_records', []);
    $codekas = $request->input('codeka', []);

    if (!empty($selectedRecords)) {
        // Cargar los registros junto con sus relaciones de cliente y destino
        $records = Molecula2::whereIn('id', $selectedRecords)
                            ->with(['cliente', 'destino']) // Cargar relaciones
                            ->get();

        // Actualizar el estatus a 'pagado' para los registros seleccionados
        foreach ($records as $record) {
            $record->status = 'pagado';
            $record->codeka = $codekas[$record->id] ?? null;
            $record->save();
        }

        // Generar y descargar el PDF si se procesaron registros
        if ($records->isNotEmpty()) {
            $data = ['records' => $records, 'clientes' => $clientes, 'destinos' => $destinos];
            $pdf = PDF::loadView('moleculas.registro_compras_pdf', $data);
            $pdfFile = 'moleculas_registro_compras.pdf';

            // Guarda el PDF en el servidor temporalmente antes de descargar
            $pdf->save(storage_path('app/public/' . $pdfFile));

            return response()->json([
                'url' => url('storage/' . $pdfFile)
            ]);
        }
    }

    return response()->json(['error' => 'No records selected'], 400);
}

// =============================================================================================================================================

//MOLECULA 3

    public function molecula3()
    {
    $bols = Molecula3::all(); // Obtén los registros de Molecula3

    $data = [
        'menu' => 'bols',
        'menu_sub' => '',
        'bols' => $bols
    ];

    // Retorna la vista con los datos
    return view('moleculas.molecula3', $data);
    }

    public function migrarBoLs()
{
    $bols = Logistica::all();

    // Obtener los precios más recientes para molecula1 y molecula3
    $precioMolecula1 = PrecioMolecula::where('molecula', 1)->orderBy('created_at', 'desc')->first()->precio;
    $precioMolecula3 = PrecioMolecula::where('molecula', 2)->orderBy('created_at', 'desc')->first()->precio;

    foreach ($bols as $bol) {
        // Asumimos que hay un campo en el BoL que contiene la cantidad de litros
        $litros = $bol->litros;

        // Convertir los litros a barriles
        $barriles = $litros / 158.9873;

        // Aplicar la fórmula original
        $resultado = (($precioMolecula3 * 42 - 7.14) - ($precioMolecula1 * 3.7854 * 42));

        // Multiplicar el resultado por la cantidad de barriles
        $serviceFee = $resultado * $barriles;

        // Calcular el Transportación Fee
        $transportationFee = $barriles * 7.14;

        // Weight Controller fijo
        $weightController = 10;

        //
        $total = $serviceFee + $transportationFee + $weightController;


        // Insertar los datos en la tabla molecula3
        Molecula3::create([
            'bol_id' => $bol->bol,
            'precio_molecula1' => $precioMolecula1,
            'precio_molecula3' => $precioMolecula3,
            'resultado' => $serviceFee,
            'transportation_fee' => $transportationFee,
            'weight_controller' => $weightController,
            'total' => $total,
        ]);
    }

    // Redirigir con un mensaje de éxito
    return redirect()->back()->with('success', 'BoLs migrados exitosamente.');
    }

    public function pagarBoLs(Request $request)
    {
    $bolIds = $request->input('bol_ids'); // Obtener los BoLs seleccionados para pago
    
    if($bolIds) {
        // Actualizar el estado de los BoLs seleccionados
        Molecula3::whereIn('bol_id', $bolIds)->update(['status' => 'pagado']);

        // Obtener los BoLs pagados
        $bolsPagados = Molecula3::whereIn('bol_id', $bolIds)->get();

        // Generar el PDF con los BoLs pagados
        $pdf = PDF::loadView('moleculas.bols_pagados', compact('bolsPagados'));

        // Retornar el PDF para descarga
        return $pdf->download('boLs_pagados.pdf');

        // Redirigir de nuevo con mensaje de éxito
        return redirect()->back()->with('success', 'Los BoLs seleccionados han sido pagados.');
    }

    return redirect()->back()->with('error', 'No se seleccionaron BoLs para pagar.');
    }

    // ==========================================================================================================================================

    // Sincronizar Numero de factura molecula 1

    public function syncBOLWithInvoice()
    {
        // Obtén los registros de Molecula 1 que tienen un BOL y están pendientes
        $moleculaRecords = Molecula::whereNotNull('bol_number')
                                    ->whereNull('NumeroFactura') // Asegúrate de que aún no se haya asignado una factura
                                    ->get();
        
        foreach ($moleculaRecords as $record) {
            // Limpia el número de BOL (elimina espacios en blanco si hay)
            $bolNumber = trim($record->bol_number);
        
            // Busca la factura en el modelo de Invoice que coincida con el BOL y que contenga 'PETROLEUM DISTILLATES'
            $invoice = Invoice::where('bol', $bolNumber)
                                ->where('item_names', 'LIKE', '%PETROLEUM DISTILLATES%')
                                ->first();
        
            // Verifica si encontró una factura
            if ($invoice) {
                // Actualiza el campo NumeroFactura en la tabla Molecula
                $record->update([
                    'NumeroFactura' => $invoice->NumeroFactura,
                ]);
    
                // Log para verificación
                \Log::info('BOL: ' . $bolNumber . ' sincronizado con la factura: ' . $invoice->NumeroFactura);
            } else {
                \Log::warning('No se encontró una factura para el BOL: ' . $bolNumber);
            }
        }
    
        return redirect()->back()->with('success', 'BOLs sincronizados con las facturas correctamente.');
    }
    
    //Sincronizar Numero de molecula 3
    public function syncBOLWithMolecula3()
    {
    // Obtén los registros de Molecula 3 que tienen un BOL y están pendientes
    $moleculaRecords = Molecula3::whereNotNull('bol_id')
                                ->whereNull('NumeroFactura') // Asegúrate de que aún no se haya asignado una factura
                                ->get();
    
    foreach ($moleculaRecords as $record) {
        // Limpia el número de BOL (elimina espacios en blanco si hay)
        $bolNumber = trim($record->bol_id);
    
        // Busca la factura en el modelo de Invoice que coincida con el BOL y contenga 'TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL'
        $invoice = Invoice::where('bol', $bolNumber)
                            ->where('item_names', 'LIKE', '%TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL%')
                            ->first();
    
        // Verifica si encontró una factura
        if ($invoice) {
            // Actualiza el campo NumeroFactura en la tabla Molecula 3
            $record->update([
                'NumeroFactura' => $invoice->NumeroFactura,
            ]);

            // Log para verificación
            \Log::info('BOL: ' . $bolNumber . ' sincronizado con la factura: ' . $invoice->NumeroFactura . ' para Molecula 3');
        } else {
            \Log::warning('No se encontró una factura para el BOL: ' . $bolNumber . ' en Molecula 3');
        }
    }

    return redirect()->back()->with('success', 'BOLs sincronizados con las facturas en Molecula 3 correctamente.');
    }

    // Sincronizar Numero de Molecula 2
    public function syncBOLWithMolecula2()
    {
    // Cambia 'bol_id' por 'bol', que es el nombre correcto de la columna
    $moleculaRecords = Molecula2::whereNotNull('bol') // Aquí el nombre correcto de la columna
                                ->whereNull('NumeroFactura') // Asegúrate de que aún no se haya asignado una factura
                                ->get();

    foreach ($moleculaRecords as $record) {
        // Limpia el número de BOL (elimina espacios en blanco si hay)
        $bolNumber = trim($record->bol); // Usa el nombre correcto de la columna

        // Busca la factura en el modelo de Invoice que coincida con el BOL y contenga 'OPERATION ADJUSTED'
        $invoice = Invoice::where('bol', $bolNumber)
                            ->where('item_names', 'LIKE', '%OPERATION ADJUSTED%')
                            ->first();

        // Verifica si encontró una factura
        if ($invoice) {
            // Actualiza el campo NumeroFactura en la tabla Molecula 2
            $record->update([
                'NumeroFactura' => $invoice->NumeroFactura,
            ]);

            // Log para verificación
            \Log::info('BOL: ' . $bolNumber . ' sincronizado con la factura: ' . $invoice->NumeroFactura . ' para Molecula 2');
        } else {
            \Log::warning('No se encontró una factura para el BOL: ' . $bolNumber . ' en Molecula 2');
        }
    }

    return redirect()->back()->with('success', 'BOLs sincronizados con las facturas en Molecula 2 correctamente.');
    }


} 