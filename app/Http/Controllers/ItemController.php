<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;


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

}