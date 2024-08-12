<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerAdmin;
use App\Banco;
use App\ClienteBanco;
use App\Devolucion;
use Illuminate\Support\Facades\Log;
use App\Customer;
use App\EmpresaCuenta;

class AdministracionController extends Controller
{
    public function index()
    {
        $clientes = CustomerAdmin::with('bancos')->get();

        $clientes = $clientes->map(function ($cliente) {
            $cliente->saldo_mxn = $cliente->bancos->sum('pivot.saldo_mxn');
            $cliente->saldo_usd = $cliente->bancos->sum('pivot.saldo_usd');
            return $cliente;
        });

        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'clientes' => $clientes,
        ];

        return view('Admin.index', $data);
    }

    public function showDepositForm()
    {
        $clientes = CustomerAdmin::all();
        $bancos = Banco::all();

        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'clientes' => $clientes,
            'bancos' => $bancos,
        ];

        return view('Admin.depositar', $data);
    }

    public function processDeposit(Request $request)
    {
        $validated = $request->validate([
            'cliente' => 'required|integer|exists:customer_admin,id',
            'banco' => 'required|integer|exists:bancos,id',
            'cantidad' => 'required|numeric|min:0',
            'moneda' => 'required|string'
        ]);
    
        // Limpiar la cantidad y convertirla a float
        $cantidad = preg_replace('/[$,]/', '', $validated['cantidad']);
        $cantidad = floatval($cantidad);
        
        // Registrar el depósito en ClienteBanco
        $deposito = new ClienteBanco();
        $deposito->cliente_id = $validated['cliente'];
        $deposito->banco_id = $validated['banco'];
        if ($validated['moneda'] == 'MXN') {
            $deposito->saldo_mxn = $cantidad;
            $deposito->saldo_usd = 0;
        } else {
            $deposito->saldo_usd = $cantidad;
            $deposito->saldo_mxn = 0;
        }
        $deposito->save();
    
        // Actualizar la cuenta de la empresa
        $bancoNombre = Banco::find($validated['banco'])->banco;
        $empresaCuenta = EmpresaCuenta::firstOrNew(['banco' => $bancoNombre]);
    
        if ($validated['moneda'] == 'MXN') {
            $empresaCuenta->ingreso_mxn += $cantidad;
            $empresaCuenta->comision_mxn = $empresaCuenta->ingreso_mxn * 0.035;
            $empresaCuenta->saldo_final_mxn = $empresaCuenta->ingreso_mxn - $empresaCuenta->comision_mxn;
        } else {
            $empresaCuenta->ingreso_usd += $cantidad;
            $empresaCuenta->comision_usd = $empresaCuenta->ingreso_usd * 0.035;
            $empresaCuenta->saldo_final_usd = $empresaCuenta->ingreso_usd - $empresaCuenta->comision_usd;
        }
        $empresaCuenta->save();
    
        return redirect()->route('Admin.showClientBanks', ['id' => $validated['cliente']])
                         ->with('success', 'Depósito registrado exitosamente con una retención del 3.5%.');
    }
    
    public function showClientBanks($id)
    {
        $cliente = CustomerAdmin::with('bancos')->findOrFail($id);

        $totales = [];
        foreach ($cliente->bancos as $banco) {
            if (!isset($totales[$banco->id])) {
                $totales[$banco->id] = [
                    'banco' => $banco->banco,
                    'saldo_mxn' => 0,
                    'saldo_usd' => 0
                ];
            }
            $totales[$banco->id]['saldo_mxn'] += $banco->pivot->saldo_mxn;
            $totales[$banco->id]['saldo_usd'] += $banco->pivot->saldo_usd;
        }

        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'cliente' => $cliente,
            'totales' => $totales,
        ];

        return view('Admin.showClientBanks', $data);
    }

    public function showDepositHistory($id)
    {
        $cliente = CustomerAdmin::findOrFail($id);
        $depositos = ClienteBanco::where('cliente_id', $id)->with('banco')->get();
        $devoluciones = Devolucion::where('cliente_id', $id)->get();
        $clientes = Customer::all(); // Cargar todos los clientes
    
        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'cliente' => $cliente,
            'depositos' => $depositos,
            'devoluciones' => $devoluciones,
        ];
    
        return view('Admin.depositos_historial', $data);
    }

    // ==================================================================================================================================================
    
    public function obtenerBancoId($nombreBanco) {
        // Consulta a la base de datos para obtener el banco_id basado en el nombre
        $banco = Banco::where('banco', $nombreBanco)->first();
        return $banco ? $banco->id : null;
    }
    public function showDevolucionesForm($cliente_id)
    {
        
        $bancos = Banco::all(); // Asegúrate de tener el modelo y los datos disponibles
        return view('Admin.devoluciones', [
            'cliente_id' => $cliente_id,
            'bancos' => $bancos
        ]);
    }
    public function storeDevolucion(Request $request)
    {
        try {
            // Validación de los datos del formulario
            $validatedData = $request->validate([
                'deposito_id' => 'required|exists:cliente_banco,id',
                'cliente_id' => 'required|exists:customer_admin,id',
                'banco_id' => 'required|exists:bancos,id',
                'banco' => 'required|string',
                'cantidad' => 'required|numeric|min:0.01',
                'moneda' => 'required|string|max:3',
            ]);
    
            // Limpiar la cantidad y convertirla a float
            $cantidad = preg_replace('/[$,]/', '', $validatedData['cantidad']);
            $cantidad = floatval($cantidad);
    
            // Crear la devolución
            Devolucion::create([
                'id_deposito' => $validatedData['deposito_id'],
                'cliente_id' => $validatedData['cliente_id'],
                'banco_id' => $validatedData['banco_id'],
                'cantidad' => $cantidad,
                'moneda' => $validatedData['moneda'],
            ]);
    
            // Actualizar el saldo en la tabla `cliente_banco`
            $deposito = ClienteBanco::find($validatedData['deposito_id']);
            if ($validatedData['moneda'] == 'MXN') {
                $deposito->saldo_mxn -= $cantidad;
            } else {
                $deposito->saldo_usd -= $cantidad;
            }
            $deposito->save();
    
            // Redirige o retorna una respuesta
            return redirect()->route('Admin.showClientBanks', ['id' => $validatedData['cliente_id']])
                             ->with('success', 'Devolución registrada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error en storeDevolucion: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()->back()->withErrors(['msg' => 'Ocurrió un error al registrar la devolución.']);
        }
    }
    
    // ============================================================================================================================================

    public function showIncomesAndRefunds()
    {
       
    $clientes = Customer::all(); // Cargar todos los clientes

    $depositos = ClienteBanco::with('banco', 'cliente')
        ->orderBy('created_at', 'desc')
        ->get();


    $devoluciones = Devolucion::with('banco')
        ->orderBy('created_at', 'desc')
        ->get();

    $data = [
        'menu' => 'Admin',
        'submenu' => '',
        'depositos' => $depositos,
        'devoluciones' => $devoluciones,
        'clientes' => $clientes,
    ];

    
        return view('Admin.ingresos_devoluciones', $data);
    }
    
    // ===============================================================================================================================================

    private function convertUsdToMxn($usdAmount, $tipo_cambio)
    {
        return $usdAmount * $tipo_cambio;
    }

    public function asignarSaldo(Request $request, $depositoId)
    {
    $deposito = ClienteBanco::findOrFail($depositoId);

    // Verificar si el depósito ya ha sido asignado
    if ($deposito->asignado) {
        return redirect()->back()->with('error', 'Este depósito ya ha sido asignado.');
    }

    $cliente = Customer::findOrFail($request->input('cliente_id'));

    if ($deposito->saldo_usd > 0) {
        if ($request->has('tipo_cambio')) {
            $tipo_cambio = $request->input('tipo_cambio');
            $cantidad = $deposito->saldo_usd * $tipo_cambio;
        } else {
            return redirect()->back()->with('error', 'El tipo de cambio es necesario para asignar el saldo en USD.');
        }
    } else {
        $cantidad = $deposito->saldo_mxn;
    }

    // Actualiza el saldo a favor del cliente
    $cliente->saldo_a_favor += $cantidad;
    $cliente->save();

    // Marcar el depósito como asignado
    $deposito->asignado = true;
    $deposito->save();

    return redirect()->route('Admin.index')->with('success', 'Saldo asignado con éxito.');
    }
     
}
