<?php

namespace App\Http\Controllers;

use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Http\Request;
use QuickBooksOnline\API\Core\OAuth\OAuth2\String as QBString;
use SebastianBergmann\Type\MixedType;



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
        $authUrlString = (string)$authUrl;

        return redirect($authUrlString);
    }

    public function callback(Request $request)
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
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($request->code, $request->realmId);

        // Almacena el accessToken en la sesión o base de datos
        session(['quickbooks_access_token' => $accessToken]);
        session(['quickbooks_realm_id' => $request->realmId]);

        return redirect()->route('quickbooks.fetchInvoices');
    }

    public function fetchInvoices()
    {
    $dataService = \QuickBooksOnline\API\DataService\DataService::Configure([
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

    // Asegúrate de usar la clase QuickBooksOnline\API\Core\OAuth\OAuth2\String aquí
    $dataService->setLogLocation(new \QuickBooksOnline\API\Core\OAuth\OAuth2\String(storage_path('logs/quickbooks.log')));
    $dataService->setMinorVersion(new \QuickBooksOnline\API\Core\OAuth\OAuth2\String('65')); // Asegúrate de usar un string
    $dataService->throwExceptionOnError(true);

    $invoices = $dataService->Query("SELECT * FROM Invoice");

    return view('invoices.index', ['invoices' => $invoices]);
    }
  
}
