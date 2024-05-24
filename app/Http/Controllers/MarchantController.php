<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Marchant;
use App\Precio;
use App\Customer;
use App\Product;
use Illuminate\Http\Request;



class MarchantController extends Controller
{

    public function index()
    {
        $data = [];
        $marchants = Marchant::orderBy('id','DESC')->get();
        $data['menu'] = "marchant";
        $data['menu_sub'] = "";
        $data['marchants'] = $marchants;

        return view('marchants.index', $data);

    }

        public function create()
        {
            $clientes = Customer::all(); 
            $productos = Product::all(); 
            $precios = precio::all();
            return view('marchants.create', compact('clientes', 'productos'));
        }
    
        public function store(Request $request)
        {
            // Validación de datos
            $validatedData = $request->validate([
                'customer_id' => 'required',
                'product_id' => 'required',
                'price' => 'required|numeric',
            ]);
        

            // Separar el valor del cliente en clave y nombre
            $customerData = explode('|', $validatedData['customer_id']);
            $CVE_CTE = $customerData[0];
            $CLIENTE_LP = $customerData[1];

            $productData = explode('|', $validatedData['product_id']);
            $product_id = $productData[0];
            $product_name = $productData[1];
        
            // Crear una nueva instancia de Merchant
            $marchant = new Marchant();
        
            // Asignar los datos validados a las propiedades del objeto Merchant
            $marchant->cliente_id = $CVE_CTE;
            $marchant->cliente_name = $CLIENTE_LP;
            $marchant->producto_id = $product_id;
            $marchant->producto_name = $product_name;
            $marchant->precio = $validatedData['price'];
        
            // Guardar el objeto Merchant en la base de datos
            $marchant->save();
        
            // Redireccionar con un mensaje de éxito
            return redirect()->route('marchants.index')->with('success', 'Nuevo precio agregado correctamente');
        }
        

    public function edit($id)
    {
        $marchants = Marchant::findOrFail($id);
        $data['menu'] = "marchants";
        $data['menu_sub'] = "";
        $data['marchants'] = $marchants;

        return view('customers.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $marchants = Marchant::findOrFail($id);

        $validatedData = $request->validate([
            'customer_id' => 'required',
            'product_id' => 'required',
            'price' => 'required|numeric',
        ]);
    
        // Separar el valor del cliente en clave y nombre
        $customerData = explode('|', $validatedData['customer_id']);
        $CVE_CTE = $customerData[0];
        $CLIENTE_LP = $customerData[1];

        $productData = explode('|', $validatedData['product_id']);
        $product_id = $productData[0];
        $product_name = $productData[1];
    
        // Crear una nueva instancia de Merchant
        $marchant = new Marchant();
    
        // Asignar los datos validados a las propiedades del objeto Merchant
        $marchant->cliente_id = $CVE_CTE;
        $marchant->cliente_name = $CLIENTE_LP;
        $marchant->producto_id = $product_id;
        $marchant->producto_name = $product_name;
        $marchant->precio = $validatedData['price'];

        $marchant->save();
        
        // Redireccionar con un mensaje de éxito
        return redirect()->route('marchants.index')->with('success', 'Nuevo precio agregado correctamente');

    }

    public function show($cliente_id)
    {
        // Depuración: Verifica el ID del cliente
        \Log::info("Cliente ID recibido: " . $cliente_id);
    
        // Cambiar el nombre de la columna según la columna correcta en la tabla 'precios'
        $precios = Marchant::where('cliente_id', $cliente_id)->get(); // Usando el nombre correcto de la columna
    
        if ($precios->isEmpty()) {
            \Log::info("Cliente no encontrado: " . $cliente_id);
            return redirect()->route('marchants.index')->with('error', 'Cliente no encontrado.');
        }
    
        return view('marchants.show', ['precios' => $precios]);
    }
    
}