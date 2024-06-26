<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Item;
use PDF; 
use App\Marchant;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use App\Factura;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use ZipArchive;
use Carbon\Carbon;
use App\Tarifa;

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

    // =======================================================================================================================================================================================
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

    // =======================================================================================================================================================================================
    
    public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required',
        'product_id' => 'required',
        'price_id' => 'required',

    ]);

    // Obtener el precio seleccionado
    $price = Marchant::find($request->input('price_id'));

    if (!$price) {
        return redirect()->back()->withErrors(['price_id' => 'Precio no encontrado.']);
    }

    // Crear una nueva instancia de Factura y guardar los datos
    $factura = new Factura();
    $factura->cliente_id = $request->input('customer_id');
    $factura->cliente_name = $price->cliente_name;
    $factura->producto_id = $request->input('product_id');
    $factura->producto_name = $price->producto_name;
    $factura->fecha_create = $request->input('invoice_date');
    $factura->due_fecha = $request->input('due_date');
    $factura->cantidad = $request->input('quantity'); 
    $factura->bol = $request->input('bol');
    $factura->Numero_Factura = $request->input('numeroFactura');
    $factura->trailer = $request->input('trailer');
    $factura->total = $request->input('total_before_discount');

    // Solo asignar num_fac si está presente en la solicitud
    if ($request->has('num_fac')) {
        $factura->code_factura = $request->input('num_fac');
    }

    // Guardar la factura en la base de datos
    $factura->save();

    // Actualizar la instancia de Invoice relacionada con esta factura

    // Redireccionar con un mensaje de éxito
    return redirect()->route('invoice.index')->with('success', 'Factura creada exitosamente.');
    }

    // =========================================================================================================================================================================================
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
    
    // =========================================================================================================================================================================================
    public function getProductsByCustomer($cliente_id)
    {
        $productos = Marchant::where('cliente_id', $cliente_id)->distinct()->get(['producto_id', 'producto_name']);
        return response()->json($productos);
    }
    
// =============================================================================================================================================================================================

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
    public function updateStatus(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);
    $invoice->estatus = $request->input('estatus');
    $invoice->save();

    return redirect()->back()->with('status', 'Estatus de la factura actualizado correctamente.');
    }

// =============================================================================================================================================================================================

    public function remi2($id)
{
    $factura = Factura::find($id);
    return view('factura.remi-pdf', compact('factura'));
    }
// =============================================================================================================================================================================================


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

// =============================================================================================================================================================================================

    public function sendPdf(Request $request, $id)
{
    // Validar la entrada
    $request->validate([
        'email' => 'required|email'
    ]);

    // Encontrar la factura
    $factura = Factura::find($id);

    // Verificar si la factura existe
    if (!$factura) {
        return redirect()->back()->with('error', 'Factura no encontrada.');
    }

    // Generar el PDF
    $pdf = PDF::loadView('invoice.remi-pdf', compact('factura'));

    // Enviar el correo
    Mail::to($request->email)->send(new InvoiceMail($factura, $pdf));

    return redirect()->back()->with('success', 'Correo enviado exitosamente.');
    }

// =============================================================================================================================================================================================

    public function linkInvoice(Request $request, $id)
{
    $request->validate([
        // Puedes agregar reglas de validación adicionales según sea necesario
    ]);

    // Encuentra la factura por su ID
    $factura = Factura::findOrFail($id);

    // Verifica si la factura ya tiene un número de factura vinculado
    if ($factura->code_factura !== null) {
        return redirect()->back()->withErrors(['message' => 'Esta remisión ya está enlazada a una factura.']);
    }

    // Asigna el número de factura a la remisión y guárdala en la base de datos
    $factura->code_factura = $request->input('invoice_number');
    $factura->save();

    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'La remisión se enlazó correctamente a la factura.');
    }

// =============================================================================================================================================================================================

    public function deleteInvoice($id)
{
    // Encuentra la remisión por su ID
    $factura = Factura::findOrFail($id);

    // Elimina la remisión de la base de datos
    $factura->delete();

    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'La remisión se eliminó correctamente.');
    }

// =============================================================================================================================================================================================

    public function store2(Request $request)
{
    // Validar los datos del formulario
    $validatedData = $request->validate([
        'customer_id' => 'required',
        'product_id' => 'required',
        'price_id' => 'required',
    ]);
    $price = Marchant::find($request->input('price_id'));

    if (!$price) {
        return redirect()->back()->withErrors(['price_id' => 'Precio no encontrado.']);
    }

    // Crea una nueva factura con los datos del formulario
    $factura = new Factura();
    $factura->cliente_id = $request->input('customer_id');
    $factura->cliente_name = $price->cliente_name;
    $factura->producto_id = $request->input('product_id');
    $factura->producto_name = $price->producto_name;
    $factura->cantidad = implode('.', $request->input('quantity'));
    $factura->bol = $request->input('bol');
    $factura->trailer = $request->input('trailer');
    $factura->fecha_create = $request->input('invoice_date');
    $factura->due_fecha = $request->input('due_date');
    $factura->total = $request->total_before_discount;
    $factura->Numero_Factura = $request->input('invoice_number'); // Ajusta esto según tus necesidades
    $factura->save();


    if ($request->has('num_fac')) {
        $factura->code_factura = $request->input('num_fac');
    }

    // Redirecciona a una página de éxito o realiza otra acción según tus necesidades
    return redirect()->route('invoice.index')->with('success', '¡La factura se ha creado correctamente!');
    }

// =============================================================================================================================================================================================

    public function karen(Request $request)
{
    // Capturar la fecha desde el request
    $fecha = $request->input('date');

    // Convertir la fecha de YYYY-MM-DD a DD-MM-YY
    $fechaConvertida = Carbon::createFromFormat('Y-m-d', $fecha)->format('d-m-y');

    // Obtener todos los invoices de la fecha especificada
    $invoices = Item::whereRaw("DATE_FORMAT(last_updated_time, '%d-%m-%y') = ?", [$fechaConvertida])->get();

    // Verificar si se encontraron invoices
    if ($invoices->isEmpty()) {
        return response()->json(['message' => 'No hay invoices para la fecha especificada.'], 404);
    }

    // Generar y guardar los PDF para cada invoice encontrado
    foreach ($invoices as $invoice) {
        $pdf = PDF::loadView('invoice.pdf-template', compact('invoice'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->save(storage_path('app/public/invoices/factura_' . $invoice->id . '.pdf'));
    }

    // Comprimir y descargar la carpeta con todos los PDF generados
    return $this->zipAndDownloadInvoices($invoices, $fechaConvertida);
    }

// =============================================================================================================================================================================================

    private function zipAndDownloadInvoices($invoices, $fecha)
    {
    try {
        $zipFileName = 'invoices_' . $fecha . '.zip';
        $zip = new ZipArchive;
        $zip->open(storage_path('app/public/' . $zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($invoices as $invoice) {
            $pdfFilePath = storage_path('app/public/invoices/factura_' . $invoice->id . '.pdf');
            $zip->addFile($pdfFilePath, 'factura_' . $invoice->id . '.pdf');
        }

        $zip->close();

        // Descargar el archivo ZIP generado
        return response()->download(storage_path('app/public/' . $zipFileName))->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al comprimir los invoices en ZIP: ' . $e->getMessage()], 500);
    }
    }

    // =============================================================================================================================================================================================

    public function invoiceList3()
    {
        $invoices = Invoice::whereIn('item_names', ['TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL'])
            ->orderBy('last_updated_time', 'DESC')
            ->get();

        $data = [];
        $data['menu'] = "pagos2";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;

        return view('invoice.mole2', $data);
    }

    // =============================================================================================================================================================================================

    public function invoiceList4()
    {
        $invoices = Invoice::whereIn('item_names', ['OPERATION ADJUSTED'])
            ->orderBy('last_updated_time', 'DESC')
            ->get();

        $data = [];
        $data['menu'] = "pagos2";
        $data['menu_sub'] = "";
        $data['invoices'] = $invoices;

        return view('invoice.mole2', $data);
    }

}