<?php

namespace App\Http\Controllers;

use App\Bol;
use App\Customer;
use App\Tarifa;
use App\Invoice;
use Illuminate\Http\Request;

class BolController extends Controller
{
    public function index()
    {
        $invoices = Invoice::whereIn('item_names', ['PETROLEUM DISTILLATES', 'TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL', 'OPERATION ADJUSTED'])
            ->orderBy('last_updated_time', 'DESC')
            ->get()
            ->groupBy('bol');

        $clientes = Customer::all();
        $transportes = Tarifa::all();
        $bolDetails = Bol::all();

        $data = [];
        $data['menu'] = "bol";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;
        $data['clientes'] = $clientes;
        $data['transportes'] = $transportes;
        $data['bolDetails'] = $bolDetails;

        return view('bol.index', $data);
    }


    public function updateTransporte(Request $request, $bol)
    {
        $request->validate([
            'transporte_id' => '',
        ]);
    
        // Find the invoices associated with the BOL
        $invoices = Invoice::where('bol', $bol)->get();
    
        // Initialize variables to store total amounts and invoice IDs
        $facturaId1 = null;
        $facturaId2 = null;
        $facturaId3 = null;
        $totalFactura1 = 0;
        $totalFactura2 = 0;
        $totalFactura3 = 0;
        $trailer = null;
    
        // Loop through the invoices and get the necessary details
        foreach ($invoices as $invoice) {
            if ($invoice->item_names == 'PETROLEUM DISTILLATES') {
                $facturaId1 = $invoice->id;
                $totalFactura1 = $invoice->total_amt;
            } elseif ($invoice->item_names == 'TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL') {
                $facturaId2 = $invoice->id;
                $totalFactura2 = $invoice->total_amt;
            } elseif ($invoice->item_names == 'OPERATION ADJUSTED') {
                $facturaId3 = $invoice->id;
                $totalFactura3 = $invoice->total_amt;
            }
    
            $trailer = $invoice->Trailer;
        }
    
        // Find the transporte details
        $transporte = Tarifa::findOrFail($request->transporte_id);
        $totalTransporte = $transporte->iva;
        
        // Calculate the final total
        $totalFinal = $totalFactura1 + $totalFactura2 + $totalFactura3 + $totalTransporte;
    
        // Update or create the bol_details entry
        Bol::updateOrCreate(
            ['numero_bol' => $bol],
            [
                'trailer' => $trailer,
                'factura_id_1' => $facturaId1,
                'factura_id_2' => $facturaId2,
                'factura_id_3' => $facturaId3,
                'total_factura_1' => $totalFactura1,
                'total_factura_2' => $totalFactura2,
                'total_factura_3' => $totalFactura3,
                'transporte_id' => $request->transporte_id,
                'total_transporte' => $totalTransporte,
                'total_final' => $totalFinal,
            ]
        );
    
        return redirect()->route('bol.index')->with('success', 'Transporte asignado exitosamente.');
    }
    
    public function updateCliente(Request $request, $bol)
    {
        $request->validate([
            'cliente_id' => '',
        ]);
    
        $bolDetail = Bol::where('numero_bol', $bol)->firstOrFail();
        $bolDetail->cliente_id = $request->cliente_id;
        $bolDetail->save();
    
        return redirect()->route('bol.index')->with('success', 'Cliente asignado exitosamente.');
    }
    

    
    public function showPairForm()
    {
        $bols = Bol::all();
        $clientes = Customer::all();
        $transportes = Tarifa::all();
        
        return view('bol.pair', compact('bols', 'clientes', 'transportes'));
    }

    public function pair(Request $request)
    {
        $request->validate([
            'numero_bol' => 'required|exists:bols,id',
            'cliente_id' => 'required|exists:clientes,id',
            'transporte_id' => 'required|exists:transportes,id',
        ]);

        $bol = Bol::findOrFail($request->numero_bol);
        $transporte = Tarifa::findOrFail($request->transporte_id);

        $bol->cliente_id = $request->cliente_id;
        $bol->transporte_id = $request->transporte_id;

        // Calcula el total dependiendo del transporte
        $tarifa = Tarifa::where('transporte_id', $transporte->id)->first();
        if ($tarifa) {
            $total = $tarifa->tar_usa ? $tarifa->tar_usa * 1.16 : $tarifa->tar_mex * 1.16;
            $bol->total = $total;
        }

        $bol->save();

        return redirect()->route('bol.pair.form')->with('success', 'BOL emparejado exitosamente.');
    }

}
