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
use App\Mail\InvoiceMail;

class LogisticaController extends Controller
{
    public function index()
    {
        ini_set('max_execution_time', 500);

        // Ordena las instancias de Logistica por fecha de manera descendente
        $logis = Logistica::orderBy('fecha', 'desc')->get();
        $clientes = Customer::all();
        $transportistas = Transportista::all();
        $destinos = Destino::all();

        $precios = [];
        $totales = [];

        foreach ($logis as $logi) {
            // Obtén la semana del año a partir de la fecha del registro de Logistica
            $semana = Carbon::parse($logi->fecha)->weekOfYear;
            $cliente = Customer::find($logi->cliente);

            if ($cliente) {
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
        }

        // Prepara los datos para la vista
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

        // Retorna la vista con los datos
        return view('logistica.index', $data);
    }

    public function transferData()
    {
    ini_set('max_execution_time', 600);
    // Obtener todos los registros de bluewi
    $bluewiRecords = Bluewi::all();

    // Iterar sobre cada registro de bluewi y crear un nuevo registro en logistica si no existe y bol no es nulo
    foreach ($bluewiRecords as $record) {
        // Verificar si el campo bol_number está definido y no es nulo
        if (!empty($record->bol_number)) {
            // Verificar si el registro ya existe en logistica
            $exists = Logistica::where('bol', $record->bol_number)
                                ->where('order_number', $record->order_number)
                                ->exists();

            if (!$exists) {
                // Inicializar la variable transportistaId
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
                ]);
            }
        }
    }

    return redirect()->route('logistica.index')->with('success', 'Datos transferidos con éxito');
    }

    public function asignarCliente(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'cliente' => 'nullable|exists:customers,id',
            'logistica_id' => 'required|exists:logistica,id',
            'status' => 'required|in:pendiente,cargada,descargada',
            'cruce' => 'required|in:verde,rojo',
            'fecha_salida' => 'nullable|date',
            'fecha_entrega' => 'nullable|date',
            'fecha_descarga' => 'nullable|date',
        ]);
    
        $logistica = Logistica::find($request->input('logistica_id'));
        if ($logistica) {
            // Obtener el cliente solo si está asignado en la solicitud
            $cliente = $request->cliente ? Customer::find($request->cliente) : null;
    
            // Si el cliente ya está asignado, no permitir cambiarlo
            if (!$logistica->cliente) {
                $logistica->cliente = $request->input('cliente');
                // Verificar si el nombre del cliente contiene "FOB"
                if ($cliente && strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false) {
                    $logistica->destino_id = 5; // Asignar ID 5 para 'FOB'
                    $logistica->transportista_id = null;
                } 
            }
    
            // Si el transportista ya está asignado, no permitir cambiarlo
            if (!$logistica->transportista_id) {
                $logistica->transportista_id = $request->transportista;
            }
    
            // Si el destino ya está asignado, no permitir cambiarlo
            if (!$logistica->destino_id && (!$cliente || strpos($cliente->NOMBRE_COMERCIAL, 'FOB') === false)) {
                $logistica->destino_id = $request->destino;
            }
    
            $logistica->status = $request->input('status');
            $logistica->cruce = $request->input('cruce');
            $logistica->fecha_salida = $request->input('fecha_salida');
            $logistica->fecha_entrega = $request->input('fecha_entrega');
            $logistica->fecha_descarga = $request->input('fecha_descarga');
    
            // Asignar el precio basado en el cliente y la semana
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

            $logistica->save();
        }
        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Cliente y estado asignados exitosamente');
    }
    
    public function guardarTodos(Request $request)
    {
        $logisticaData = $request->input('logistica');
    
        foreach ($logisticaData as $id => $data) {
            $logistica = Logistica::find($id);
    
            if (!$logistica) {
                continue; // Saltar si no se encuentra la instancia de Logistica
            }
    
            if (!$logistica->cliente) {
                $cliente = Customer::find($data['cliente']);
                $logistica->cliente = $data['cliente'];
    
                if ($cliente && strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false) {
                    $logistica->destino_id = 5; // Asignar ID 5 para 'FOB'
                    $logistica->transportista_id = null;
                }
            }
    
            // Actualizar otros campos
            $logistica->status = $data['status'];
            $logistica->cruce = $data['cruce'];
            $logistica->fecha_salida = array_key_exists('fecha_salida', $data) && $data['fecha_salida'] ? Carbon::parse($data['fecha_salida'])->format('Y-m-d') : null;
            $logistica->fecha_entrega = array_key_exists('fecha_entrega', $data) && $data['fecha_entrega'] ? Carbon::parse($data['fecha_entrega'])->format('Y-m-d') : null;
            $logistica->fecha_descarga = array_key_exists('fecha_descarga', $data) && $data['fecha_descarga'] ? Carbon::parse($data['fecha_descarga'])->format('Y-m-d') : null;
    
            // Asignar el precio si existe en los datos recibidos
            if (isset($data['precio'])) {
                $logistica->precio = $data['precio'];
            }

            $logistica->transportista_id = $data['transportista'] ?? $logistica->transportista_id;
            $logistica->destino_id = $data['destino'] ?? $logistica->destino_id;
    
            $logistica->save();
        }
    
        return redirect()->route('logistica.index')->with('success', 'Datos guardados correctamente.');
    }

}