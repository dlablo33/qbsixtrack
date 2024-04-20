<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

class QuickbookController extends Controller
{
    public function callback(User $user, Request $request)
    {
        $setting = $user->setting;
        $qbAuth = $user->qbAuth;
        // Create SDK instance
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $setting['ClientID'],
            'ClientSecret' =>  $setting['ClientSecret'],
            'RedirectURI' => $setting['RedirectURI'],
            'scope' => $setting['scope'],
            'baseUrl' => $setting['baseUrl']
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $code = $request->query('code');
        $realmId = $request->query('realmId');
        $setting->QBORealmID = $realmId;
        $setting->save();
        /*
         * Update the OAuth2Token
         */
        try {
            $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
        } catch (SdkException | ServiceException $e) {
            dd($e);
        }
        $dataService->updateOAuth2Token($accessToken);

        /*
         * Setting the accessToken for session variable
         */
        $qbAuth->setToken($accessToken);

        return redirect()->route('settings.edit', ['id' => $setting->id]);

    }

    public function syncItems()
    {

        // Replace these values with your actual QuickBooks credentials
        $realmId = '9130357849536636';
        $clientId = 'AB5kFbletRbjWcZWUqor6CHxtY730MlAZ9nEcuFNtmjfNwOdtU';
        $clientSecret = 'oW2mxLmn6WgFxQOzKDn9xrSGV4j8i0RkKo6gaAYW';


        $accessToken = $this->getAccessToken($clientId, $clientSecret, $realmId);


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ])->get("https://quickbooks.api.url/v3/company/{$realmId}/item");

        $items = $response->json()['QueryResponse']['Item'] ?? [];



        foreach ($items as $item) {
            Product::updateOrCreate(
                ['id' => $item['Id']], // Assuming QuickBooks provides an ID for items
                [
                    'name' => $item['Name'],
                    'description' => $item['Description'],
                    // Add other fields based on QuickBooks item attributes you want to store
                ]
            );
        }

        return response()->json(['message' => 'Items synchronized successfully']);
    }
}
