<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Logistica;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\AgenteAduanal;
use App\Aduana;

class AduanaController extends Controller
{
    public function calcularCuota(Request $request)
    {
        // Validar la entrada del usuario
        $request->validate([
            'tipo_cambio' => 'required|numeric',
            'cuota_por_bol' => 'required|numeric',
        ]);

        // Obtener los datos de logística
        $logis = Logistica::where('status', 'pendiente')->get(); // Solo los BoLs pendientes

        $tipoCambio = $request->input('tipo_cambio');
        $cuotaBase = $request->input('cuota_por_bol');
        $totales = [];

        foreach ($logis as $logi) {
            // Verificar que el registro tiene litros y precio
            if ($logi->litros && $logi->precio) {
                // Calcular el total aplicando el tipo de cambio y la cuota
                $total = ($logi->precio * $logi->litros * $tipoCambio) + $cuotaBase;
                $totales[$logi->id] = $total;

                // Guardar el total en la base de datos (opcional)
                $logi->total_cuota = $total;
                $logi->save();
            } else {
                $totales[$logi->id] = null;
            }
        }

        return view('aduana.calculo_cuota', compact('totales', 'tipoCambio', 'cuotaBase'));
    }

    public function mostrarFormulario()
    {
        return view('aduana.formulario');
    }

    public function indexAgentes()
    {
        $agentes = AgenteAduanal::all(); // Obtener todos los agentes aduanales
        
        $data = [];
        $data['menu'] = 'agentes';
        $data['submenu'] = '';
        $data['agentes'] = $agentes;
        return view('aduana.listado', $data ); // Cambiar a 'listado'
    }

    public function createAgente()
    {
        return view('aduana.create'); // Retornar la vista del formulario
    }

    public function storeAgente(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:agentes_aduanales,codigo',
            'rfc' => 'required|string|max:13|unique:agentes_aduanales,rfc',
            'telefono' => 'nullable|string|max:15',
            'email' => 'nullable|string|email|max:255'
        ]);

        // Crear un nuevo agente aduanal
        AgenteAduanal::create($request->all());

        return redirect()->route('aduana.index')->with('success', 'Agente aduanal creado con éxito');
    }

    public function index()
    {
        $aduanas = Aduana::all();
        $agentes = AgenteAduanal::all();
        $data = [];
        $data['menu'] = 'aduanas';
        $data['submenu'] = '';
        $data['aduanas'] = $aduanas;
        $data['agentes'] = $agentes;
        return view('aduana.index', $data);
    }

    public function migrateAll(Request $request)
    {
        // Usar transacciones para mantener la consistencia de la base de datos
        DB::beginTransaction();
    
        try {
            // Buscar todos los BoLs en la tabla logistica que ya tengan pedimento
            $logisticas = Logistica::whereNotNull('pedimento')->get();
    
            if ($logisticas->isEmpty()) {
                return redirect()->back()->with('error', 'No se encontraron BoLs con pedimento en la tabla logistica');
            }
    
            foreach ($logisticas as $logistica) {
                // Verificar si el BoL ya ha sido migrado
                $aduanaExistente = Aduana::where('bol_number', $logistica->bol)->first();
                if (!$aduanaExistente) {
                    // Crear un nuevo registro en la tabla aduanas
                    $aduana = new Aduana();
                    $aduana->bol_number = $logistica->bol;
                    $aduana->pedimento = $logistica->pedimento;
                    $aduana->linea = $logistica->linea;
                    $aduana->no_pipa = $logistica->no_pipa;
                    $aduana->save();
                }
            }
    
            DB::commit();  // Confirmar la transacción
    
            return redirect()->route('aduana.index')->with('success', 'BoLs migrados con éxito');
        } catch (Exception $e) {
            DB::rollBack();  // Revertir la transacción en caso de error
            return redirect()->back()->with('error', 'Error al migrar los BoLs: ' . $e->getMessage());
        }
    }

    public function updateAgente(Request $request, $id)
    {
        $request->validate([
        'agente_aduanal_id' => 'nullable|exists:agentes_aduanales,id',
    ]);

    $aduana = Aduana::findOrFail($id);
    $aduana->id_agente = $request->input('agente_aduanal_id');
    $aduana->save();

    return redirect()->route('aduana.index')->with('success', 'Agente aduanal actualizado con éxito');
    }

    public function assignAgents(Request $request)
    {
        $request->validate([
            'agente_aduanal_id' => 'required|exists:agentes_aduanales,id',
        ]);
    
        $agenteId = $request->input('agente_aduanal_id');
    
        // Asignar el agente aduanal a todos los BoLs sin agente asignado
        Aduana::whereNull('id_agente')->update(['id_agente' => $agenteId]);
    
        return redirect()->route('aduana.index')->with('success', 'Agente aduanal asignado a todos los BoLs con éxito');
    }

    public function saveAllAgents(Request $request)
    {
        // Validaciones
        $request->validate([
            'agentes' => 'required|array',
            'agentes.*' => 'nullable|exists:agentes_aduanales,id',  // Validar que cada agente exista
            'tipo_de_cambio_global' => 'required|numeric',  // Validar el tipo de cambio global
        ]);
    
        $tipoDeCambioGlobal = $request->input('tipo_de_cambio_global');
    
        // Recorrer cada BoL y asignar el agente aduanal correspondiente
        try {
            foreach ($request->input('agentes') as $aduanaId => $agenteId) {
                $aduana = Aduana::findOrFail($aduanaId);
    
                // Asignar el agente si no está vacío
                if ($agenteId) {
                    $aduana->id_agente = $agenteId;
    
                    // Realizar los cálculos de honorarios y dls
                    $aduana->tipo_de_cambio = $tipoDeCambioGlobal;
                    $aduana->honorarios = 175 * $tipoDeCambioGlobal;
                    $aduana->dls = 185;
    
                    // Guardar la actualización
                    $aduana->save();
                }
            }
    
            // Redireccionar con un mensaje de éxito y mostrar el modal para el tipo de cambio
            return redirect()->route('aduana.index')->with('success', 'Agentes aduanales asignados y cálculos realizados con éxito.')
                                       ->with('showTipoCambioModal', true);
        } catch (Exception $e) {
            // Capturar errores y mostrar un mensaje
            return back()->withErrors(['error' => 'Ocurrió un error al guardar los datos: ' . $e->getMessage()]);
        }
    }
    
    
    public function assignTipoCambio(Request $request)
    {
        $request->validate([
            'tipo_de_cambio_global' => 'required|numeric',
        ]);
    
        // Obtener solo las aduanas que fueron asignadas en la solicitud anterior
        $assignedAduanasIds = session('assigned_aduanas', []); // Obtener las aduanas de la sesión
        if (!empty($assignedAduanasIds)) {
            // Obtener las aduanas asignadas
            $aduanas = Aduana::whereIn('id', $assignedAduanasIds)->get();
    
            // Actualizar cada una con el tipo de cambio y calcular los honorarios y dls
            foreach ($aduanas as $aduana) {
                $aduana->tipo_de_cambio = $request->input('tipo_de_cambio_global');
                $aduana->honorarios = 175 * $aduana->tipo_de_cambio;
                $aduana->dls = 185;
                $aduana->save();
            }
    
            // Limpiar la sesión después de guardar
            session()->forget('assigned_aduanas');
    
            return redirect()->route('aduana.index')->with('success', 'Agentes aduanales asignados y cálculos realizados con éxito.');
        } else {
            return redirect()->route('aduana.index')->with('error', 'No se encontraron aduanas asignadas.');
        }
    }
    
}