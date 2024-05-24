<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MarchantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Marchant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Sabberworm\CSS\Settings;

Route::get('/invoices/show/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Route::get('/login', function () {
    return view('welcome');
})->middleware('guest');
Route::get('/register', function () {
    return view('auth/register');
})->middleware('guest');
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

Auth::routes();
Route::get('/test', 'TestingController@index');

Route::get('/change/password', 'ChangePasswordController@index')->middleware('auth')->name('change.password');
Route::post('/change/password/store', 'ChangePasswordController@store')->middleware('auth')->name('change.password.store');

Route::get('/settings', 'SettingsController@index')->middleware('auth')->name('settings');
Route::get('/cardknox', 'SettingsController@cardknoxIndex')->middleware('auth')->name('cardknox');
Route::get('/settings/edit/{id?}', 'SettingsController@edit')->middleware('auth')->name('settings.edit');
Route::get('/cardknox/edit/{id?}', 'SettingsController@cardknoxEdit')->middleware('auth')->name('cardknox.edit');
Route::post('/settings/store/{settings?}', 'SettingsController@store')->middleware('auth')->name('settings.store');
Route::post('/settings/cardknoxStore/{settings?}', 'SettingsController@cardknoxStore')->middleware('auth')->name('settings.cardknoxStore');
Route::get('/home', 'HomeController@index')->middleware('auth')->name('home');
Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');
Route::get('/customer', 'CustomerController@index')->middleware('auth')->name('customer');

Route::get('/journal', 'CustomerController@index')->middleware('auth')->name('journal');
Route::get('/journal/new', 'CustomerController@create')->middleware('auth')->name('journal.create');
Route::post('/journal/store', 'CustomerController@store')->middleware('auth')->name('journal.store');


// Products of QB
Route::get('/settings/products', 'ProductController@index')->name('products.index');
Route::get('/settings/products/create/{id?}', 'ProductController@create')->name('products.create');
Route::get('/settings/products/edit/{id}', 'ProductController@edit')->name('products.edit');
Route::put('/settings/products/update/{id}', 'ProductController@update')->name('products.update');
Route::get('/products/autocomplete', [ProductController::class, 'autocomplete'])->name('products.autocomplete');

Route::get('/settings/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
Route::post('/settings/products', 'ProductController@store')->name('products.store');

Route::get('/products/syncItems', 'ProductController@syncItems');


// Route for Quickbook Callback

Route::get('/quickbook/{user}/callback', 'QuickbookController@callback')->name('qb.callback');

//Rote for Marchant


Route::get('/marchants', [MarchantController::class, 'index'])->name('marchants.index');
Route::get('/marchants/create', [MarchantController::class, 'create'])->name('marchants.create');
Route::post('/marchants', [MarchantController::class, 'store'])->name('marchants.store');
Route::get('/marchants/edit/{id}', [MarchantController::class, 'edit'])->name('marchants.edit');
Route::put('/marchants/update/{id}', [MarchantController::class, 'update'])->name('marchants.update');

Route::delete('/marchants/destroy/{id}', [MarchantController::class, 'destroy'])->name('marchants.destroy');

//Route For payemnt Request
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');

Route::get('/invoices', [InvoiceController::class, 'invoiceList'])->name('invoice.invoice-list');
Route::get('/invoices/edit/{id}', [InvoiceController::class, 'edit'])->name('invoice.edit-invoice');
Route::put('/invoices/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');


// Customer routes
Route::prefix('customers')->group(function () {
    Route::get('/', 'CustomerController@index')->name('customers.index');
    Route::get('/create', 'CustomerController@create')->name('customers.create');
    Route::post('/', 'CustomerController@store')->name('customers.store');
    Route::get('/{id}/edit', 'CustomerController@edit')->name('customers.edit');
    Route::put('/{id}', 'CustomerController@update')->name('customers.update');
    Route::delete('/{id}', 'CustomerController@destroy')->name('customers.destroy');

    Route::get('/syncCustomers', 'CustomerController@syncCustomers');
});


Route::post('/webhook', 'WebhookController@index');
Route::get('/testGetCustomer', 'TestingController@testGetCustomer');

Route::get('/privacy', function () {
    return "<h3>Privacy Policy Page Coming Soon...</h3>";
})->name('privacy');

Route::get('/terms', function () {
    return "<h3>Terms & Condition Page Coming Soon...</h3>";
})->name('terms');

Route::get('/test-database', function () {
    try {
        DB::connection()->getPdo();
        print_r("Connected successfully to: " . DB::connection()->getDatabaseName());
    } catch (\Exception $e) {
        die("Could not connect to the database.  Please check your configuration. Error:" . $e );
    }
});

//propias
Route::post('/invoice/download', 'InvoiceController@download')->name('invoice.download');
Route::get('/invoice/{numeroFactura}/download-pdf', 'ItemController@downloadPDF')->name('item.download-pdf');

Route::get('settings/{id}/edit', [settingsController::class, 'edit'])->name('settings.edit');
Route::post('cardknox/{id}/update', 'settingsController@update')->name('cardknox.update');
Route::get('cardknox.index', 'settingsController@index')->name('cardknox.index');
Route::post('/product', 'ProductController@store')->name('product.store');
Route::get('/settings/products/create', 'ProductController@create');

Route::post('merchants', 'MerchantController@store');
// web.php
// routes/web.php
Route::get('/item/{NumeroFactura}/pdf', [ItemController::class, 'generatePDF'])->name('item.generatePDF');
Route::post('/item/{NumeroFactura}/email', [ItemController::class, 'sendEmail'])->name('item.sendEmail');
Route::get('/marchants/{id}/edit', [MarchantController::class, 'edit'])->name('marchants.edit');
Route::get('/marchants/{cliente_Id}/precios', 'MarchantController@show')->name('marchants.show');

// Ruta para manejar el envío del formulario y almacenar la factura
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoice.store');

Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');

// ==============================================================================================================================
Route::get('/precios/{cliente_id}/{product_id}', function ($cliente_id, $product_id) {
    // Implement your logic to retrieve the price data based on $customerId and $productId
    // Assuming you have a 'precios' table with 'cliente_id' and 'producto_id' columns
    $precio = Marchant::where('cliente_id', $cliente_id)
      ->where('producto_id', $product_id)
      ->first();
  
    // Return the price data in JSON format
    if ($precio) {
      return response()->json([$precio]);
    } else {
      // Handle no-price scenario (e.g., return empty array or appropriate error response)
      return response()->json([], 404); // Not Found
    }
  });

Route::get('/get-products-by-customer/{cliente_id}', [InvoiceController::class, 'getProductsByCustomer']);
Route::get('/get-prices-by-product-and-customer/{cliente_id}/{product_id}', [InvoiceController::class, 'getPricesByProductAndCustomer']);











