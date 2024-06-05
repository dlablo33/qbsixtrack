<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Item;
use App\Precio;
use App\Marchant;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use App\Factura;
use Illuminate\Http\Request;

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

    // ========================================================================================================================================================================
    public function create()
    {

        $precios = Marchant::all();
        $facturas = Factura::all();
        $invoice = Invoice::all();

        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['facturas'] = $facturas;

        return view('invoice.create', compact('precios', 'facturas'),$data);
    }

    // ========================================================================================================================================================================
    
    public function store(Request $request)
    {
    // Validar los datos del formulario
    $request->validate([
    ]);

    $precio = Marchant::where('cliente_id', $request->input('customer_id'))
    ->where('producto_id', $request->input('product_id'))
    ->first();

if (!$precio) {
return redirect()->back()->withErrors(['price_id' => 'Precio no encontrado.']);
}

$cliente_name = $precio->cliente_name;
$producto_name = $precio->producto_name;

    // Crear una nueva instancia de Factura y guardar los datos
    $factura = new Factura();
    $factura->cliente_id = $request->input('customer_id');
    $factura->cliente_name = $cliente_name;
    $factura->producto_id = $request->input('product_id');
    $factura->producto_name = $producto_name;
    $factura->fecha_create = $request->input('invoice_date');
    $factura->due_fecha = $request->input('due_date');
    $factura->cantidad = $request->input('quantity'); 
    $factura->bol = $request->input('bol');
    $factura->Numero_Factura = $request->input('numeroFactura');
    $factura->trailer = $request->input('trailer');
    $factura->total = $request->input('total_before_discount');
    
    // Guardar la factura en la base de datos
    $factura->save();

    // Redireccionar con un mensaje de éxito
    return redirect()->route('invoice.index')->with('success', 'Factura creada exitosamente.');
 }
    
    // ========================================================================================================================================================================
    private function generateInvoiceNumber()
    {
        $latestInvoice = Factura::orderBy('created_at', 'desc')->first();
        if (!$latestInvoice) {
            return 'INV-0001';
        }
        $lastNumber = intval(substr($latestInvoice->numero_factura, 4));
        $newNumber = $lastNumber + 1;
        return 'INV-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    // ========================================================================================================================================================================
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

// =============================================================================================================================================================================================
                        public function getPriceByCustomer($cliente_id)
                         {
                               $precio = Marchant::where('cliente_id', $cliente_id)
                                 ->orderBy('updated_at', 'desc')
                                  ->first();
    
                                 return response()->json($precio->precio);
                        }

// =============================================================================================================================================================================================

    public function getLastPriceByCustomer(Request $request)
    {
        $clienteId = $request->get('cliente_id');
        
        $ultimoPrecio = Invoice::where('customer_id', $clienteId)
                                ->where('product_id', 1) 
                                ->orderBy('created_at', 'desc')
                                ->value('price') ?? 0;

        return response()->json(['price' => $ultimoPrecio]);
    }

// =============================================================================================================================================================================================
                    public function getLastPrice($customerId)
                    {
                        $lastPrice = \DB::table('precios')
                        ->where('cliente_id', $customerId)
                        ->orderBy('created_at', 'desc')
                        ->first();

                        return response()->json($lastPrice);
                    }
    
// =============================================================================================================================================================================================

    public function invoiceList()
    {
    $invoices = Invoice::orderBy('last_updated_time', 'DESC')->get();
        
    $data = [];
    $data['menu'] = "pagos";
    $data['menu_sub'] = "";
    $data['invoices'] = $invoices;
    
    return view('invoice.invoice-list', $data);
        
    }

// =============================================================================================================================================================================================
            public function invoiceList2()
            {
                $clientes = Marchant::all();

                $invoices = Invoice::where('item_names', 'PETROLEUM DISTILLATES')
                ->orderBy('last_updated_time', 'DESC')
                ->get();

                $data = [];
                $data['menu'] = "pagos";
                $data['menu_sub'] = "";
                $data['invoices'] = $invoices;
                $data['clientes'] = $clientes;

                return view('invoice.petrolio', $data);
                
            }
            
// =============================================================================================================================================================================================
            public function show($NumeroFactura)
            {
                $items = Item::where('NumeroFactura', $NumeroFactura)->get();
                return view('invoice.show', ['items' => $items]);
            }

// =============================================================================================================================================================================================

            
            public function remi($NumeroFactura)
            {
                $clientes = Marchant::all();
                $items = Item::where('NumeroFactura', $NumeroFactura)->get();
                return view('invoice.remi', ['items' => $items, 'clientes' => $clientes]);
            }

// =============================================================================================================================================================================================


            public function items()
            {
                return $this->hasMany(Item::class);
            }

// =============================================================================================================================================================================================
    
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
    
// =============================================================================================================================================================================================
public function updateStatus(Request $request, Invoice $invoice)
    {
        $invoice->estatus = $request->estatus;
        $invoice->save();
    
        session()->flash('status', 'Invoice status updated successfully!');
        return redirect()->route('invoices.index'); 
    }

// =============================================================================================================================================================================================

public function remi2($id)
{
    $factura = Factura::find($id);
    return view('factura.remi-pdf', compact('factura'));
}

    }