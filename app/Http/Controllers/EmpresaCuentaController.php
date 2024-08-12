<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmpresaCuenta;
use App\Gasto;

class EmpresaCuentaController extends Controller
{
    public function index()
    {
        $cuentas = EmpresaCuenta::all();
        $data = [
            'menu' => 'Admin',
            'submenu' => '',
            'cuentas' => $cuentas,
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
}
