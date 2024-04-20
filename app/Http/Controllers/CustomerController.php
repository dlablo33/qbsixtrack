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

class CustomerController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $setting = $user->setting;
        $realmId=$setting['QBORealmID'];
        $data = [];
        $customers = Customer::where('realm_id',$realmId)->orderBy('id', 'DESC')->get();
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['customers'] = $customers;

        return view('customers.index', $data);
    }

    public function create($id = null)
    {

        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";

        return view('customers.create', $data);
    }



    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
         $setting = $user->setting;
         $realmId=$setting['QBORealmID'];
       // dd($realmId);

        try {
            DB::beginTransaction();
            $dataService = QBDataService::init();

            $customerData = [
                'PrimaryEmailAddr' => [
                    'Address' => $request->input('email'),
                ],
                'DisplayName' => $request->input('name'),
                'PrimaryPhone' => [
                    'FreeFormNumber' => $request->input('phone'),
                ],
                'BillAddr' => [
                    'CountrySubDivisionCode' => $request->input('state'),
                    'City' => $request->input('city'),
                    'PostalCode' => $request->input('zip'),
                    'Line1' => $request->input('address'),
                    'Country' => $request->input('country')
                ],
               // 'Id'=>date('Ymdhs'),
                'SyncToken'=>1

            ];


          // $quickbooksResponse = $this->createOrUpdateQBCustomers($customerData);
            $quickbooksResponse = \QuickBooksOnline\API\Facades\Customer::create($customerData);

            $resultObj = $dataService->Add($quickbooksResponse);
            //echo '<pre>';print_r($resultObj);exit();
            if ($resultObj) {

                $customerArray = [
                    'quickbooks_id' => $resultObj->Id,
                    'SyncToken' => $resultObj->SyncToken,
                    'name' => $resultObj->DisplayName,
                    'email' => $resultObj->PrimaryEmailAddr->Address ?? null,
                    'phone' => $resultObj->PrimaryPhone->FreeFormNumber?? null,
                    'address' => $resultObj->BillAddr->Line1 ?? null,
                    'city' => $resultObj->BillAddr->City?? null,
                    'country' => $resultObj->BillAddr->Country ?? null,
                    'state' => $resultObj->BillAddr->CountrySubDivisionCode ?? null,
                    'zip' => $resultObj->BillAddr->PostalCode ?? null,
                    'realm_id' => $realmId??null,
                    'createdby' => $user_id,
                    'updatedby' => $user_id,
                ];
               // echo '<pre>';print_r($realmId);exit();

              //  dd($customerArray);
             $data=   Customer::create($customerArray);



                DB::commit();

                return redirect()->route('customers.index')->with('success', 'Customer created successfully');
            } else {
                DB::rollBack();
                return redirect()->route('customers.create')->with('error', 'Something went wrong!');
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('customers.create')->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customers.create')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $data['menu'] = "customers";
        $data['menu_sub'] = "";
        $data['customer'] = $customer;

        return view('customers.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $user = Auth::user();
        $user_id = $user->id;
        $setting = $user->setting;
        $realmId=$setting['QBORealmID'];
        // dd($realmId);
        try {
            DB::beginTransaction();
           $dataService = QBDataService::init();

            // Your existing logic to update in the local database
            $customer = Customer::findOrFail($id);

            $syncToken = $customer->SyncToken;

            $data = [
//                'Id' => $customer->quickbooks_id,
                'DisplayName' => $request->input('name'),
                'PrimaryEmailAddr' => [
                    'Address' => $request->input('email'),
                ],
                'PrimaryPhone' => [
                    'FreeFormNumber' => $request->input('phone'),
                ],
                'BillAddr' => [
                    'Line1' => $request->input('address'),
                    'City' => $request->input('city'),
                    'Country' => $request->input('country'),
                    'CountrySubDivisionCode' => $request->input('state'),
                    'PostalCode' => $request->input('zip'),
                ],
                'SyncToken' => $syncToken,
            ];

            $customerId=$customer->quickbooks_id;
           $customerToUpdate = $dataService->Query("SELECT * FROM Customer WHERE id='$customerId'");
            $theCustomer='';
            if(!empty($customerToUpdate) && sizeof($customerToUpdate) == 1){
                $theCustomer = current($customerToUpdate);
            }

            $quickbooksResponse = \QuickBooksOnline\API\Facades\Customer::update($theCustomer,$data);

            if ($quickbooksResponse) {
                $customerArray = [
                    'id'=>$id,
                    'quickbooks_id' => $quickbooksResponse->Id,
                    'SyncToken' => $quickbooksResponse->SyncToken,
                    'name' => $quickbooksResponse->DisplayName,
                    'email' => $quickbooksResponse->PrimaryEmailAddr->Address ?? null,
                    'phone' => $quickbooksResponse->PrimaryPhone->FreeFormNumber?? null,
                    'address' => $quickbooksResponse->BillAddr->Line1 ?? null,
                    'city' => $quickbooksResponse->BillAddr->City?? null,
                    'country' => $quickbooksResponse->BillAddr->Country ?? null,
                    'state' => $quickbooksResponse->BillAddr->CountrySubDivisionCode ?? null,
                    'zip' => $quickbooksResponse->BillAddr->PostalCode ?? null,
                    'realm_id' =>$realmId ?? null,
                    'createdby' => $user_id,
                    'updatedby' => $user_id,
                ];
                $customer->update($customerArray);

                DB::commit();

                return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('customers.edit', ['id' => $id])->with('error', 'Something went wrong!');
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('customers.edit', ['id' => $id])->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customers.edit', ['id' => $id])->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }

}
