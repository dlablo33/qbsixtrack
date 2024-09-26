<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Factura;
use App\Logistica;
use App\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\LotePago;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index()
    {
        // Obtener las facturas con saldos pendientes
        $deudasPorCliente = Factura::select('cliente_name', DB::raw('SUM(total) as totalFacturas'))
        ->groupBy('cliente_name')
        ->havingRaw('SUM(total) > 0')
        ->get()
        ->map(function ($factura) {
            // Obtener todas las facturas del cliente
            $facturasDelCliente = Factura::where('cliente_name', $factura->cliente_name)->get();
            
            $totalFacturas = 0;
            $totalPagos = 0;
    
            foreach ($facturasDelCliente as $facturaCliente) {
                // Sumar el total de todas las facturas
                $totalFacturas += $facturaCliente->total;
    
                // Sumar todos los pagos de las facturas
                $totalPagos += Pago::where('factura_id', $facturaCliente->id)->sum('monto');
            }
    
            // Calcular el saldo restante correctamente
            $saldoRestante = $totalFacturas - $totalPagos;
    
            // Obtener saldo a favor del cliente
            $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
            $saldoAFavor = $cliente ? $cliente->saldo_a_favor : 0;
    
            return (object) [
                'cliente_name' => $factura->cliente_name,
                'saldoRestante' => $saldoRestante,
                'saldoAFavor' => $saldoAFavor,
            ];
        });

        // Preparar los datos para la vista
        return view('cuentas.index', [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'deudasPorCliente' => $deudasPorCliente,
        ]);
    }

    public function facturasCliente($cliente)
    {
        $facturas = Factura::where('cliente_name', $cliente)->get();

        return view('invoice.index', [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'facturas' => $facturas,
            'cliente_name' => $cliente,
        ]);
    }

    public function show($cliente_name)
    {
        // Obtener facturas por cliente
        $facturas = Factura::where('cliente_name', $cliente_name)->get();
        
        // Obtener el saldo a favor del cliente
        $saldoAFavor = Customer::where('NOMBRE_COMERCIAL', $cliente_name)->value('saldo_a_favor') ?? 0;
    
        // Obtener todos los pagos asociados con las facturas de ese cliente
        $facturaIds = $facturas->pluck('id'); // Obtener una lista de todos los IDs de las facturas
        $pagos = Pago::whereIn('factura_id', $facturaIds)->get();
    
        return view('cuentas.cnc-detalle', [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'facturas' => $facturas,
            'cliente_name' => $cliente_name,
            'saldoAFavor' => $saldoAFavor,
            'pagos' => $pagos,  // Pasar los pagos a la vista
        ]);
    }
    
    

    public function create($factura_id)
    {
        $factura = Factura::findOrFail($factura_id);
        $cliente_name = $factura->cliente_name;
        $saldoAFavor = Customer::where('NOMBRE_COMERCIAL', $cliente_name)->value('saldo_a_favor') ?? 0;

        return view('cuentas.create', [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'factura' => $factura,
            'cliente_name' => $cliente_name,
            'saldoAFavor' => $saldoAFavor,
        ]);
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

        // Manejar el pago
        if ($montoPago >= $montoPendiente) {
            $this->registrarSaldoAFavor($factura, $montoPago, $montoPendiente, $request->referencia);
            $factura->estatus = 'Pagado';
        } else {
            Pago::create([
                'factura_id' => $factura->id,
                'monto' => $montoPago,
                'fecha_pago' => now(),
                'referencia' => $request->referencia,
            ]);
            $factura->estatus = 'Abonado';
        }
        
        $factura->save();

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
        $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
        if ($cliente) {
            $cliente->increment('saldo_a_favor', $saldoAFavor);
        }
    }

    public function usarSaldo(Request $request, Factura $factura)
    {
        // Verificar el cliente y su saldo a favor
        $cliente = Customer::where('NOMBRE_COMERCIAL', $factura->cliente_name)->first();
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        $saldoAFavor = $cliente->saldo_a_favor;
        $montoPendiente = $factura->montoPendiente();

        if ($saldoAFavor <= 0) {
            return redirect()->back()->with('error', 'No hay saldo a favor disponible.');
        }

        // Aplicar saldo a favor
        $montoCubierto = min($saldoAFavor, $montoPendiente);
        $cliente->decrement('saldo_a_favor', $montoCubierto);

        Pago::create([
            'factura_id' => $factura->id,
            'monto' => $montoCubierto,
            'fecha_pago' => now(),
            'referencia' => 'Saldo a favor'
        ]);

        // Cambiar el estatus de la factura
        $factura->estatus = $saldoAFavor >= $montoPendiente ? 'Pagado' : 'Abonado';
        $factura->save();

        return redirect()->back()->with('success', 'Saldo a favor utilizado como abono para pagar ' . ($factura->estatus == 'Pagado' ? 'completamente' : 'parcialmente') . ' la factura.');
    }

    public function pagos($factura_id)
    {
        $factura = Factura::findOrFail($factura_id);
        $pagos = $factura->pagos;

        return view('cuentas.index', [
            'menu' => "deudasPorCliente",
            'menu_sub' => "",
            'factura' => $factura,
            'pagos' => $pagos,
        ]);
    }

    public function pagarCompleto(Request $request, Factura $factura)
    {
        $request->validate([
            'referencia' => 'required|string|max:255',
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

    public function estadoCuenta($cliente_id)
    {
        // Obtener cliente por su ID
        $cliente = Customer::findOrFail($cliente_id);
    
        // Obtener todas las facturas del cliente, incluyendo los pagos
        $facturas = Factura::where('cliente_id', $cliente_id)->with('pagos')->get();
    
        // Calcular el saldo a favor
        $saldoAFavor = $cliente->saldo_a_favor;
    
        // Retornar vista con datos del cliente y sus facturas
        return view('cuentas.estado_cuenta', [
            'cliente_name' => $cliente->name,
            'facturas' => $facturas,
            'saldoAFavor' => $saldoAFavor
        ]);
    }
    
    public function createPDF()
    {
    $data = ['title' => 'Welcome to PDF Generation in Laravel!'];
    $pdf = PDF::loadView('pdf_view', $data);
    return $pdf->download('example.pdf');
    }

    public function descargarEstadoCuenta($cliente_name)
    {
        // Obtener las facturas del cliente
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
    
        // Generar el PDF con la vista y los datos
        $pdf = PDF::loadView('cuentas.estado_cuenta', $data);
    
        return $pdf->download('estado_cuenta_' . $cliente_name . '.pdf');
    }

    public function descargarEstadoDeCuentaPDF()
    {
        // Obtener datos de los clientes y sus facturas
        $clientes = Customer::with(['facturas', 'facturas.pagos'])->get();
        $deudasPorCliente = [];
        $totalSaldoRestante = 0;
        $totalSaldoAFavor = 0;

        foreach ($clientes as $cliente) {
            $saldoRestante = 0;
            $saldoAFavor = $cliente->saldo_a_favor;

            foreach ($cliente->facturas as $factura) {
                $pagosTotales = $factura->pagos->sum('monto');
                $saldoRestante += $factura->total - $pagosTotales;
            }

            $deudasPorCliente[] = [
                'cliente_name' => $cliente->NOMBRE_COMERCIAL,
                'saldoRestante' => $saldoRestante,
                'saldoAFavor' => $saldoAFavor,
            ];

            // Acumular totales
            $totalSaldoRestante += $saldoRestante;
            $totalSaldoAFavor += $saldoAFavor;
        }

        // Definir el nombre del archivo CSV
        $fileName = 'estado_de_cuenta_clientes.csv';

        // Crear la respuesta CSV
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Cliente', 'Saldo Restante', 'Saldo a Favor'];

        // Generar el CSV
        $callback = function() use ($deudasPorCliente, $columns, $totalSaldoRestante, $totalSaldoAFavor) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Agregar los datos de cada cliente
            foreach ($deudasPorCliente as $cliente) {
                fputcsv($file, [
                    $cliente['cliente_name'], 
                    number_format($cliente['saldoRestante'], 2, '.', ','), 
                    number_format($cliente['saldoAFavor'], 2, '.', ',')
                ]);
            }

            // Agregar una fila en blanco para separación
            fputcsv($file, []);

            // Agregar la suma total al final
            fputcsv($file, [
                'Totales', 
                number_format($totalSaldoRestante, 2, '.', ','), 
                number_format($totalSaldoAFavor, 2, '.', ',')
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function procesar(Request $request)
    {
        // Obtener las facturas seleccionadas del formulario
        $selectedFacturas = $request->input('selected_facturas', []);
        
        // Verificar si se seleccionaron facturas
        if (empty($selectedFacturas)) {
            return redirect()->back()->with('error', 'No se seleccionaron registros.');
        }
    
        // Validar que las facturas seleccionadas existan en la base de datos
        $request->validate([
            'selected_facturas' => 'required|array',
            'selected_facturas.*' => 'exists:fac_invoice,id',
            'cliente_name' => 'required|string', // Validación del nombre del cliente
        ]);
    
        // Obtener el nombre del cliente
        $clienteName = $request->input('cliente_name');
    
        // Obtener el saldo a favor del cliente
        $saldoAFavor = Customer::where('NOMBRE_COMERCIAL', $clienteName)->value('saldo_a_favor') ?? 0;
    
        // Inicializar el total a pagar
        $totalAPagar = 0;
    
        // Obtener las facturas seleccionadas
        $facturas = Factura::whereIn('id', $selectedFacturas)->get();
    
        // Calcular el total de las facturas seleccionadas
        foreach ($facturas as $factura) {
            $totalAPagar += $factura->total;
        }
    
        // Verificar si el saldo a favor del cliente es suficiente
        if ($totalAPagar > $saldoAFavor) {
            return response()->json(['error' => 'El saldo a favor no es suficiente para cubrir el total a pagar.']);
        }
    
        // Crear un nuevo registro de LotePago
        $complemento = random_int(100000, 999999); // Generar un número de 6 dígitos
        $lotePago = LotePago::create([
            'fecha' => now(),
            'cliente_id' => Customer::where('NOMBRE_COMERCIAL', $clienteName)->value('id'),
            'total_pago' => $totalAPagar,
            'complemento' => $complemento,
        ]);
    
        // Realizar el pago de las facturas y marcarlas como 'pagado'
        foreach ($facturas as $factura) {
            // Crear un nuevo registro de pago
            $pago = Pago::create([
                'factura_id' => $factura->id,
                'monto' => $factura->total,
                'fecha_pago' => now(),
                'referencia' => random_int(10000000, 99999999), // Generar un número aleatorio para la referencia
                'complemento' => $complemento, // Usar el mismo complemento para todas las facturas
                'lote_pago_id' => $lotePago->id,
            ]);
    
            // Cambiar el estado de la factura a 'pagado'
            $factura->estatus = 'pagado'; 
            $factura->save();
        }
    
        // Actualizar el saldo a favor del cliente
        $nuevoSaldo = $saldoAFavor - $totalAPagar;
        Customer::where('NOMBRE_COMERCIAL', $clienteName)->update(['saldo_a_favor' => $nuevoSaldo]);
    
        // Retornar una respuesta con éxito y redirigir a la vista de detalles del cliente
    return redirect()->route('cuentas.cnc-detalle', ['cliente_name' => $clienteName])
                     ->with('success', 'Los pagos han sido procesados correctamente.')
                     ->with('complemento', $complemento); // Pasar el complemento si es necesario
    }


    public function pagosPorCliente($clienteName) 
    {
        // Obtener los datos del cliente
        $cliente = Customer::where('NOMBRE_COMERCIAL', $clienteName)->first();
    
        // Verificar si el cliente existe
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }
    
        // Obtener los pagos del cliente y las facturas relacionadas
        $pagos = Pago::whereHas('factura', function($query) use ($cliente) {
            $query->where('cliente_id', $cliente->id);
        })->with('factura') // Carga la relación de factura para evitar consultas adicionales
          ->get()
          ->map(function($pago) {
              // Asegurarse de que 'fecha_pago' sea un objeto Carbon
              $pago->fecha_pago = Carbon::parse($pago->fecha_pago);
              return $pago;
          });
    
        // Calcular el total pagado
        $totalAPagar = $pagos->sum('monto');
    
        // Obtener la última factura relacionada
        $factura = $pagos->first() ? $pagos->first()->factura : null;
    
        // Crear una variable $data para devolver a la vista
        $data = [
            'menu' => "pagos",
            'menu_sub' => "",
            'cliente' => $cliente,
            'pagos' => $pagos,
            'totalAPagar' => $totalAPagar,
            'factura' => $factura,
            // Agregar el id del lote_pago si es necesario
            'lote_pago_id' => $pagos->first() ? $pagos->first()->lote_pago_id : null,
        ];
    
        // Retornar la vista con los datos
        return view('cuentas.pagos_por_cliente', $data);
    }
    
    
    public function descargarLote($id)
    {
        $lotePago = LotePago::with(['pagos.factura'])->findOrFail($id);
    
        // Convertir 'fecha_pago' a Carbon para cada pago
        foreach ($lotePago->pagos as $pago) {
            $pago->fecha_pago = Carbon::parse($pago->fecha_pago);
        }
    
        // Generar PDF usando una librería como dompdf o snappy
        $pdf = PDF::loadView('cuentas.pdf_detalle_lote', compact('lotePago'));
        
        return $pdf->download('lote_pago_' . $id . '.pdf');
    }
    
    

}
