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

        return redirect($authUrl);
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
            session([
                'quickbooks_access_token' => $accessToken->getAccessToken(),
                'quickbooks_refresh_token' => $accessToken->getRefreshToken(),
                'quickbooks_realm_id' => $request->realmId
            ]);

            // Redirect to fetch route
            return redirect()->route('quickbooks.index');
        } catch (\Exception $e) {
            return redirect()->route('quickbooks.error')->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function fetchInvoices()
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

        if (!$accessToken || !$realmId) {
            return redirect()->route('quickbooks.index')->with('error', 'Access token or Realm ID missing.');
        }
    
        // Actualiza el token si es necesario
        $this->updateTokens();
    
        // Reconfigura el servicio con el nuevo token
        $dataService->updateOAuth2Token(session('quickbooks_access_token'));
    
        try {
            $invoices = $dataService->Query("SELECT * FROM Invoice");
            $this->storeInvoicesInDatabase($invoices);
    
            return view('quickbooks.index', ['invoices' => $invoices]);
        } catch (\Exception $e) {
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

    public function updateTokens()
    {
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOKS_CLIENT_ID'),
            'ClientSecret' => env('QUICKBOOKS_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOKS_REDIRECT_URI'),
            'scope' => 'com.intuit.quickbooks.accounting',
            'baseUrl' => env('QUICKBOOKS_ENVIRONMENT') === 'sandbox' ? "Development" : "Production",
        ]);

        $refreshToken = session('quickbooks_refresh_token');

        if (!$refreshToken) {
            return redirect()->route('quickbooks.index')->with('error', 'Refresh token missing.');
        }

        try {
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessToken = $OAuth2LoginHelper->refreshToken($refreshToken);

            session([
                'quickbooks_access_token' => $accessToken->getAccessToken(),
                'quickbooks_refresh_token' => $accessToken->getRefreshToken(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('quickbooks.index')->with('error', $e->getMessage());
        }
    }
    
}
