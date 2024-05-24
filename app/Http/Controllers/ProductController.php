<?php

namespace App\Http\Controllers;

use App\Product;
use App\QBDataService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{

    public function index()
    {

        $data = [];
        $products = Product::orderBy('id','DESC')->get();
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['products'] = $products;



        return view('products.index', $data);
    }


    public function create($id= null)
    {
        // form
        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
//        $dataService = QBDataService::init();
//        $data['product'] = $dataService->FindById('item', $id);
//        $data['coas'] = $dataService->Query("SELECT * FROM Account");
        return view('products.create', $data);
    }


    public function store(Request $request)
    {
            $product = New Product();
            $product->nombre = $request->input('name');
            $product->clv_producto = $request->input('clv');
            $product->save();
    
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente!'); // Flash message for success
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $data['menu'] = "products";
        $data['menu_sub'] = "";
        $data['products'] = $product;
        

        return view('products.index', $data);

    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->nombre = $request->input('name');
        $product->clv_producto = $request->input('clv');

        $product->save();

        return redirect()->route('product.index')->with('success','Producto Actualizado');
    }


    public function syncItems()
    {
       
    }

}
