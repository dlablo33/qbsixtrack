<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Item;
use App\Marchant;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use App\Factura;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class InvoiceController extends Controller
{
    public function index()
    {
        $facturas = Factura::orderBy('updated_at', 'DESC')->get(); 

        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['facturas'] = $facturas;

        return view('invoice.index', $data);
    }

    public function create()
    {

        $precios = Marchant::all();
        $facturas = Factura::all();

        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['facturas'] = $facturas;

        return view('invoice.create', compact('precios', 'facturas'),$data);
    }

    public function store(Request $request)
    {
        try {
            // Validación de los datos recibidos
            $validatedData = $request->validate([

            ]);

            // Obtener el cliente
            $cliente_id = $request->input('customer_id');
            $cliente = Marchant::find($cliente_id);

            if (!$cliente) {
                return redirect()->back()->with('error', 'Cliente no encontrado.');
            }

            // Variables de cliente
            $cliente_name = $cliente->cliente_name;

            // Recorrer y guardar cada producto
            foreach ($request->input('product_id') as $index => $producto_id) {
                Factura::create([
                    'cliente_id' => $cliente_id,
                    'cliente_name' => $cliente_name,
                    'producto_id' => $producto_id,
                    'producto_name' => $request->input('product_name')[$index],
                    'total' => $request->input('unit_price')[$index] * $request->input('quantity')[$index],
                    'fecha_create' => $request->input('invoice_date'),
                    'due_date' => $request->input('due_date'),
                ]);
            }

            return redirect()->back()->with('success', 'Factura creada con éxito.');
        } catch (\Exception $e) {
            Log::error('Error al guardar la factura: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear la remisa.');
        }
    }
    
    public function getProductsByCustomer($cliente_id)
    {
        $productos = Marchant::where('cliente_id', $cliente_id)->distinct()->get(['producto_id', 'producto_name']);
        return response()->json($productos);
    }
    
    public function getPricesByProductAndCustomer($cliente_id, $producto_id)
    {
        $precios = Marchant::where('cliente_id', $cliente_id)
                         ->where('producto_id', $producto_id)
                         ->get(['id', 'precio']);
        return response()->json($precios);
    }
    

    public function invoiceList()
    {
        $invoices = Invoice::orderBy('last_updated_time', 'DESC')->get();
        
        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;
    
            // Pass data to the view
            return view('invoice.invoice-list', $data);
        
            }

            public function show($NumeroFactura)
            {
                $items = Item::where('NumeroFactura', $NumeroFactura)->get();
                // Pasar las facturas a la vista
                return view('invoice.show', ['items' => $items]);
            }

            public function items()
            {
                return $this->hasMany(Item::class);
            }
    
            public function download()
                {
                    $invoices = Invoice::all();

                         if (is_null($invoices)) {
                         return Response::make('No invoices found.', 404);
                         }

                $csv = Writer::createFromString('');
                $csv->insertOne(['ID', 'Numero de Factura', 'BOL', 'Trailer', 'Fecha', 'Total']);

                foreach ($invoices as $invoice) {
                        $csv->insertOne([
                        $invoice->id,
                        $invoice->NumeroFactura,
                        $invoice->bol,
                        $invoice->Trailer,
                        $invoice->last_updated_time,
                        $invoice->total_amt,
            ]);
    }


    return Response::make($csv->getContent(), 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="invoices.csv"',
        ]);
    }   

    }