<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer; // Assuming your customer model
use App\Product; // Assuming your product model
use App\Precio; // Assuming your price model
use Illuminate\Support\Facades\Validator; // For data validation

class PriceController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'clv_producto' => 'required|integer|unique:precio', // Update table name
            'nombre' => 'required|string|max:255',
        ]);
      // OR return redirect()->route('products.show', $precio->id); // Redirect to product details
    }
    

}
