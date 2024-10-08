<?php

namespace App\Http\Controllers;

use App\Banco;
use Illuminate\Http\Request;
use App\EmpresaCuenta;
use App\Gasto;
use App\CurrencyConversion;
use Illuminate\Support\Facades\DB; 
use App\Traspaso;

class EmpresaCuentaController extends Controller
{
    public function index()
    {
        $cuentas = EmpresaCuenta::all();
        $bancos = Banco::all();
        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'cuentas' => $cuentas,
            'bancos' => $bancos
        ];
        return view('empresa_cuenta.index', $data);
    }

    public function agregarIngreso(Request $request)
    {
        $validatedData = $request->validate([
            'banco' => 'required|string',
            'ingreso_mxn' => 'nullable|numeric',
            'ingreso_usd' => 'nullable|numeric'
        ]);

        $ingreso_mxn = $validatedData['ingreso_mxn'] ?? 0;
        $ingreso_usd = $validatedData['ingreso_usd'] ?? 0;

        $comision_mxn = $ingreso_mxn * 0.035;
        $comision_usd = $ingreso_usd * 0.035;

        $saldo_final_mxn = $ingreso_mxn - $comision_mxn;
        $saldo_final_usd = $ingreso_usd - $comision_usd;

        EmpresaCuenta::create([
            'banco' => $validatedData['banco'],
            'ingreso_mxn' => $ingreso_mxn,
            'ingreso_usd' => $ingreso_usd,
            'comision_mxn' => $comision_mxn,
            'comision_usd' => $comision_usd,
            'saldo_final_mxn' => $saldo_final_mxn,
            'saldo_final_usd' => $saldo_final_usd
        ]);

        return redirect()->route('empresa_cuenta.index')->with('success', 'Ingreso agregado con éxito.');
    }

    public function showGastosForm()
    {
        $clasificaciones = [
            'Traspaso entre cuentas',
            'Reparto a socios',
            'Deposito cliente',
            'Traducciones Oficiales',
            'Sueldos',
            'Seguridad Social',
            'Satánicos',
            'Renta de oficina',
            'Derecho de cruce',
            'Costo por disposición en efectivo',
            'Pago facturas',
            'Donación',
            'Devolución',
            'Uxira',
            'Retenciones',
            'Licencia OFFICE',
            'Permiso Sener',
            'Préstamo',
            'Mensajería y paquetería',
            'Sueldos socios',
            'Transportes',
            'Gastos Aduanales',
            'Reparto de utilidad',
            'Equipo de cómputo',
            'Papelería',
            'Transportes',
            'Retenciones',
            'ST',
            'Equipo de oficina',
            'Multas',
            'Estacionamiento',
            'Comisión por sobre precio',
            'Abogada',
            'Estacionamiento'
        ];

        $beneficiarios = [
            'Sixtrack Internacional',
            'CABLIT SA DE CV',
            'Fernando Martínez Tirado',
            'Traducciones Oficiales',
            'Edgar Jayo de la Torre Blanco',
            'Kattia Janeth Oyervides Sandoval',
            'Jaime Rodriguez Chavez',
            'Alejandra Isabel Salas Vital',
            'Bancrea',
            'Amigos Estado',
            'Osmo Multiservicios SA de CV',
            'Costo por disposición en efectivo',
            'Malahueco S.P.R de R.L de C.V',
            'Satánicos',
            'Abel Hiram Duran Cisneros',
            'Vector',
            'Abogados',
            'Alberto Federico Aldama',
            'Fernando Martínez Gonzalez',
            'Traducciones oficiales',
            'Comercialización Estratégica Uxira',
            'Corporativo Art In Technology',
            'Energymss SA de CV',
            'Profcient Systems SAPI de CV',
            'DHL',
            'Comisión por venta STN',
            'PCEL',
            'Abel Gerardo Duran',
            'Adrian Lemothe Richer',
            'Venta de dólares',
            'Transportadora DUCACI',
            'Karen Yisell Paz Salinas',
            'Uxira',
            'Armando Sanchez Rodriguez',
            'Jose Luis Lumbreras Ibarra',
            'Genaro Treviño Hernández',
            'Jose Francisco Treviño Casas',
            'Santiago Josue Villegas Saldaña',
            'Grupo Industrial Eduni SA de CV',
            'Javier Duran Yañez',
            'Karla García',
            'Juan Alcocer Axtle',
            'MSF TRANSPORT SA DE CV',
            'International Forwarding LLC',
            'Río Bravo',
            'Trareysa'
        ];

        $bancos = EmpresaCuenta::all();

        return view('empresa_cuenta.gastos_form', [
            'menu' => 'Admin',
            'submenu' => '',
            'clasificaciones' => $clasificaciones,
            'beneficiarios' => $beneficiarios,
            'bancos' => $bancos
        ]);
    }

    public function storeGasto(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'clasificacion' => 'required|string|max:255',
            'beneficiario' => 'required|string|max:255',
            'banco' => 'required|exists:empresa_cuenta,id',
            'moneda' => 'required|string|in:MXN,USD',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|numeric|min:0'
        ]);

        $clasificacion = $validated['clasificacion'] === 'otro' ? $request->input('otro_clasificacion') : $validated['clasificacion'];
        $beneficiario = $validated['beneficiario'] === 'otro' ? $request->input('otro_beneficiario') : $validated['beneficiario'];

        // Crear el gasto
        $gasto = Gasto::create([
            'fecha' => $validated['fecha'],
            'clasificacion' => $clasificacion,
            'beneficiario' => $beneficiario,
            'descripcion' => $validated['descripcion'],
            'cantidad' => $validated['cantidad'],
            'banco_id' => $validated['banco'],
            'moneda' => $validated['moneda']
        ]);

        // Actualizar la cuenta de la empresa
        $this->actualizarCuentaEmpresa($validated['banco'], $validated['cantidad'], $validated['moneda']);

        return redirect()->route('empresa_cuenta.listaGastos')->with('success', 'Gasto registrado exitosamente.');
    }

    public function showGastos()
    {
        $gastos = Gasto::orderBy('fecha', 'desc')->get();
        $cuentas = EmpresaCuenta::all();
        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'cuentas' => $cuentas,
            'gastos' => $gastos,
        ];

        return view('empresa_cuenta.gastos', $data);
    }

    private function actualizarCuentaEmpresa($bancoId, $cantidad, $moneda)
    {
        $cuentaEmpresa = EmpresaCuenta::find($bancoId);

        if ($cuentaEmpresa) {
            if ($moneda === 'MXN') {
                $cuentaEmpresa->saldo_final_mxn -= $cantidad;
            } else {
                $cuentaEmpresa->saldo_final_usd -= $cantidad;
            }
            $cuentaEmpresa->save();
        }
    }

    // ========================================================================================================================================

    public function listaGastos()
    {
        $gastos = Gasto::all();
        $totalCantidad = $gastos->sum('cantidad');

        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'gastos' => $gastos,
            'totalCantidad' => $totalCantidad,
        ];

        return view('empresa_cuenta.lista_gastos', $data);
    }

    // ========================================================================================================================================


    public function convertCurrency(Request $request)
    {
        // Tasa de cambio obtenida dinámicamente del formulario
        $exchangeRate = $request->input('exchange_rate');
    
        // Validar el formulario
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from_currency' => 'required|in:MXN,USD',
            'to_currency' => 'required|in:MXN,USD',
            'exchange_rate' => 'required|numeric|min:0.01',
        ]);
    
        // Obtener los datos del formulario
        $amount = $request->input('amount');
        $fromCurrency = $request->input('from_currency');
        $toCurrency = $request->input('to_currency');
    
        // Asegurarse de que las monedas sean diferentes
        if ($fromCurrency === $toCurrency) {
            return back()->with('error', 'Las monedas de origen y destino deben ser diferentes.');
        }
    
        // Buscar la cuenta de la empresa
        $cuenta = EmpresaCuenta::first(); // Ajusta esto según tu lógica de selección de cuenta
    
        if (!$cuenta) {
            return back()->with('error', 'No se encontró una cuenta para realizar la conversión.');
        }
    
        // Lógica de conversión
        if ($fromCurrency == 'MXN' && $toCurrency == 'USD') {
            if ($amount > $cuenta->saldo_final_mxn) {
                return back()->with('error', 'Saldo insuficiente en pesos.');
            }
    
            // Realizar la conversión de MXN a USD
            $convertedAmount = $amount / $exchangeRate;
            $cuenta->saldo_final_mxn -= $amount; // Resta del saldo en pesos
            $cuenta->saldo_final_usd += $convertedAmount; // Suma al saldo en dólares
    
        } elseif ($fromCurrency == 'USD' && $toCurrency == 'MXN') {
            if ($amount > $cuenta->saldo_final_usd) {
                return back()->with('error', 'Saldo insuficiente en dólares.');
            }
    
            // Realizar la conversión de USD a MXN
            $convertedAmount = $amount * $exchangeRate;
            $cuenta->saldo_final_usd -= $amount; // Resta del saldo en dólares
            $cuenta->saldo_final_mxn += $convertedAmount; // Suma al saldo en pesos
        }
    
        // Guardar los cambios en la base de datos
        $cuenta->save();
    
        // Registrar la conversión en la tabla currency_conversions
        CurrencyConversion::create([
            'amount' => $amount,
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'exchange_rate' => $exchangeRate,
            'converted_amount' => $convertedAmount,
            'empresa_cuenta_id' => $cuenta->id, // Asignar la cuenta relacionada
        ]);
    
        // Retornar con éxito y mostrar el monto convertido
        return back()->with('success', 'Conversión realizada con éxito. Monto convertido: ' . number_format($convertedAmount, 2) . ' ' . $toCurrency);
    }
    
    public function transferFunds(Request $request)
    {
        $request->validate([
            'banco_origen' => 'required|exists:empresa_cuenta,id',
            'cantidad' => 'required|numeric|min:0',
            'banco_destino' => 'required|exists:empresa_cuenta,id',
            'moneda' => 'required|in:MXN,USD',
        ]);
    
        $bancoOrigen = EmpresaCuenta::find($request->banco_origen);
        $bancoDestino = EmpresaCuenta::find($request->banco_destino);
        $cantidad = $request->cantidad; 
        $moneda = $request->moneda;
    
        try {
            DB::transaction(function() use ($bancoOrigen, $bancoDestino, $cantidad, $moneda) {
                // Verificar saldo
                if ($moneda === 'MXN' && $bancoOrigen->saldo_final_mxn < $cantidad) {
                    throw new \Exception('Saldo insuficiente en MXN en el banco origen.');
                } elseif ($moneda === 'USD' && $bancoOrigen->saldo_final_usd < $cantidad) {
                    throw new \Exception('Saldo insuficiente en USD en el banco origen.');
                }
    
                // Actualizar saldos
                if ($moneda === 'MXN') {
                    $bancoOrigen->saldo_final_mxn -= $cantidad;
                    $bancoDestino->saldo_final_mxn += $cantidad;
                } else {
                    $bancoOrigen->saldo_final_usd -= $cantidad;
                    $bancoDestino->saldo_final_usd += $cantidad;
                }
    
                $bancoOrigen->save();
                $bancoDestino->save();
    
                // Registrar el traspaso
                Traspaso::create([
                    'banco_origen' => $bancoOrigen->id,
                    'banco_destino' => $bancoDestino->id,
                    'cantidad' => $cantidad,
                    'moneda' => $moneda,
                ]);
            });
    
            return back()->with('success', 'Transferencia realizada con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
}