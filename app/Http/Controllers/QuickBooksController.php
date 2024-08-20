<?php

namespace App\Http\Controllers;

use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use App\Quickbook;

class QuickBooksController extends Controller
{
    public function connect()
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => env('QUICKBOOKS_ENVIRONMENT') === 'sandbox' ? "Development" : "Production",
        ]);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        // Convertir a cadena de texto
        $authUrlString = (string) $authUrl;

        return redirect($authUrlString);
    }

    public function callback(Request $request)
    {
        // Check for required parameters
        if (!$request->has('code') || !$request->has('realmId')) {
            return redirect()->route('quickbooks.error')->withErrors(['error' => 'Authorization code or realm ID missing.']);
        }
    
        // Configure Data Service
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => env('QUICKBOOKS_ENVIRONMENT') === 'sandbox' ? "Development" : "Production",
        ]);
    
        // Get OAuth2 Login Helper
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    
        try {
            // Exchange code for access token
            $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($request->code, $request->realmId);
    
            if (!$accessToken) {
                return redirect()->route('quickbooks.error')->withErrors(['error' => 'Failed to obtain access token.']);
            }
    
            // Store access token and realm ID in session (consider using encrypted storage)
            session(['quickbooks_access_token' => $accessToken, 'quickbooks_realm_id' => $request->realmId]);

            dd(session()->all());

            // Redirect to fetch route
            return redirect()->route('quickbooks.index');
        } catch (\Exception $e) {
            return redirect()->route('quickbooks.error')->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function fetchInvoices()
    {
        // Configurar el servicio de datos de QuickBooks
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => env('QUICKBOOKS_ENVIRONMENT') === 'sandbox' ? "Development" : "Production",
        ]);
    
        // Recuperar los tokens de la sesión
        $accessToken = session('quickbooks_access_token');
        $realmId = session('quickbooks_realm_id');
    
        if (!$accessToken || !$realmId) {
            return redirect()->route('quickbooks.index')->with('error', 'Access token or Realm ID missing.');
        }
    
        // Actualizar el token en el servicio
        $dataService->updateOAuth2Token($accessToken);
    
        try {
            // Consulta para obtener todas las facturas de QuickBooks
            $invoices = $dataService->Query("SELECT * FROM Invoice");
    
            // Guardar las facturas en la base de datos
            $this->storeInvoicesInDatabase($invoices);
    
            // Retornar los datos de las facturas a la vista
            return view('quickbooks.index', ['invoices' => $invoices]);
        } catch (\Exception $e) {
            // Manejar errores de la API
            return redirect()->route('quickbooks.index')->with('error', $e->getMessage());
        }
    }
    
    public function error()
    {
        return view('quickbooks.error'); // Asegúrate de que esta vista exista
    }

    public function storeInvoicesInDatabase($invoices)
    {
        foreach ($invoices as $invoice) {
            DB::table('quickbook')->updateOrInsert(
                ['id' => $invoice->Id], // Asume que `Id` es la clave primaria en tu tabla
                [
                    'sync_token' => $invoice->SyncToken,
                    'meta_data' => json_encode($invoice->MetaData),
                    'custom_field' => json_encode($invoice->CustomField),
                    'domain' => $invoice->domain,
                    'txn_date' => $invoice->TxnDate,
                    'doc_number' => $invoice->DocNumber,
                    'due_date' => $invoice->DueDate,
                    'total_amt' => $invoice->TotalAmt,
                    'balance' => $invoice->Balance,
                    'allow_online_ach_payment' => $invoice->AllowOnlineACHPayment,
                    'allow_online_credit_card_payment' => $invoice->AllowOnlineCreditCardPayment,
                    'allow_online_payment' => $invoice->AllowOnlinePayment,
                    'allow_ipn_payment' => $invoice->AllowIPNPayment,
                    'print_status' => $invoice->PrintStatus,
                    'email_status' => $invoice->EmailStatus,
                    'bill_email' => $invoice->BillEmail->Address,
                    'ship_addr' => json_encode($invoice->ShipAddr),
                    'bill_addr' => json_encode($invoice->BillAddr),
                    'private_note' => $invoice->PrivateNote,
                    'customer_memo' => $invoice->CustomerMemo->value,
                    'sales_term_ref' => $invoice->SalesTermRef->value,
                    'customer_ref' => $invoice->CustomerRef->value,
                    'apply_tax_after_discount' => $invoice->ApplyTaxAfterDiscount,
                ]
            );
        }
    }

    public function testQuickBooksApi()
    {
    $dataService = DataService::Configure([
        'auth_mode' => 'oauth2',
        'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
        'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
        'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
        'scope' => 'com.intuit.quickbooks.accounting',
        'baseUrl' => env('QUICKBOOKS_ENVIRONMENT') === 'sandbox' ? "Development" : "Production",
    ]);

    $accessToken = session('quickbooks_access_token');
    $realmId = session('quickbooks_realm_id');

    $dataService->updateOAuth2Token($accessToken);

    try {
        $companyInfo = $dataService->query("SELECT * FROM CompanyInfo");
        dd($companyInfo);
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
    }

    public function index()
    {
        // Recuperar las facturas almacenadas en la base de datos
        $invoices = Quickbook::all();

        $data = [];
        $data['menu'] = $invoices;
        $data['submenu'] = '';
        $data['invoices'] = $invoices;
    
        // Pasar las facturas a la vista
        return view('quickbooks.index', $data);
    }

    
}

