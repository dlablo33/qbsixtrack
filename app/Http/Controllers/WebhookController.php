<?php

namespace App\Http\Controllers;


use App\Customer;
use App\InvoiceDetail;
use App\Product;
use App\QBDataService;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;

use Illuminate\Support\Facades\Mail;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\WebhooksService\WebhooksService;


class WebhookController extends Controller
{
    public function __construct()
    {
//        $this->dataService = QBDataService::init();
       $this->webhook_token = '3ef9b4a3-6f86-444c-90b9-d3d553aefc39';
    }

    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
            $dataService = QBDataService::init();
            $payLoad = file_get_contents("php://input");
            $verified = WebhooksService::verifyPayload($this->webhook_token, $payLoad, $_SERVER['HTTP_INTUIT_SIGNATURE']);

        if ($verified == true) {
            try {


                $payLoad_data = json_decode($payLoad, true);

                foreach ($payLoad_data['eventNotifications'] as $event_noti) {
                    $realmId = $event_noti['realmId'];
                    foreach ($event_noti['dataChangeEvent']['entities'] as $entity) {

                        $name = $entity['name'];
                        $id = $entity['id'];
                        if ($name === 'Customer') {
                            $response = $dataService->FindById('Customer', $id);
                            $insertedData= self::updateOrCreateCustomer($response,$realmId,$user_id);
                            mail("mdarmancse@gmail.com", "QB Customer data", json_encode($insertedData));
                        }
                        if ($name === 'Item') {
                            $response = $dataService->FindById('Item', $id);
                            $insertedData=self::updateOrCreateItem($response,$realmId,$user_id);
                            mail("amd55077@gmail.com", "QB Item data", json_encode($insertedData));
                        }
                        if ($name === 'Invoice') {
                            $response = $dataService->FindById('Invoice', $id);
                            $insertedData = self::updateOrCreateInvoice($response, $realmId, $user_id);
                            mail("amd55077@gmail.com", "QB Invoice data", json_encode($insertedData));
                        }
                    }
                }
            }catch (\Exception $e){
                mail("amd55077@gmail.com", "QB Error ", json_encode($e->getMessage()));

            }
        }



    }

    public function updateOrCreateItem($response,$realmId,$user_id)
    {
        return Product::updateOrCreate(
            ['ItemId' => $response->Id],
            [
                'Name' => $response->Name,
                'Description' => $response->Description ?? null,
                'Active' => $response->Active ? 1 : 0,
                'FullyQualifiedName' => $response->FullyQualifiedName,
                'Taxable' => $response->Taxable ? 1 : 0,
                'UnitPrice' => $response->UnitPrice ?? null,
                'Type' => $response->Type ?? null,
                'IncomeAccountRef' => isset($response->IncomeAccountRef) ? json_encode($response->IncomeAccountRef) : null,
                'PurchaseCost' => $response->PurchaseCost ?? null,
                'TrackQtyOnHand' => $response->TrackQtyOnHand ? 1 : 0,
                'SyncToken' => $response->SyncToken,
                'realm_id' =>$realmId ?? null,
                'createdby' => $user_id,
                'updatedby' => $user_id,
            ]
        );
    }

    public function updateOrCreateCustomer($response,$realmId,$user_id)
    {
        $data = Customer::updateOrCreate(
            ['quickbooks_id' => $response->Id],
            [
                'name' => $response->DisplayName,
                'email' => $response->PrimaryEmailAddr->Address ?? null,
                'phone' => $response->PrimaryPhone->FreeFormNumber ?? null,
                'address' => $response->BillAddr->Line1 ?? null,
                'city' => $response->BillAddr->City ?? null,
                'country' => $response->BillAddr->Country ?? null,
                'state' => $response->BillAddr->CountrySubDivisionCode ?? null,
                'zip' => $response->BillAddr->PostalCode ?? null,
                'SyncToken' => $response->SyncToken,
                'realm_id' =>$realmId ?? null,
                'createdby' => $user_id,
                'updatedby' => $user_id,
            ]
        );

        return $data ;
    }


    public function updateOrCreateInvoice($response, $realmId, $user_id)
    {

        $invoice = Invoice::updateOrCreate(
            ['invoice_no' => $response->Id],
            [
                'customer_id' => $response->CustomerRef->value,
                'terms' => $response->TermsRef->value ?? null,
                'invoice_date' => $response->TxnDate ?? date('Y-m-d'),
                'due_date' => $response->DueDate ?? null,
                'billing_address' => isset($response->BillAddr) ? json_encode($response->BillAddr) : null,
                'total_before_discount' => $response->TotalAmt ?? null,
                'total_after_discount' => $response->TotalAmt ?? null,
                'discount_percentage' => 0,
                'realm_id' => $realmId,
                'SyncToken' => $response->SyncToken,
                'createdby' => $user_id,
                'updatedby' => $user_id,
            ]
        );

        // Update or create invoice details
        if (isset($response->Line) && is_array($response->Line)) {
            foreach ($response->Line as $line) {
                $product = Product::where('ItemId', $line->SalesItemLineDetail->ItemRef->value)->first();
                if ($product) {
                    InvoiceDetail::updateOrCreate(
                        ['invoice_id' => $invoice->id, 'product_id' => $product->id],
                        [
                            'quantity' => $line->SalesItemLineDetail->Qty ?? null,
                            'unit_price' => $line->SalesItemLineDetail->UnitPrice ?? null,
                            'total' => $line->Amount ?? null,
                        ]
                    );
                }
            }
        }

        return $invoice;
    }

    public function testGetCustomerApi()
    {
        $dataService = QBDataService::init();

        $id = 2;
        $response = $dataService->FindById('Item', $id);

         $data=self::updateOrCreateItem($response);
        //mail("mdarmancse@gmail.com", "Tst Reposne", json_encode($data));

        return $data;
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();

    }


}
