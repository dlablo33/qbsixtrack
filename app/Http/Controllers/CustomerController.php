<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;



class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('id', 'DESC')->get();

        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['customers'] = $customers;

        return view('customers.index', $data);
    }

    public function create($id = null)
    {

        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $customers = Customer::all();

        return view('customers.create', $data);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $data['menu'] = "customers";
        $data['menu_sub'] = "";
        $data['customer'] = $customer;

        return view('customers.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
        // Encuentra el cliente por su ID
        $customer = Customer::findOrFail($id);
        
        // Actualiza los campos del cliente con los valores de la solicitud
        $customer->CLIENTE_LP = $request->input('clp');
        $customer->NOMBRE_COMERCIAL = $request->input('nc');
        $customer->STATUS = $request->input('STATUS') ? 'Activo' : 'Inactivo';
        $customer->RFC = $request->input('rfc');
        $customer->RAZON_SOCIAL = $request->input('rs');
        $customer->EMPRESA_VENDEDORA = $request->input('ev');
        $customer->CODIGO_CUENTA_CONTABLE = $request->input('ccc');
        $customer->CODIGO_CLIENTE_COMERCIAL = $request->input('cco'); // Asigna a otro campo
        $customer->DENOMINACION_SERIAL = $request->input('ds');
        $cve_cte = $request->input('ccc') . $request->input('cco') . $request->input('ds');
        $customer->CVE_CTE = $cve_cte;
        
        // Guarda los cambios en la base de datos
        $customer->save();
    
        // Redirige a la página de índice de clientes u otra página según necesites
        return redirect()->route('customers.index')->with('success', 'Cliente actualizado correctamente');
    }

    public function store(Request $request)
    {
                // Validar los datos de entrada
                $request->validate([
                    'clp' => 'required|string|max:255',
                    'nc' => 'nullable|string|max:255',
                    'status' => 'nullable|boolean',
                    'rfc' => 'nullable|string|max:255',
                    'rs' => 'nullable|string|max:255',
                    'ev' => 'nullable|string|max:255',
                    'ccc' => 'nullable|string|max:255',
                    'cco' => 'nullable|string|max:255',
                    'ds' => 'nullable|string|max:255',
                ]);
        
                // Crear una nueva instancia de Customer
                $customer = new Customer();
                $customer->CLIENTE_LP = $request->input('clp');
                $customer->NOMBRE_COMERCIAL = $request->input('nc');
                $customer->STATUS = $request->has('status') ? 'Inactivo' : 'Activo';
                $customer->RFC = $request->input('rfc');
                $customer->RAZON_SOCIAL = $request->input('rs');
                $customer->EMPRESA_VENDEDORA = $request->input('ev');
                $customer->CODIGO_CUENTA_CONTABLE = $request->input('ccc');
                $customer->CODIGO_CLIENTE_COMERCIAL = $request->input('cco');
                $customer->DENOMINACION_SERIAL = $request->input('ds');
                $cve_cte = $request->input('ccc') . $request->input('cco') . $request->input('ds');
                $customer->CVE_CTE = $cve_cte;

                $customer->save();
        
                // Redirigir a la página de índice de clientes con un mensaje de éxito
                return redirect()->route('customers.index')->with('success', 'Cliente creado correctamente');
            }
    }
    
    