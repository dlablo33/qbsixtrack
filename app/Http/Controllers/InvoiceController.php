<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    // ... otros métodos del controlador ...

    public function index()
    {
        $invoices = Invoice::orderBy('last_updated_time', 'DESC')->get(); // Obtener todas las facturas, ordenadas por fecha

        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;

        return view('invoice.index', $data);
    }

    public function invoiceList()
    {
        $invoices = Invoice::orderBy('last_updated_time', 'DESC')->get();


        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;

        return view('invoice.invoice-list', $data);
    }

    public function show($numeroFactura)
    {
        $invoices = Invoice::where('id', $numeroFactura)->get();

        // Pasar las facturas a la vista
        return view('invoice.show', ['invoices' => $invoices]);

    }

    
}
