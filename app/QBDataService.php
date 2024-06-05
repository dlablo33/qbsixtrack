<?php


namespace App;


use QuickBooksOnline\API\DataService\DataService;

class QBDataService
{
    public static function init()
    {
        $user = auth()->user();
        if ($user) {
            $qbAuth = $user->qbAuth;
            $setting = $user->setting;
        } else {
            $setting = Settings::where('id', 1)->first()->toArray();
            $qbAuth = Auth::where('id', 1)->first();
        }


//        $setting = $user->setting;
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $setting['ClientID'],
            'ClientSecret' => $setting['ClientSecret'],
            'RedirectURI' => $setting['RedirectURI'],
            'scope' => $setting['scope'],
            'baseUrl' => $setting['baseUrl']
        ));

        /*
         * Retrieve the accessToken value from session variable
         */
      //  $qbAuth = $user->qbAuth;
     //   echo '<pre>';print_r($qbAuth);exit();
        $accessToken = $qbAuth->refreshToken(); // we are re generating accrss token.

      //  echo '<pre>';print_r($accessToken);exit();
        $dataService->updateOAuth2Token($accessToken);

        return $dataService;
    }
}
