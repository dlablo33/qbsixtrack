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

class LogisticaController extends Controller
{
    public function index()
    {
        $logis = Logistica::all();
        $clientes = Customer::all();
        $transportistas = Transportista::all();
        $destinos = Destino::all();
        $precios = Marchant::all();

        $precios = [];
        foreach ($logis as $logi) {
            $semana = Carbon::parse($logi->fecha)->weekOfYear;
            // Buscar el cliente usando el ID del cliente en la tabla logistica
            $cliente = Customer::find($logi->cliente);
    
            // Si se encuentra el cliente, usamos el CVE_CTE para buscar los precios
            if ($cliente) {
                $clienteCveCte = $cliente->CVE_CTE;
                $precios[$logi->id] = Marchant::where('cliente_id', $clienteCveCte)
                    ->where('semana', $semana)
                    ->pluck('precio', 'id');
            } else {
                $precios[$logi->id] = collect();
            }
        }

        $data['menu'] = 'logistica';
        $data['submenu'] = '';
        $data['logis'] = $logis;
        $data['clientes'] = $clientes;
        $data['transportistas'] = $transportistas;
        $data['destinos'] = $destinos;
        $data['precios'] = $precios;

        return view('logistica.index', $data);
    }

    public function transferData()
    {
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
                    Logistica::create([
                        'bol' => $record->bol_number,
                        'order_number' => $record->order_number ?? '', 
                        'semana' => null, 
                        'fecha' => $record->bol_date, 
                        'linea' => $record->carrier ?? '', 
                        'no_pipa' => $record->trailer ?? '', 
                        'cliente' => null,
                        'destino' => null,
                        'transportista_id' => null,
                        'destino_id' => null,
                        'status' => null,
                        'litros' => $record->net_usg ? round($record->net_usg * 3.78541) : 0,
                        'cruce' => null, 
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
        ]);

        $logistica = Logistica::find($request->input('logistica_id'));
        if ($logistica) {
            // Si el cliente ya está asignado, no permitir cambiarlo
            if (!$logistica->cliente) {
                $logistica->cliente = $request->input('cliente');
                // Verificar si el nombre del cliente contiene "FOB"
                $cliente = Customer::find($request->cliente);
                if (strpos($cliente->NOMBRE_COMERCIAL, 'FOB') !== false) {
                    $logistica->destino_id = 5; // Asignar ID 5 para 'FOB'
                    $logistica->transportista_id = null;
                } else {
                    $logistica->transportista_id = $request->transportista;
                    $logistica->destino_id = $request->destino;
                }
            }

            // Si el transportista ya está asignado, no permitir cambiarlo
            if (!$logistica->transportista_id) {
                $logistica->transportista_id = $request->transportista;
            }

            // Si el destino ya está asignado, no permitir cambiarlo
            if (!$logistica->destino_id && strpos($cliente->NOMBRE_COMERCIAL, 'FOB') === false) {
                $logistica->destino_id = $request->destino;
            }

            $logistica->status = $request->input('status');
            $logistica->cruce = $request->input('cruce');

            // Asignar el precio basado en el cliente y la semana
            $semana = Carbon::parse($logistica->fecha)->weekOfYear;
            $precio = Marchant::where('cliente_id', $logistica->cliente)
                            ->where('semana', $semana)
                            ->first();
            $logistica->precio = $precio ? $precio->precio : 0;

            $logistica->save();
        }

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Cliente y estado asignados exitosamente');
    }
}