<?php

namespace App\Http\Controllers;

use App\Bluewi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Shuchkin\SimpleXLSX;
use PDO;
use DateTime;
use App\Invoice; 

class BluewiController extends Controller
{
    public function index(Request $request)
    {
        $query = Bluewi::query();
    
        if ($request->has('filter') && $request->input('filter') == 1) {
            $query->whereNull('bol_number')->orWhere('bol_number', '');
        }

        $query->orderBy('bol_date','desc');
    
        $bluewi = $query->paginate(10);
    
        $data = [
            'menu' => "bluewi",
            'menu_sub' => "",
            'bluewi' => $bluewi,
        ];
    
        return view("bluewi.index", $data);
    }
    
    public function showUploadForm()
    {
        $data = [
            'menu' => "bluewi",
            'menu_sub' => "",
        ];

        return view('bluewi.upload', $data);
    }

    public function upload(Request $request)
    {
        ini_set('max_execution_time', 300);

        $data = [
            'menu' => "bluewi",
            'menu_sub' => "",
        ];

        // Validar el archivo subido
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048', // Validar que sea archivo XLSX y máximo 2MB
        ]);

        // Verificar si el archivo es válido
        if ($request->file('file')->isValid()) {
            // Guardar el archivo en el almacenamiento público
            $filePath = $request->file('file')->store('public');
            Log::info('Archivo subido correctamente: ' . $filePath);

            // Procesar el archivo Excel
            $this->processExcel($filePath);
        }

        // Procesamiento exitoso
        return redirect()->back()->with('success', 'Archivo subido y procesado correctamente.')->with($data);
    }

    private function processExcel($filePath)
    {
        // Datos de conexión a la base de datos
        $database_name = 'railway';
        $user = 'root';
        $password = 'ftMKHcANburGUFcaNNnVMJqnLQZRBvGo';
        $host = 'viaduct.proxy.rlwy.net';
        $port = '11013';

        try {
            // Conectar a la base de datos MySQL
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database_name", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Cargar el libro de Excel
            $xlsx = SimpleXLSX::parse(storage_path('app/' . $filePath));

            if (!$xlsx) {
                Log::error('Error al leer el archivo Excel: ' . SimpleXLSX::parseError());
                return;
            }

            // Extraer la fila de encabezado (asumiendo que es la primera fila)
            $header_row = $xlsx->rows()[0];
            $escaped_header_row = array_map(function ($header) {
                return "`" . str_replace('`', '``', $header) . "`";
            }, $header_row);
            Log::info('Encabezados del archivo Excel:', $escaped_header_row);

            // Crear un mapeo para las columnas de Excel a columnas de la base de datos
            $column_map = [
                'Order Number' => 'order_number',
                'BOL#' => 'bol_number',
                'BOL Ver.' => 'bol_version',
                'Order Type' => 'order_type',
                'Status' => 'status',
                'BOL Date' => 'bol_date',
                'Position Holder' => 'position_holder',
                'Supplier' => 'supplier',
                'Customer' => 'customer',
                'Destination' => 'destination',
                'Carrier' => 'carrier',
                'PO' => 'po',
                'Truck' => 'truck',
                'Trailer' => 'trailer',
                'Bay' => 'bay',
                'Product' => 'product',
                'Scheduled Amount (USG)' => 'scheduled_amount_usg',
                'Gross(USG)' => 'gross_usg',
                'Net(USG)' => 'net_usg',
                'Temperature' => 'temperature',
                'Gravity' => 'gravity',
                'Tank' => 'tank',
            ];

            // Preparar las columnas para la consulta SQL
            $db_columns = array_map(function ($header) use ($column_map) {
                return $column_map[$header];
            }, $header_row);

            // Preparar la consulta INSERT con marcadores de posición
            $insert_query = sprintf(
                "INSERT INTO bluewis (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
                implode(', ', $db_columns),
                implode(', ', array_fill(0, count($db_columns), '?')),
                implode(', ', array_map(function ($col) {
                    return "$col = VALUES($col)";
                }, $db_columns))
            );

            // Insertar datos fila por fila (omitir la fila de encabezado)
            $stmt = $pdo->prepare($insert_query);
            foreach ($xlsx->rows() as $index => $row) {
                if ($index === 0) continue; // Saltar la fila de encabezado

                // Convertir la fecha al formato MySQL
                if (!empty($row[5])) {
                    $date = DateTime::createFromFormat('m/d/Y H:i:s', $row[5]);
                    if ($date) {
                        $row[5] = $date->format('Y-m-d H:i:s');
                    } else {
                        Log::error('Formato de fecha incorrecto en la fila ' . ($index + 1) . ': ' . $row[5]);
                        continue; // Saltar filas con formato de fecha incorrecto
                    }
                }

                // Registro de filas que se insertarán
                Log::info('Insertando fila:', $row);
                try {
                    $stmt->execute($row);
                } catch (PDOException $e) {
                    // Registro de error específico por fila
                    Log::error('Error en la fila ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }

            // Confirmar los cambios y cerrar la conexión
            $pdo = null;

            Log::info('Datos importados con éxito.');

        } catch (PDOException $e) {
            // Manejo de errores de conexión a la base de datos
            Log::error('Error de conexión a la base de datos: ' . $e->getMessage());
            throw $e; // Re-lanzar la excepción para manejarla en el método llamante
        } catch (Exception $e) {
            // Manejo de otros errores
            Log::error('Error al procesar el archivo Excel: ' . $e->getMessage());
            throw $e; // Re-lanzar la excepción para manejarla en el método llamante
        }
    }

    public function compareBol()
    {
    // Obtener todos los números de BOL de Invoice
    $invoiceBols = Invoice::pluck('bol')->toArray();

    // Obtener todos los registros de Bluewi
    $bluewi = Bluewi::all();

    // Filtrar los registros de Bluewi que no están en Invoice
    $notInInvoice = $bluewi->filter(function ($item) use ($invoiceBols) {
        return !in_array($item->bol_number, $invoiceBols);
    });

    // Devolver vista con los resultados
    $data = [
        'menu' => "bluewi",
        'menu_sub' => "",
        'notInInvoice' => $notInInvoice,
    ];

    return view('bluewi.not-in-invoice', $data);
    }

}
