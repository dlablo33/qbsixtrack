<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\pdf as PDF;
use Illuminate\Support\Facades\Mail;

class ItemController extends Controller
{
    

    public function index()
    {
        $invoices = Item::orderBy('last_updated_time', 'DESC')->get(); // Obtener todas las facturas, ordenadas por fecha

        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;

        return view('invoice.index', $data);
    }

    public function show($numeroFactura)
    {
        $Items = Item::where('id', $numeroFactura)->get();

        // Pasar las facturas a la vista
        return view('invoice.show', ['Items' => $Items]);

    }

    public function generatePDF($numeroFactura) {
        // Obtén los datos necesarios para la factura según el número de factura
        $items = Item::where('NumeroFactura', $numeroFactura)->get();// Obtén los datos de la base de datos o donde estén almacenados
    
        // Carga la vista 'invoice' con los datos
        $pdf = PDF::loadView('invoice.pdf-template', compact('items'))->setOptions(['defaultFont' => 'sans-serif',['isPhpEnabled' => true]]);
    
        // Descarga el PDF con un nombre específico
        return $pdf->download('factura_'.$numeroFactura.'.pdf');
    }

public function sendEmail(Request $request, $numeroFactura)
{
    $items = Item::where('NumeroFactura', $numeroFactura)->get();
    
    // Utiliza PDF::loadView() para cargar la vista en DomPDF y generar el PDF
    $pdf = PDF::loadView('invoice.pdf-template', compact('items'))->setOptions(['defaultFont' => 'sans-serif',['isPhpEnabled' => true]])->output();

    // Envía el PDF por correo electrónico
    Mail::send([], [], function ($message) use ($request, $pdf) {
        $message->to($request->email)
                ->subject('Your Invoice')
                ->attachData($pdf, 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);
    });

    return redirect()->back();
}

    
    }
