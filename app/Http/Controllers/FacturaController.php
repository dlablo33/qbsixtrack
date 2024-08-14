<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;
use App\Customer;
use App\Product;
use App\Logistica;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class FacturaController extends Controller
{
    public function index()
    {
        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['facturas'] = Factura::orderBy('updated_at', 'DESC')->get();
        $data['clientes'] = Customer::all();
        $data['productos'] = Product::all();
        $data['logistica'] = Logistica::all();
        
        return view('facturas.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['clientes'] = Customer::all();
        $data['productos'] = Product::all();

        return view('facturas.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'cliente_name' => 'required',
            'producto_id' => 'required',
            'producto_name' => 'required',
            'cantidad' => 'required',
            'bol' => 'required',
            'trailer' => 'required',
            'fecha_create' => 'required|date',
            'due_fecha' => 'required|date',
            'total' => 'required',
            'estatus' => 'required',
        ]);

        Factura::create($request->all());

        return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente.');
    }

    public function edit($id)
    {
        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['factura'] = Factura::findOrFail($id);
        $data['clientes'] = Customer::all();
        $data['productos'] = Product::all();

        return view('facturas.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required',
            'cliente_name' => 'required',
            'producto_id' => 'required',
            'producto_name' => 'required',
            'cantidad' => 'required',
            'bol' => 'required',
            'trailer' => 'required',
            'fecha_create' => 'required|date',
            'due_fecha' => 'required|date',
            'total' => 'required',
            'estatus' => 'required',
        ]);

        $factura = Factura::findOrFail($id);
        $factura->update($request->all());

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada exitosamente.');
    }

    public function showPdf($id)
    {
        $factura = Factura::with('customer')->findOrFail($id);
    
        $data = [];
        $data['menu'] = "remisiones";
        $data['menu_sub'] = "";
        $data['factura'] = $factura;
    
        $dompdf = new Dompdf();
        $html = view('invoice.remi-pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->render();
    
        $pdfContent = $dompdf->output();
        $pdfDirectory = storage_path('app/temp');
    
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }
    
        $pdfPath = $pdfDirectory . '/remision.pdf';
        file_put_contents($pdfPath, $pdfContent);
    
        return response()->download($pdfPath, 'remision.pdf')->deleteFileAfterSend(true);
    }

    public function link(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $factura->Numero_Factura = $request->invoice_number;
        $factura->save();

        return redirect()->route('facturas.index')->with('success', 'Factura enlazada exitosamente.');
    }

    public function transferLogisticaToFactura()
    {
        $logisticasConPrecio = Logistica::whereNotNull('precio')->get();

        foreach ($logisticasConPrecio as $logistica) {
            $cliente = Customer::find($logistica->cliente);

            if ($cliente) {
                $factura = new Factura();
                $factura->cliente_id = $cliente->id;
                $factura->cliente_name = $cliente->NOMBRE_COMERCIAL; 
                $factura->producto_id = 1; 
                $producto = Product::findOrFail($factura->producto_id);
                $factura->producto_name = $producto->nombre;
                $factura->fecha_create = Carbon::now();
                $factura->due_fecha = Carbon::now()->addDays(30); 
                $factura->cantidad = $logistica->litros;
                $factura->bol = $logistica->bol;
                $factura->trailer = $logistica->no_pipa;
                $factura->total = $logistica->precio * $logistica->litros;
                $factura->created_at = Carbon::now();
                $factura->updated_at = Carbon::now();
                $factura->code_factura = $this->generarCodigoFactura();
                $factura->estatus = 'Pendiente';
                $factura->pedimento = $logistica->pedimento;
                $factura->precio = $logistica->precio;

                $factura->save();
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Datos transferidos con éxito');
    }

    private function generarNumeroFactura()
    {
        return 'FAC-' . uniqid();
    }

    private function generarCodigoFactura()
    {
        return 'CODE-' . uniqid();
    }
}
