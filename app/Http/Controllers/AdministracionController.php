<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerAdmin;
use App\Banco;
use App\ClienteBanco;
use App\transacciones;


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

        $data = [];
        $data['menu'] = 'Admin';
        $data['submenu'] = '';
        $data['clientes'] = $clientes;

        return view('Admin.index', $data);
    }

    public function showDepositForm()
    {
        $clientes = CustomerAdmin::all();
        $bancos = Banco::all();

        $data = [];
        $data['menu'] = 'Admin';
        $data['submenu'] = '';
        $data['clientes'] = $clientes;
        $data['bancos'] = $bancos;

        return view('Admin.depositar', $data );
    }

    public function processDeposit(Request $request)
    {
        $validated = $request->validate([
            'cliente' => 'required|exists:customer_admin,id',
            'banco' => 'required|exists:bancos,id',
            'cantidad' => 'required|numeric|min:0',
            'moneda' => 'required|in:MXN,USD',
        ]);
    
        $clienteId = $validated['cliente'];
        $bancoId = $validated['banco'];
        $cantidad = $validated['cantidad'];
        $moneda = $validated['moneda'];
    
        // Calcular el monto después del descuento del 3.5%
        $descuento = 0.035;
        $cantidadDescontada = $cantidad * (1 - $descuento);
    
        // Registrar el depósito
        ClienteBanco::updateOrCreate(
            ['cliente_id' => $clienteId, 'banco_id' => $bancoId],
            [
                'saldo_mxn' => $moneda == 'MXN' ? $cantidadDescontada : 0,
                'saldo_usd' => $moneda == 'USD' ? $cantidadDescontada : 0,
            ]
        );
    
        return redirect()->route('Admin.index')->with('success', 'Depósito registrado exitosamente con el descuento aplicado.');
    }
    

    public function showClientBanks($id)
    {
        $cliente = CustomerAdmin::with('bancos')->findOrFail($id);
        $data = [];
        $data['menu'] = 'Admin';
        $data['submenu'] = '';
        $data['cliente'] = $cliente;

        return view('Admin.showClientBanks', $data);
    }

    public function showDepositHistory($id)
    {
        // Obtén el cliente y su historial de depósitos
        $cliente = CustomerAdmin::findOrFail($id);
        $depositos = ClienteBanco::where('cliente_id', $id)
            ->with('banco') // Asegúrate de que 'banco' esté correctamente definido en el modelo ClienteBanco
            ->get();

        $data = [];
        $data['menu'] = 'Admin';
        $data['submenu'] = '';
        $data['cliente'] = $cliente;
        $data['depositos'] = $depositos;

        return view('Admin.depositos_historial', $data);
    }

}


