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
        
        $facturas = Factura::orderBy('updated_at', 'DESC')->get(); 

        $facturas = Factura::all();
        $clientes = Customer::all();
        $productos = Product::all();
        $logistica = Logistica::all();


        $data = [];
        $data['menu'] = "pagos";
        $data['menu_sub'] = "";
        $data['facturas'] = $facturas;
        $data['clientes'] = $clientes;
        $data['productos'] = $productos;
        $data['logistica'] = $logistica;
        
        return view('facturas.index', $data);
    }

    public function create()
    {
        $clientes = Customer::all();
        $productos = Product::all();

        return view('facturas.create', compact('clientes', 'productos'));
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
        $factura = Factura::findOrFail($id);
        $clientes = Customer::all();
        $productos = Product::all();

        return view('facturas.edit', compact('factura', 'clientes', 'productos'));
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
    
            // Preparar los datos para la vista
            $data = [];
            $data['menu'] = "remisiones";
            $data['menu_sub'] = "";
            $data['factura'] = $factura;
    
            // Crear una instancia de Dompdf
            $dompdf = new Dompdf();
    
            // Cargar la vista con los datos necesarios
            $html = view('invoice.remi-pdf', $data)->render();
    
            // Generar el PDF
            $dompdf->loadHtml($html);
    
            // Renderizar el PDF
            $dompdf->render();
    
            // Obtener el contenido del PDF
            $pdfContent = $dompdf->output();
    
            // Directorio donde se almacenará el archivo PDF
            $pdfDirectory = storage_path('app/temp');
    
            // Verificar si el directorio existe, si no, crearlo
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }
    
            // Nombre y ruta completa del archivo PDF
            $pdfPath = $pdfDirectory . '/remision.pdf';
    
            // Guardar el PDF en el directorio
            file_put_contents($pdfPath, $pdfContent);
    
            // Descargar el PDF
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
        // Obtener todos los registros de Logistica que ya tienen precio
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
        // Lógica para generar un número de factura único
        return 'FAC-' . uniqid();
    }

    private function generarCodigoFactura()
    {
        // Lógica para generar un código de factura único
        return 'CODE-' . uniqid();
    }

}
