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
            $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
            $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;

            return (object) [
                'cliente_name' => $factura->cliente_name,
                'saldoRestante' => $saldoRestante,
                'saldoAFavor' => $saldoAFavor,
            ];
        });

        // Preparar los datos para la vista
        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'deudasPorCliente' => $deudasPorCliente,
        ];

        return view('cuentas.index', $data);
    }

    public function facturasCliente($cliente)
    {
        $facturas = Factura::where('cliente_name', $cliente)->get();

        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'facturas' => $facturas,
            'cliente_name' => $cliente,
        ];

        return view('invoice.index', $data);
    }

    public function show($cliente_name)
    {
        $facturas = Factura::where('cliente_name', $cliente_name)->get();

        // Obtener el saldo a favor del cliente
        $cliente = Customer::where('NOMBRE_COMERCIAL', $cliente_name)->first();
        $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;

        // Preparar los datos para la vista
        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'facturas' => $facturas,
            'cliente_name' => $cliente_name,
            'saldoAFavor' => $saldoAFavor,
        ];

        return view('cuentas.cnc-detalle', $data);
    }

    public function create($factura_id)
    {
        $factura = Factura::findOrFail($factura_id);
        $cliente_name = $factura->cliente_name;

        // Obtener el saldo a favor del cliente
        $cliente = Customer::where('NOMBRE_COMERCIAL', $cliente_name)->first();
        $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;

        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'factura' => $factura,
            'cliente_name' => $cliente_name,
            'saldoAFavor' => $saldoAFavor,
        ];

        return view('cuentas.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'factura_id' => 'required|exists:fac_invoice,id',
            'monto' => 'required|numeric|min:0',
            'referencia' => 'required|string|max:255',
        ]);

        $factura = Factura::findOrFail($request->factura_id);
        $montoPendiente = $factura->montoPendiente();
        $montoPago = $request->monto;

        if ($montoPago >= $montoPendiente) {
            // Pago completo o con saldo a favor
            $this->registrarSaldoAFavor($factura, $montoPago, $montoPendiente, $request->referencia);

            // Cambiar el estatus de la factura a 'Pagado'
            $factura->estatus = 'Pagado';
            $factura->save();
        } else {
            // Pago parcial sin saldo a favor
            Pago::create([
                'factura_id' => $factura->id,
                'monto' => $montoPago,
                'fecha_pago' => now(),
                'referencia' => $request->referencia,
            ]);

            // Cambiar el estatus de la factura a 'Abonado'
            $factura->estatus = 'Abonado';
            $factura->save();
        }

        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'cliente_name' => $factura->cliente_name,
        ];

        return redirect()->route('cuentas.cnc-detalle', $data)
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
        $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
        $cliente->saldo_a_favor += $saldoAFavor;
        $cliente->save();
    }

    public function usarSaldo(Factura $factura)
    {
        // Obtén el cliente asociado con la factura
        $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
    
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

                $factura = Factura::find($factura->id);
    
                // Cambiar el estatus de la factura a 'Pagado'
                $factura->estatus = 'Pagado';
                $factura->save();
    
                return redirect()->back()->with('success', 'Saldo a favor utilizado como abono para pagar completamente la factura.');
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

                $factura = Factura::find($factura->id);
    
                // Cambiar el estatus de la factura a 'Abonado'
                $factura->estatus = 'Abonado';
                $factura->save();
    
                return redirect()->back()->with('success', 'Parte del saldo a favor utilizado como abono para cubrir parcialmente la factura.');
            }
        } else {
            return redirect()->back()->with('error', 'No hay saldo a favor disponible.');
        }
    }

    public function pagos($factura_id)
    {
        $factura = Factura::findOrFail($factura_id);
        $pagos = $factura->pagos;

        $data = [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'factura' => $factura,
            'pagos' => $pagos,
        ];

        return view('cuentas.index', $data);
    }

    public function pagarCompleto(Request $request, Factura $factura)
    {
        $request->validate([
            'referencia' => 'required|string|max:255', // Validación para el campo de referencia
        ]);
    
        $montoPendiente = $factura->montoPendiente();
    
        Pago::create([
            'factura_id' => $factura->id,
            'monto' => $montoPendiente,
            'fecha_pago' => now(),
            'referencia' => $request->referencia,
        ]);
    
        $factura->estatus = 'Pagado';
        $factura->save();
    
        return redirect()->route('cuentas.index')
            ->with('success', 'Factura pagada completamente con éxito.');
    }
}