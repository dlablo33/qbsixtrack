<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MarchantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\BluewiController;
use App\Marchant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransporteController;
use App\Http\Controllers\BolController;
use App\Http\Controllers\LogisticaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MoleculaController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\EmpresaCuentaController;
use App\Http\Controllers\TipoCambioController;
use App\Http\Controllers\QuickBooksController;
// ==============================================================================================================================================================================================
Route::get('/invoices/show/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
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
// ==============================================================================================================================================================================================
Auth::routes();
Route::get('/test', 'TestingController@index');
Route::get('/cuentas/password', 'ChangePasswordController@index')->middleware('auth')->name('change.password');
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
// ==============================================================================================================================================================================================
Route::get('/settings/products', 'ProductController@index')->name('products.index');
Route::get('/settings/products/create/{id?}', 'ProductController@create')->name('products.create');
Route::get('/settings/products/edit/{id}', 'ProductController@edit')->name('products.edit');
Route::put('/settings/products/update/{id}', 'ProductController@update')->name('products.update');
Route::get('/products/autocomplete', [ProductController::class, 'autocomplete'])->name('products.autocomplete');
Route::get('/settings/products/destroy/{id}', 'ProductController@destroy')->name('products.destroy');
Route::post('/settings/products', 'ProductController@store')->name('products.store');
Route::get('/products/syncItems', 'ProductController@syncItems');
// ==============================================================================================================================================================================================
Route::get('/quickbook/{user}/callback', 'QuickbookController@callback')->name('qb.callback');
// ==============================================================================================================================================================================================
Route::get('/marchants', [MarchantController::class, 'index'])->name('marchants.index');
Route::get('/marchants/create', [MarchantController::class, 'create'])->name('marchants.create');
Route::post('/marchants', [MarchantController::class, 'store'])->name('marchants.store');
Route::get('/marchants/edit/{id}', [MarchantController::class, 'edit'])->name('marchants.edit');
Route::put('/marchants/update/{id}', [MarchantController::class, 'update'])->name('marchants.update');
Route::delete('/marchants/destroy/{id}', [MarchantController::class, 'destroy'])->name('marchants.destroy');
// ==============================================================================================================================================================================================
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoices', [InvoiceController::class, 'invoiceList'])->name('invoice.invoice-list');
Route::get('/invoices/edit/{id}', [InvoiceController::class, 'edit'])->name('invoice.edit-invoice');
Route::put('/invoices/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
Route::get('/invoices/Molecula_1', [InvoiceController::class, 'invoiceList2'])->name('invoice.petrolio');
Route::get('/invoices/Molecula_2', [InvoiceController::class, 'invoiceList3'])->name('invoice.mole2');
Route::get('/invoices/Molecula_3', [InvoiceController::class, 'invoiceList4'])->name('invoice.mole3');
Route::get('/invoices/remicion/{id}', [InvoiceController::class, 'remi'])->name('invoice.remi');
Route::post('/invoice/update-status/{id}', [InvoiceController::class, 'updateStatus'])->name('invoice.update.status');
// ==============================================================================================================================================================================================
Route::prefix('customers')->group(function () {
    Route::get('/', 'CustomerController@index')->name('customers.index');
    Route::get('/create', 'CustomerController@create')->name('customers.create');
    Route::post('/', 'CustomerController@store')->name('customers.store');
    Route::get('/{id}/edit', 'CustomerController@edit')->name('customers.edit');
    Route::put('/{id}', 'CustomerController@update')->name('customers.update');
    Route::delete('/{id}', 'CustomerController@destroy')->name('customers.destroy');
    Route::get('/syncCustomers', 'CustomerController@syncCustomers');
});
// ==============================================================================================================================================================================================
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
// ==============================================================================================================================================================================================
Route::post('/invoice/download', 'InvoiceController@download')->name('invoice.download');
Route::get('/invoice/{numeroFactura}/download-pdf', 'ItemController@downloadPDF')->name('item.download-pdf');
Route::get('settings/{id}/edit', [settingsController::class, 'edit'])->name('settings.edit');
Route::post('cardknox/{id}/update', 'settingsController@update')->name('cardknox.update');
Route::get('cardknox.index', 'settingsController@index')->name('cardknox.index');
Route::post('/product', 'ProductController@store')->name('product.store');
Route::get('/settings/products/create', 'ProductController@create');
Route::post('merchants', 'MerchantController@store');
Route::get('/item/{NumeroFactura}/pdf', [ItemController::class, 'generatePDF'])->name('item.generatePDF');
Route::post('/item/{NumeroFactura}/email', [ItemController::class, 'sendEmail'])->name('item.sendEmail');
Route::get('/marchants/{id}/edit', [MarchantController::class, 'edit'])->name('marchants.edit');
Route::get('/marchants/{cliente_Id}/precios', 'MarchantController@show')->name('marchants.show');
// ==============================================================================================================================================================================================
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::get('factura/{id}/ver-pdf', [App\Http\Controllers\InvoiceController::class, 'verPDF'])->name('invoice.ver-pdf');
// ==============================================================================================================================================================================================
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
// ==============================================================================================================================================================================================
  Route::get('/Cuentas/Index', 'PagoController@index')->name('cuentas.index');
  Route::get('/Cuentas/index', [PagoController::class, 'index']);
  Route::get('/clientes/{cliente_name}/detalles', [PagoController::class, 'show'])->name('cuentas.cnc-detalle');
  Route::get('/facturas/{factura}/pagos/create', [PagoController::class, 'create'])->name('cuentas.create');
Route::post('/pagos', [PagoController::class, 'store'])->name('cuentas.store');
Route::post('/facturas/{factura}/pagar-completo', [PagoController::class, 'pagarCompleto'])->name('cuentas.pagarCompleto');
Route::post('/usar-saldo/{factura}', 'PagoController@usarSaldo')->name('cuentas.usarSaldo');
// ============================================================================================================================================================================================== 
Route::get('/get-products-by-customer/{cliente_id}', [InvoiceController::class, 'getProductsByCustomer']);
Route::get('/get-prices-by-product-and-customer/{cliente_id}/{product_id}', [InvoiceController::class, 'getPricesByProductAndCustomer']);
Route::get('/get-price-by-customer', 'InvoiceController@getPriceByCustomer')->name('getPriceByCustomer');
Route::get('/getLastPriceByCustomer', [InvoiceController::class, 'getLastPriceByCustomer'])->name('getLastPriceByCustomer');
Route::get('/getLastPriceByCustomer', 'InvoiceController@getLastPriceByCustomer'); 
Route::get('/getLastPriceByCustomer', [InvoiceController::class, 'getLastPriceByCustomer'])->name('getLastPriceByCustomer');
Route::get('/get-last-price/{customerId}', [InvoiceController::class, 'getLastPrice'])->name('getLastPrice');
Route::post('/invoices/{invoice}/update-status', 'InvoiceController@updateStatus')->name('invoice.update.status');
// ==============================================================================================================================================================================================
Route::get('invoice/{id}/pdf', [InvoiceController::class, 'showPdf'])->name('invoice.showPdf');
Route::post('/invoice/{id}/sendPdf', [InvoiceController::class, 'sendPdf'])->name('invoice.sendPdf');
// ==============================================================================================================================================================================================
Route::get('/invoices/filter', 'InvoiceController@filter')->name('invoice.filter');
Route::post('/invoice/link/{id}', 'InvoiceController@linkInvoice')->name('invoice.link');
Route::delete('/invoice/delete/{id}', 'InvoiceController@deleteInvoice')->name('invoice.delete');
// ==============================================================================================================================================================================================
Route::get('/dashboard', function () {
  return view('dashboard');
});
// ==============================================================================================================================================================================================
Route::post('/invoicesesp', [InvoiceController::class, 'store2'])->name('invoice.store2');
Route::get('/invoices/{fecha}', 'App\Http\Controllers\InvoiceController@karen');
Route::post('/invoice/karen', [InvoiceController::class, 'karen'])->name('invoice.karen');
// ==============================================================================================================================================================================================
Route::get('/transporte', [TransporteController::class, 'index'])->name('transporte.index');;
Route::get('/transporte/create', [TransporteController::class, 'create'])->name('transporte.create');
Route::post('/transporte', [TransporteController::class, 'store'])->name('transporte.store');
Route::get('transportes/{id}/edit', [TransporteController::class, 'edit'])->name('transporte.edit');
Route::delete('transportes/{transporte}/destroy', [TransporteController::class, 'destroy'])->name('transporte.destroy');
Route::put('/transporte/{id}/update', [TransporteController::class, 'update'])->name('transporte.update');
// ==============================================================================================================================================================================================
Route::get('/Cuentas', [BolController::class, 'index'])->name('bol.index');
Route::get('/bols/pair', [BolController::class, 'showPairForm'])->name('bol.pair.form');
Route::post('/bols/pair', [BolController::class, 'pair'])->name('bol.pair');
Route::put('/invoices/{bol}/cliente', [BolController::class, 'updateCliente'])->name('bol.updateCliente');
Route::put('/invoices/{bol}/transporte', [BolController::class, 'updateTransporte'])->name('bol.updateTransporte');
// ==============================================================================================================================================================================================R¿
Route::get('/logistica',[LogisticaController::class, 'index'])->name('logistica.index');
// ==============================================================================================================================================================================================R¿
Route::get('/bluewi', [BluewiController::class, 'index'])->name('bluewi.index');
Route::get('/bluewi/upload', [BluewiController::class, 'showUploadForm'])->name('bluewi.upload.form');
Route::post('/bluewi/upload', [BluewiController::class, 'upload'])->name('bluewi.upload.xlsx');
Route::get('/bluewi/compare-bol', 'BluewiController@compareBol')->name('bluewi.compare.bol');
// ==============================================================================================================================================================================================R¿
Route::get('/Logistica', [LogisticaController::class, 'index'])->name('logistica.index');
Route::get('/transfer-data', [LogisticaController::class, 'transferData'])->name('logistica.transferData');
Route::get('/logistica/formulario-asignar-cliente', [LogisticaController::class, 'showForm'])->name('logistica.mostrar_formulario');
Route::post('/logistica/asignar-cliente', [LogisticaController::class, 'asignarCliente'])->name('logistica.asignar_cliente');
Route::get('logistica/filterByWeek', 'LogisticaController@filterByWeek')->name('logistica.filterByWeek');
Route::post('/logistica/guardar-todos', [LogisticaController::class, 'guardarTodos'])->name('logistica.guardar_todos');
Route::post('/calcular-precio', [LogisticaController::class, 'calcularPrecio'])->name('logistica.calcularPrecio');
// ================================================================================================================================================================================================
Route::get('/factyremi', [FacturaController::class, 'index'])->name('factura.index');
Route::post('factyremi/transfer', [FacturaController::class, 'transferLogisticaToFactura'])->name('facturas.transferLogisticaToFactura');
Route::get('/factyremi', [FacturaController::class, 'index'])->name('facturas.index');
Route::get('/facturas/{id}/pdf', [FacturaController::class, 'showPdf'])->name('facturas.showPdf');
Route::delete('/facturas/{id}', [FacturaController::class, 'destroy'])->name('facturas.delete');
Route::post('/facturas/{id}/link', [FacturaController::class, 'link'])->name('facturas.link');
//=================================================================================================================================================
Route::post('/facturas/sync-bluewi', [FacturaController::class, 'syncBluewi'])->name('facturas.syncBluewi');
Route::get('/moleculas', [MoleculaController::class, 'index'])->name('moleculas.index');
Route::get('/moleculas/molecula1', [MoleculaController::class, 'molecula1'])->name('moleculas.molecula1');
Route::post('/moleculas/migrate', [MoleculaController::class, 'migrateLogisticaToMolecula1'])->name('moleculas.migrateLogisticaToMolecula1');
Route::get('/moleculas/create', [MoleculaController::class, 'create'])->name('moleculas.create');
Route::post('/moleculas/store', [MoleculaController::class, 'store'])->name('moleculas.store');
Route::get('/molecula1', [MoleculaController::class, 'showMolecula1'])->name('molecula1.show');
Route::post('/moleculas/calculate-best-options', [MoleculaController::class, 'calculateBestOptions'])->name('moleculas.calculateBestOptions');
Route::post('/moleculas/process-payment-batch', [MoleculaController::class, 'processPaymentBatch'])->name('moleculas.processPaymentBatch');
Route::post('/moleculas/pay-best-options', [MoleculaController::class, 'payBestOptions'])->name('moleculas.payBestOptions');
Route::get('/moleculas/download-pdf', [MoleculaController::class, 'downloadPdf'])->name('moleculas.downloadPdf');
Route::get('/generate-pdf', [MoleculaController::class, 'generatePdf'])->name('generatePdf');
Route::post('/process-payment-batch/{batchId}', [MoleculaController::class, 'processPaymentBatch'])
    ->name('processPaymentBatch');
Route::get('/payment/download-and-refresh', [MoleculaController::class, 'downloadAndRefresh'])->name('payment.downloadAndRefresh');
// =============================================================================================================================================
Route::get('/Administracion', [AdministracionController::class, 'index'])->name('Admin.index');
Route::get('/administracion/depositar', [AdministracionController::class, 'showDepositForm'])->name('Admin.showDepositForm');
Route::post('/administracion/depositar', [AdministracionController::class, 'processDeposit'])->name('Admin.processDeposit');
Route::get('/admin/cliente/{id}/bancos', [AdministracionController::class, 'showClientBanks'])->name('Admin.showClientBanks');
Route::get('/admin/depositos_historial/{id}', [AdministracionController::class, 'showDepositHistory'])->name('Admin.showDepositHistory');
Route::get('/devoluciones/{cliente_id}', [AdministracionController::class, 'showDevolucionesForm'])->name('showDevolucionesForm');
Route::post('/refund-deposit', [AdministracionController::class, 'storeDevolucion'])->name('Admin.refundDeposit');
Route::get('/admin/ingresos-devoluciones', [AdministracionController::class, 'showIncomesAndRefunds'])->name('Admin.incomesAndRefunds');
Route::post('/depositos/{id}/asignarSaldo', [AdministracionController::class, 'asignarSaldo'])->name('depositos.asignarSaldo');
// ===============================================================================================================================================
Route::get('/empresa_cuenta', [EmpresaCuentaController::class, 'index'])->name('empresa_cuenta.index');
Route::post('/empresa_cuenta/agregar', [EmpresaCuentaController::class, 'agregarIngreso'])->name('empresa_cuenta.agregarIngreso');
// ==============================================================================================================================================
Route::get('/gastos/formulario', [EmpresaCuentaController::class, 'showGastosForm'])->name('empresa_cuenta.showGastosForm');
Route::get('/gastos', [EmpresaCuentaController::class, 'showGastosForm'])->name('empresa_cuenta.gastos');
Route::post('/gastos', [EmpresaCuentaController::class, 'storeGasto'])->name('empresa_cuenta.storeGasto');
Route::get('/gastos/listar', [EmpresaCuentaController::class, 'listaGastos'])->name('empresa_cuenta.listarGastos');
Route::get('/empresa_cuenta/gastos', [EmpresaCuentaController::class, 'showGastosForm'])->name('empresa_cuenta.showGastos');
Route::post('/empresa_cuenta/gastos', [EmpresaCuentaController::class, 'storeGasto'])->name('empresa_cuenta.storeGasto');
Route::get('/empresa_cuenta/gastos/lista', [EmpresaCuentaController::class, 'listaGastos'])->name('empresa_cuenta.listaGastos');
// ===============================================================================================================================================
Route::post('/moleculas/assign-tariff', [MoleculaController::class, 'assignTariffToBOL'])->name('moleculas.assignTariffToBOL');
Route::post('/moleculas/storeMolecula2', [MoleculaController::class, 'storeMolecula2'])->name('moleculas.storeMolecula2');
Route::post('/molecula2/migrate', [MoleculaController::class, 'migrateDataForMolecula2'])->name('moleculas.migrateDataForMolecula2');
Route::get('/moleculas/molecula2', [MoleculaController::class, 'molecula2'])->name('moleculas.molecula2');
Route::post('/moleculas/storeMolecula2', [MoleculaController::class, 'storeMolecula2'])->name('moleculas.storeMolecula2');
Route::post('/moleculas/processMolecula2', [MoleculaController::class, 'processMolecula2'])->name('moleculas.processMolecula2');
Route::post('/moleculas/migrateToMolecula2', [MoleculaController::class, 'migrateToMolecula2'])->name('moleculas.migrateToMolecula2');
Route::post('/moleculas/molecula2/process', [MoleculaController::class, 'processMolecula2'])->name('moleculas.molecula2.process');
Route::get('/moleculas/downloadPdf/{pdfPath}', [MoleculaController::class, 'downloadPdf'])->name('moleculas.downloadPdf');
Route::get('/moleculas/redirectAfterDownload/{pdfPath}', [MoleculaController::class, 'redirectAfterDownload'])->name('moleculas.redirectAfterDownload');
Route::post('/moleculas/processPaymentBatch', [MoleculaController::class, 'processPaymentBatch'])->name('moleculas.processPaymentBatch');
Route::get('/molecula1', [MoleculaController::class, 'molecula1'])->name('molecula1');
Route::post('/procesar-pagos', [MoleculaController::class, 'procesarPagos'])->name('pagos.procesar');
Route::post('/pagos/procesar', [MoleculaController::class, 'procesar'])->name('moleculas.procesar');
// ===============================================================================================================================================
Route::get('/tipo-cambio', [TipoCambioController::class, 'index'])->name('tipocambio.index');
Route::get('/tipo-cambio/create', [TipoCambioController::class, 'create'])->name('tipocambio.create');
Route::post('/tipo-cambio', [TipoCambioController::class, 'store'])->name('tipocambio.store');
// ================================================================================================================================================
Route::get('/quickbooks/connect', [QuickBooksController::class, 'connect'])->name('quickbooks.connect');
Route::get('/quickbooks/callback', [QuickBooksController::class, 'callback'])->name('quickbooks.callback');
Route::get('/quickbooks/invoices', [QuickBooksController::class, 'fetchInvoices'])->name('quickbooks.fetchInvoices');
