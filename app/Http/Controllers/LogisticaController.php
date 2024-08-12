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
            $bluewiRecords = Bluewi::all();

            foreach ($bluewiRecords as $record) {
                if (!empty($record->bol_number)) {
                    $exists = Logistica::where('bol', $record->bol_number)
                                        ->where('order_number', $record->order_number)
                                        ->exists();

                    if (!$exists) {
                        $transportistaId = null;
                        
                        if (isset($record->carrier)) {
                            if ($record->carrier == 'JOSE LUIS LUMBRERAS') {
                                $transportistaId = 2; // ID del transportista XLV (Liji)
                            } elseif ($record->carrier == 'Autotransportes SK') {
                                $transportistaId = 1; // ID del transportista SK
                            } elseif ($record->carrier == 'TOKKO CARRIERS DE MEXICO SA DE CV') {
                                $transportistaId = 3; // ID del transportista TOKKO
                            }
                        }

                        Logistica::create([
                            'bol' => $record->bol_number,
                            'order_number' => $record->order_number ?? '',
                            'semana' => Carbon::parse($record->bol_date)->weekOfYear,
                            'fecha' => $record->bol_date,
                            'linea' => $record->carrier ?? '',
                            'no_pipa' => $record->trailer ?? '',
                            'cliente' => null,
                            'destino' => null,
                            'transportista_id' => $transportistaId,
                            'destino_id' => null,
                            'status' => "pendiente",
                            'litros' => $record->net_usg ? round($record->net_usg * 3.78541) : 0,
                            'cruce' => "rojo",
                            'pedimento' => null,
                        ]);
                    }
                }
            }
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
        $logisticaData = $request->input('logistica');

        DB::transaction(function () use ($logisticaData) {
            foreach ($logisticaData as $id => $data) {
                $logistica = Logistica::find($id);

                if (!$logistica) {
                    continue;
                }

                if (!$logistica->cliente) {
                    $cliente = Customer::find($data['cliente']);
                    if ($cliente) {
                        $logistica->cliente = $data['cliente'];

                        if (strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false) {
                            $logistica->destino_id = 5;
                            $logistica->transportista_id = null;
                        }
                    }
                }

                $logistica->status = $data['status'] ?? $logistica->status;
                $logistica->cruce = $data['cruce'] ?? $logistica->cruce;
                $logistica->fecha_salida = $data['fecha_salida'] ? Carbon::parse($data['fecha_salida'])->format('Y-m-d') : $logistica->fecha_salida;
                $logistica->fecha_entrega = $data['fecha_entrega'] ? Carbon::parse($data['fecha_entrega'])->format('Y-m-d') : $logistica->fecha_entrega;
                $logistica->fecha_descarga = $data['fecha_descarga'] ? Carbon::parse($data['fecha_descarga'])->format('Y-m-d') : $logistica->fecha_descarga;

                if (isset($data['precio'])) {
                    $logistica->precio = $data['precio'];
                }

                $logistica->pedimento = $data['pedimento'] ?? $logistica->pedimento;
                $logistica->save();
            }
        });

        return redirect()->back()->with('success', 'Datos actualizados exitosamente');
    }

}