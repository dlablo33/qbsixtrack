<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bluewi;
use App\Logistica;
use App\Customer;
use App\Destino;
use App\Transportista;
use App\Marchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\InvoiceMail;

class LogisticaController extends Controller
{
    public function index()
    {
        ini_set('max_execution_time', 300);

        // Obtener solo los datos necesarios
        $logis = Logistica::with(['cliente', 'destino', 'transportista'])
                          ->orderBy(DB::raw('YEAR(fecha)'), 'desc')
                          ->orderBy(DB::raw('WEEK(fecha)'), 'desc')
                          ->paginate(50); // Paginación para manejar grandes volúmenes de datos

        $clientes = Customer::all();
        $transportistas = Transportista::all();
        $destinos = Destino::all();

        $precios = [];
        $totales = [];

        foreach ($logis as $logi) {
            // Asegúrate de definir y asignar $semana antes de usarla
            $semana = Carbon::parse($logi->fecha)->weekOfYear;
        
            // Verifica si el ID del cliente es un valor numérico
            if (is_numeric($logi->cliente)) {
                $cliente = Customer::find($logi->cliente);
        
                // Verifica que $cliente no es null
                if ($cliente && isset($cliente->CVE_CTE)) {
                    $clienteCveCte = $cliente->CVE_CTE;
        
                    // Busca los precios para el cliente y semana específica
                    $precios[$logi->id] = Marchant::where('cliente_id', $clienteCveCte)
                        ->where('semana', '>=', $semana)
                        ->pluck('precio', 'id');
        
                    if (isset($precios[$logi->id])) {
                        $precioSeleccionado = $logi->precio ?? 0;
                        $litros = $logi->litros;
                        // Calcula el total multiplicando el precio por los litros
                        $totales[$logi->id] = $precioSeleccionado * $litros;
                    } else {
                        $totales[$logi->id] = null;
                    }
                } else {
                    $precios[$logi->id] = collect();
                    $totales[$logi->id] = null;
                }
            } else {
                // Maneja el caso donde 'cliente' no es numérico
                $precios[$logi->id] = collect();
                $totales[$logi->id] = null;
            }
        }
        

        $data = [
            'menu' => 'logistica',
            'submenu' => '',
            'logis' => $logis,
            'clientes' => $clientes,
            'transportistas' => $transportistas,
            'destinos' => $destinos,
            'precios' => $precios,
            'totales' => $totales
        ];

        return view('logistica.index', $data);
    }

    public function transferData()
{
    ini_set('max_execution_time', 600);

    DB::transaction(function () {
        // Obtener una lista de todos los `bol` y `order_number` ya existentes en Logistica
        $existingRecords = Logistica::pluck('bol', 'order_number')->toArray();

        // Procesar los registros de Bluewi en lotes
        Bluewi::chunk(1000, function ($bluewiRecords) use ($existingRecords) {
            $dataToInsert = [];

            foreach ($bluewiRecords as $record) {
                if (!empty($record->bol_number) && 
                    !isset($existingRecords[$record->order_number]) &&
                    !in_array($record->bol_number, $existingRecords)) {

                    $transportistaId = null;

                    if (isset($record->carrier)) {
                        if ($record->carrier == 'JOSE LUIS LUMBRERAS') {
                            $transportistaId = 2;
                        } elseif ($record->carrier == 'Autotransportes SK') {
                            $transportistaId = 1;
                        } elseif ($record->carrier == 'TOKKO CARRIERS DE MEXICO SA DE CV') {
                            $transportistaId = 3;
                        }
                    }

                    // Preparar los datos para inserción masiva
                    $dataToInsert[] = [
                        'bol' => $record->bol_number,
                        'order_number' => $record->order_number ?? '',
                        'semana' => Carbon::parse($record->bol_date)->weekOfYear,
                        'fecha' => $record->bol_date,
                        'linea' => $record->carrier ?? '',
                        'no_pipa' => $record->trailer ?? '',
                        'cliente' => null,
                        'transportista_id' => $transportistaId,
                        'destino_id' => null,
                        'status' => "pendiente",
                        'litros' => $record->net_usg ? round($record->net_usg * 3.78541) : 0,
                        'cruce' => "rojo",
                        'pedimento' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Inserción masiva
            Logistica::insert($dataToInsert);
        });
    });

    return redirect()->route('logistica.index')->with('success', 'Datos transferidos con éxito');
}


    public function asignarCliente(Request $request)
    {
        $request->validate([
            'cliente' => 'nullable|exists:customers,id',
            'logistica_id' => 'required|exists:logistica,id',
            'status' => 'required|in:pendiente,cargada,descargada',
            'cruce' => 'required|in:verde,rojo',
            'fecha_salida' => 'nullable|date',
            'fecha_entrega' => 'nullable|date',
            'fecha_descarga' => 'nullable|date',
            'pedimento' => 'nullable|string'
        ]);

        $logistica = Logistica::find($request->input('logistica_id'));
        if ($logistica) {
            $cliente = $request->cliente ? Customer::find($request->cliente) : null;

            if (!$logistica->cliente) {
                $logistica->cliente = $request->input('cliente');
                if ($cliente && strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false) {
                    $logistica->destino_id = 5;
                    $logistica->transportista_id = null;
                }
            }

            if (!$logistica->transportista_id) {
                $logistica->transportista_id = $request->transportista;
            }

            if (!$logistica->destino_id && (!$cliente || strpos($cliente->NOMBRE_COMERCIAL, 'FOB') === false)) {
                $logistica->destino_id = $request->destino;
            }

            $logistica->status = $request->input('status');
            $logistica->cruce = $request->input('cruce');
            $logistica->fecha_salida = $request->input('fecha_salida');
            $logistica->fecha_entrega = $request->input('fecha_entrega');
            $logistica->fecha_descarga = $request->input('fecha_descarga');

            $semana = Carbon::parse($logistica->fecha)->weekOfYear;
            $precio = $request->input('precio');
            if ($precio === null) {
                $precio = Marchant::where('cliente_id', $logistica->cliente)
                    ->where('semana', $semana)
                    ->first();
                $logistica->precio = $precio ? $precio->precio : 0;
            } else {
                $logistica->precio = $precio;
            }

            $logistica->pedimento = $request->input('pedimento');
            $logistica->save();
        }

        return redirect()->back()->with('success', 'Cliente y estado asignados exitosamente');
    }

    public function guardarTodos(Request $request)
    {
        $data = $request->input('logistica');
    
        foreach ($data as $id => $values) {
            // Verifica si $values está asignado
            if (!empty($values)) {
                $logistica = Logistica::find($id);
    
                if ($logistica) {
                    // Guardar el cliente
                    if (!empty($values['cliente'])) {
                        $cliente = Customer::find($values['cliente']);
                        if ($cliente) {
                            $logistica->cliente = $values['cliente'];
    
                            // Asignar destino basado en el nombre comercial del cliente
                            $logistica->destino_id = strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false ? 5 : $logistica->destino_id;
                            if ($logistica->destino_id == 5) {
                                $logistica->transportista_id = null; // Resetea el transportista si es FOB
                            }
                        }
                    }
    
                    // Guardar el destino, si se ha proporcionado
                    if (!empty($values['destino']) && $values['destino'] != $logistica->destino_id) {
                        $logistica->destino_id = $values['destino'];
                    }
    
                    // Guardar otros campos
                    $logistica->status = $values['status'] ?? $logistica->status;
                    $logistica->cruce = $values['cruce'] ?? $logistica->cruce;
                    $logistica->fecha_salida = $values['fecha_salida'] ?? $logistica->fecha_salida;
                    $logistica->fecha_entrega = $values['fecha_entrega'] ?? $logistica->fecha_entrega;
                    $logistica->fecha_descarga = $values['fecha_descarga'] ?? $logistica->fecha_descarga;
                    $logistica->pedimento = $values['pedimento'] ?? $logistica->pedimento;
                    $logistica->precio = $values['precio'] ?? $logistica->precio; // Mantener el precio si no se actualiza
                    $logistica->save();
                }
            }
        }
    
        return redirect()->route('logistica.index')->with('success', 'Datos actualizados con éxito');
    }
    
}