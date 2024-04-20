<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use App\QBDataService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TestingController extends Controller
{
//    public function __construct()
//    {
//        $this->dataService = QBDataService::init();
//        $this->webhook_token = '3ef9b4a3-6f86-444c-90b9-d3d553aefc39';
//    }

    public function index()
    {
        $data = [];

        $dataService = QBDataService::init();

        /* for Customer */
      //  $product = $dataService->FindById('Customer', $id);
         $id = 2;
        //$product = $dataService->FindById('Item', $id);
        //$product = $dataService->Query("SELECT * FROM Customer WHERE id='$id'");
      //  $product = $dataService->Query("SELECT * FROM Customer");


        /* for Item */
        //$product = $dataService->FindById('Item', $id);
        //$product = $dataService->Query("SELECT * FROM Item WHERE id='$id'");
        $product = $dataService->Query("SELECT * FROM item");


        /* for Invoice */
        //$product = $dataService->FindById('Invoice', $id);
        //$product = $dataService->Query("SELECT * FROM Invoice WHERE id='$id'");
        //$product = $dataService->Query("SELECT * FROM Invoice");

        //$product = $dataService->getCompanyInfo();
//        $data=[
//            'Name' => $product->getName(),
//            'Description' => $product->getDescription() ?? null,
//        ];
        echo "<pre>";
        print_r($product);
        echo "</pre>";
        die();

    }

    public function testGetCustomer()
    {
        $dataService = QBDataService::init();

        $id = 2;
        $response = $dataService->FindById('Item', $id);
                $data=[
                    'Name' => $response->Name,
                    'Description' => $response->Description ?? null,
                ];
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();

    }



}
