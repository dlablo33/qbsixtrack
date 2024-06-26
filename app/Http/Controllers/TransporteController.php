<?php

namespace App\Http\Controllers;

use App\Transportista;
use App\Destino;
use App\Tarifa;
use Illuminate\Http\Request;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Twilio\Rest\Autopilot\V1\Assistant\Task\ReadSampleOptions;
use Twilio\Rest\Routes\V2\TrunkInstance;

class TransporteController extends Controller
{
    public function index()
    {
        $data = [];
        $tarifas = Tarifa::with('transportista', 'destino')->get();
        $data['menu'] = "tarifas";
        $data['menu_sub'] = "";
        $data['tarifas'] = $tarifas;
        return view('transporte.index', $data);
    }

    public function create()
    {
        $transportistas = Transportista::all();
        $destinos = Destino::all();

        return view('transporte.create', compact('transportistas', 'destinos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transportista_id' => 'required|exists:transportistas,id',
            'destino_id' => 'required|exists:destinos,id',
            'tar_usa' => 'numeric|nullable',
            'tar_mex' => 'numeric|nullable',
            'moneda' => 'required|string|in:MXN,USD',
            'tc_fijo' => 'required|numeric',
        ]);
    
        $transportista = Transportista::findOrFail($request->transportista_id);
        $destino = Destino::findOrFail($request->destino_id);
    
        $tarifa = new Tarifa();
        $tarifa->transportista_id = $request->transportista_id;
        $tarifa->tar_mex = $request->tar_mex;
        $tarifa->tar_usa = $request->tar_usa;
        $tarifa->destino_id = $request->destino_id;
        $tarifa->moneda = $request->moneda;
        $tarifa->tc_fijo = $request->tc_fijo;
        $tarifa->transportista_nombre = $transportista->nombre;
        $tarifa->destino_nombre = $destino->nombre;
    
        // Calcula la retención y el total a pagar
        if ($request->moneda == 'USD') {
            $tarifa->retencion = ($request->tar_usa * 0.04);
            $tarifa->iva = $request->tar_usa * 1.16;
        } else {
            $tarifa->retencion = ($request->tar_mex * 0.04);
            $tarifa->iva = $request->tar_mex * 1.16;
        }
    
        $tarifa->save();
    
        return redirect()->route('transporte.index')->with('success', 'Transporte creado exitosamente.');
    }
    

    public function edit($id)
    {
        $tarifa = Tarifa::findOrFail($id);
        $transportistas = Transportista::all();
        $destinos = Destino::all();

        return view('transporte.edit', compact('tarifa', 'transportistas', 'destinos'));
    }



    public function update(Request $request,$id)
    {
        // Validar los datos enviados
        $validatedData = $request->validate([
            'transportista_id' => 'integer',
            'transportista_nombre' => 'string',
            'destino_id' => 'integer',
            'destino_nombre' => 'string',
            'tar_usa' => '',
            'tar_mex' => '',
            'retencion' => '',
            'moneda' => 'string|max:3',
            'tc_fijo' => '',
            'iva' => '',
        ]);
        
        $transportista = Transportista::findOrFail($validatedData['transportista_id']);
        $destino = Destino::findOrFail($validatedData['destino_id']);

        $tarifa = Tarifa::findOrFail($id);
        $tarifa->transportista_id = $validatedData['transportista_id'];
        $tarifa->transportista_nombre = $transportista->nombre; // Asignar el nombre del transportista
        $tarifa->destino_id = $validatedData['destino_id'];
        $tarifa->destino_nombre = $destino->nombre;
        $tarifa->tar_usa = $validatedData['tar_usa'];
        $tarifa->tar_mex = $validatedData['tar_mex'];
        $tarifa->retencion = $validatedData['retencion'];
        $tarifa->moneda = $validatedData['moneda'];
        $tarifa->tc_fijo = $validatedData['tc_fijo'];
        $tarifa->iva = $validatedData['iva'];
    
        $tarifa->save();
    
        return redirect()->route('transporte.index')->with('success', 'Transporte actualizado exitosamente.');
    }
    


    public function destroy($id)
    {
        $tarifa = Tarifa::findOrFail($id);
        $tarifa->delete();

        return redirect()->route('transporte.index')->with('success', 'Transporte eliminado exitosamente.');
    }

}
