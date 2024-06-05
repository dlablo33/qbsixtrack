<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Factura;
use App\Pago;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index()
{
    // Obtener las facturas con saldos pendientes
    $facturasPendientes = Factura::select(
            'cliente_name',
            DB::raw('SUM(total) as totalFacturas')
        )
        ->groupBy('cliente_name')
        ->havingRaw('SUM(total) > 0') // Solo facturas con saldo pendiente
        ->get();

    // Obtener los pagos realizados
    $pagosRealizados = Pago::select(
            'fac_invoice.cliente_name',
            DB::raw('SUM(monto) as totalPagos')
        )
        ->leftJoin('fac_invoice', 'pagos.factura_id', '=', 'fac_invoice.id')
        ->groupBy('fac_invoice.cliente_name')
        ->get();

    // Calcular el saldo pendiente por cliente
    $deudasPorCliente = $facturasPendientes->map(function ($factura) use ($pagosRealizados) {
        $pagosCliente = $pagosRealizados->firstWhere('cliente_name', $factura->cliente_name);
        $totalPagos = $pagosCliente ? $pagosCliente->totalPagos : 0;
        $saldoRestante = $factura->totalFacturas - $totalPagos;

        // Calcular saldo a favor
        $cliente = Customer::where('CLIENTE_LP', $factura->cliente_name)->first();
        $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;

        return (object) [
            'cliente_name' => $factura->cliente_name,
            'saldoRestante' => $saldoRestante,
            'saldoAFavor' => $saldoAFavor,
        ];
    });

    // Preparar los datos para la vista
    $data['menu'] = "deudasPorCliente";
    $data['menu_sub'] = "";
    $data['deudasPorCliente'] = $deudasPorCliente;

    return view('cuentas.index', $data);
}


    public function facturasCliente($cliente)
    {
        $facturas = Factura::where('cliente_name', $cliente)->get();
        return view('invoice.index', compact('facturas', 'cliente_name'));
    }

    public function show($cliente_name)
{
    $facturas = Factura::where('cliente_name', $cliente_name)->get();

    //=================
    $cliente = Customer::where('CLIENTE_LP', $cliente_name)->first();
    $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;


    return view('cuentas.cnc-detalle', compact('facturas', 'cliente_name','saldoAFavor'));
}

public function create($factura_id)
{
    $factura = Factura::findOrFail($factura_id);
    $cliente_name = $factura->cliente_name;

    // Obtener el saldo a favor del cliente
    $cliente = Customer::where('CLIENTE_LP', $cliente_name)->first();
    $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;

    return view('cuentas.create', compact('factura', 'cliente_name', 'saldoAFavor'));
}

public function store(Request $request)
{
    $request->validate([
        'factura_id' => 'required|exists:fac_invoice,id',
        'monto' => 'required|numeric|min:0',
        'referencia' => 'nullable|string|max:255',
    ]);

    $factura = Factura::findOrFail($request->factura_id);
    $montoPendiente = $factura->montoPendiente();
    $montoPago = $request->monto;

    if ($montoPago > $montoPendiente) {
        // Pago completo con saldo a favor
        $this->registrarSaldoAFavor($factura, $montoPago, $montoPendiente, $request->referencia);
    } else {
        // Pago parcial o completo sin saldo a favor
        Pago::create([
            'factura_id' => $factura->id,
            'monto' => $montoPago,
            'fecha_pago' => now(),
            'referencia' => $request->referencia,
        ]);
    }

    return redirect()->route('cuentas.cnc-detalle', ['cliente_name' => $factura->cliente_name])
        ->with('success', 'Pago registrado exitosamente.');
}

private function registrarSaldoAFavor(Factura $factura, $montoPago, $montoPendiente, $referencia)
{
    Pago::create([
        'factura_id' => $factura->id,
        'monto' => $montoPendiente,
        'fecha_pago' => now(),
        'referencia' => $referencia,
    ]);

    $saldoAFavor = $montoPago - $montoPendiente;
    $cliente = Customer::where('CLIENTE_LP', $factura->cliente_name)->first();
    $cliente->saldo_a_favor += $saldoAFavor;
    $cliente->save();
}

public function usarSaldo(Factura $factura)
{
    // Obtén el cliente asociado con la factura
    $cliente = Customer::where('CLIENTE_LP', $factura->cliente_name)->first();

    if (!$cliente) {
        return redirect()->back()->with('error', 'Cliente no encontrado.');
    }

    $saldoAFavor = $cliente->saldo_a_favor;
    $montoPendiente = $factura->montoPendiente();

    if ($saldoAFavor > 0) {
        if ($saldoAFavor >= $montoPendiente) {
            // El saldo a favor es suficiente para cubrir la factura
            $cliente->saldo_a_favor -= $montoPendiente;
            $cliente->save();

            Pago::create([
                'factura_id' => $factura->id,
                'monto' => $montoPendiente,
                'fecha_pago' => now(),
                'referencia' => 'Saldo a favor'
            ]);

            return redirect()->back()->with('success', 'Saldo a favor utilizado completamente para pagar la factura.');
        } else {
            // El saldo a favor es insuficiente para cubrir la factura
            $montoCubierto = $saldoAFavor;
            $cliente->saldo_a_favor = 0;
            $cliente->save();

            Pago::create([
                'factura_id' => $factura->id,
                'monto' => $montoCubierto,
                'fecha_pago' => now(),
                'referencia' => 'Saldo a favor'
            ]);

            return redirect()->back()->with('success', 'Parte del saldo a favor utilizado para cubrir parcialmente la factura.');
        }
    } else {
        return redirect()->back()->with('error', 'No hay saldo a favor disponible.');
    }
}

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function pagos($factura_id)
    {
        $factura = Factura::findOrFail($factura_id);
        $pagos = $factura->pagos;
        return view('cuentas.index', compact('factura', 'pagos'));
    }

    public function pagarCompleto(Request $request, Factura $factura)
    {
        $request->validate([
            'referencia' => 'nullable|string|max:255', // Validación para el campo de referencia
        ]);
    
        $montoPendiente = $factura->montoPendiente();
        
        // Datos para crear el pago
        $data = [
            'factura_id' => $factura->id,
            'monto' => $montoPendiente,
            'fecha_pago' => now(),
        ];
    
        // Si se proporcionó una referencia, añadirla a los datos del pago
        if ($request->has('referencia')) {
            $data['referencia'] = $request->referencia;
        }
    
        Pago::create($data);
    
        return redirect()->route('cuentas.cnc-detalle', ['cliente_name' => $factura->cliente_name])
            ->with('success', 'Deuda pagada completamente.');
    }
}
