<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Factura;
use App\Pago;
use App\Invoice;
use App\Customer;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalCuentasPorPagar = Factura::sum('total');
        
        $pagadoEstaSemana = Pago::whereBetween('fecha_pago', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                ->sum('monto');
    
        $facturasSinPagar = Factura::whereIn('estatus', ['Pendiente', 'Abonado'])->count();
    
        $saldoAFavor = Customer::sum('saldo_a_favor');

        $invoicesPorDia = Invoice::whereBetween('create_time', [Carbon::now()->subWeek()->startOfDay(), Carbon::now()->endOfDay()])
        ->select(DB::raw('DATE(create_time) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('count', 'date')
        ->toArray();

    // Asegurarse de que todos los días de la última semana están representados
    $invoicesData = [];
    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->toDateString();
        $dates[] = $date;
        $invoicesData[] = $invoicesPorDia[$date] ?? 0;
    }
    
        $data = [
            'totalCuentasPorPagar' => $totalCuentasPorPagar,
            'pagadoEstaSemana' => $pagadoEstaSemana,
            'facturasSinPagar' => $facturasSinPagar,  // Asegúrate de incluir esto en el arreglo $data
            'saldoAFavor' => $saldoAFavor,
            'invoicesData' => $invoicesData,
            'dates' => $dates,
        ];
        
        $data['menu'] = "home";
        $data['menu_sub'] = "";
    
        return view('dashboard', $data);
    }
}
