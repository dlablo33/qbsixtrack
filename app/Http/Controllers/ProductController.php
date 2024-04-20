<?php

namespace App\Http\Controllers;

use App\Product;
use App\QBDataService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;


class ProductController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $user_id = $user->id;
        $setting = $user->setting;
        $realmId=$setting['QBORealmID'];
        $data = [];
        $products = Product::where('realm_id',$realmId)->orderBy('id','DESC')->get();
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
        $data['qb_products'] = $products;



        return view('products.index', $data);
    }


    public function create($id= null)
    {
        // form
        $data = [];
        $data['menu'] = "settings";
        $data['menu_sub'] = "";
//        $dataService = QBDataService::init();
//        $data['product'] = $dataService->FindById('item', $id);
//        $data['coas'] = $dataService->Query("SELECT * FROM Account");
        return view('products.create', $data);
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
            $data = [
                'Name' => $request->input('name'),
                'Description' => $request->input('description'),
                'UnitPrice' => $request->input('unit_price'),
                'IncomeAccountRef' => [
                    'name' => 'Sales of Product Income',
                    'value' => '79',
                ],
                'AssetAccountRef' => [
                    'name' => 'Inventory Asset',
                    'value' => '81',
                ],
                'Type' => $request->input('type'),
                'ExpenseAccountRef' => [
                    'name' => 'Cost of Goods Sold',
                    'value' => '80',
                ],
            ];



            $quickbooksResponse = \QuickBooksOnline\API\Facades\Item::create($data);
           // echo '<pre>';print_r($quickbooksResponse);exit();

            $resultObj = $dataService->Add($quickbooksResponse);

           // echo '<pre>';print_r($resultObj);exit();

            if ($resultObj) {
                $productArray = [
                    'ItemId' => $resultObj->Id,
                    'Name' => $resultObj->Name,
                    'Description' => $resultObj->Description ?? null,
                    'Active' => $resultObj->Active ? 1 : 0,
                    'FullyQualifiedName' => $resultObj->FullyQualifiedName,
                    'Taxable' => $resultObj->Taxable ? 1 : 0,
                    'UnitPrice' => $resultObj->UnitPrice ?? null,
                    'Type' => $resultObj->Type ?? null,
                    'IncomeAccountRef' => isset($resultObj->IncomeAccountRef) ? json_encode($resultObj->IncomeAccountRef) : null,
                    'PurchaseCost' => $resultObj->PurchaseCost ?? null,
                    'TrackQtyOnHand' => $resultObj->TrackQtyOnHand ? 1 : 0,
                    'SyncToken' => $resultObj->SyncToken,
                    'realm_id' =>$realmId ?? null,
                    'createdby' => $user_id,
                    'updatedby' => $user_id,
                ];



                Product::create($productArray);

                DB::commit();

                return redirect()->route('products.index')->with('success', 'Product created successfully');
            } else {

                DB::rollBack();

                return redirect()->route('products.create')->with('error', 'Something went wrong!');
            }
        } catch (QueryException $e) {

            DB::rollBack();
            return redirect()->route('products.create')->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->route('products.create')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $data['menu'] = "products";
        $data['menu_sub'] = "";
        $data['product'] = $product;

        return view('products.edit', $data);

    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $setting = $user->setting;
        $realmId=$setting['QBORealmID'];

        try {
            DB::beginTransaction();
            $dataService = QBDataService::init();
            // Your existing logic to update in the local database
            $product = Product::findOrFail($id);

            $syncToken = $product->SyncToken;

            $data = [
                'Name' => $request->input('name'),
                'Description' => $request->input('description'),
                'UnitPrice' => $request->input('unit_price'),
                'IncomeAccountRef' => [
                    'name' => 'Sales of Product Income',
                    'value' => '79',
                ],
                'ItemCategoryType'=>'Service',
                'AssetAccountRef' => [
                    'name' => 'Inventory Asset',
                    'value' => '81',
                ],
                'Type' => $request->input('type'),
                'SyncToken'=>$syncToken,
                'ExpenseAccountRef' => [
                    'name' => 'Cost of Goods Sold',
                    'value' => '80',
                ],
            ];
            $ItemId=$product->ItemId;
            $productToUpdate = $dataService->Query("SELECT * FROM Item WHERE id='$ItemId'");

            $theProduct='';
            if(!empty($productToUpdate) && sizeof($productToUpdate) == 1){
                $theProduct = current($productToUpdate);
            }

            $quickbooksResponse = \QuickBooksOnline\API\Facades\Item::update($theProduct, $data);
            if ($quickbooksResponse) {

                $product->update([
                    "id" => $id,
                    'ItemId' => $quickbooksResponse->Id,
                    'Name' => $quickbooksResponse->Name,
                    'Description' => $quickbooksResponse->Description ?? null,
                    'Active' => $quickbooksResponse->Active ? 1 : 0,
                    'FullyQualifiedName' => $quickbooksResponse->FullyQualifiedName,
                    'Taxable' => $quickbooksResponse->Taxable ? 1 : 0,
                    'UnitPrice' => $quickbooksResponse->UnitPrice ?? null,
                    'Type' => $quickbooksResponse->Type ?? null,
                    'IncomeAccountRef' =>  null,
                    'PurchaseCost' => $quickbooksResponse->PurchaseCost ?? null,
                    'TrackQtyOnHand' => $quickbooksResponse->TrackQtyOnHand ? 1 : 0,
                    'realm_id' =>$realmId ?? null,
                    'SyncToken' => $quickbooksResponse->SyncToken,
                    'updatedby' => $user_id,
                ]);
                DB::commit();

                return redirect()->route('products.index')->with('success', 'Product updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('products.edit', ['id' => $id])->with('error', 'Something went wrong!');
            }

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('products.edit', ['id' => $id])->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.edit', ['id' => $id])->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function destroy(Product $product,$id)
    {
        $product = Product::find($id);
        $product->delete();
        toast('Product deleted successful!', 'success');
        return redirect()->route('products.index');
    }


    public function syncItems()
    {
        try {
            $user_id = Auth::user()->id;

            $realmId = '9130357849536636';

            $accessToken = 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..gWtNOQ_npS-RSgrXYiPwYg.67ywnTiXUUt2NgXdT9GinH-9DUFHKgwYRlnlap1uVs-8Ihmm0Q5hhzRRLys9QWI6_y-KkZquQe1OChlUX5grnvEONy9efsRPAAnL4zl4m9j_5ARt79s1warmVuMOUego9XWrvRpVWZWWiqhelhw-lvT2--cgkOvoIIIytd1GaWGQzTh9j0ulVhczUDx_7esjHuKjbugwT_DrmK3sxbDjT1yes7411PILCdhgrNAKkZmNuR_AMAAGdIV0kEFBb-JS1pCALqtdtHWimKzVXppMtEYdGAFFaW5qjas4Vg5ABWLXBFqs-iyLbeMqtqh3VIlb3OdV4bQm07RGhp6-LUViTJXaLZeHNmPTrjZKz_GT690JEvFichHUkFpSpTMOkQViaaXJO4O5vc8KP6sdq9H_1pL4OywXFXcnWYiYUgPUazORkoyxhXPqwl0lSbh9ETsATflp7W2DZ3gYMbswJ7tEjGxvuNr_Y3ztY2VlSILSlTYzC1F41euiofNHJOHHjfqJF7CmK2L88Yg1SRsmPdxUnEJG-UzhlYkZeN1TleCTBj0Y_7BXeNVE1UgyLfyuutPvNR3lafgL1rx3m6RaMArsuHewx_Z_gS5ZhSgMw1jkMXsIbMLzHyf2NoBT-y8_mvnBe_6-hlgPlYt1DKIL9F7Ay8TW6klvUbI3sJmOmxBf1ktBmn6lbRyXcUjUM8yKzuNuvteB-mtUpbdCZlsBLPpGoqCdLCuvaTqABF1udA6RgXPCxScP52ma8pvfpddwPDJb.VjcBz6R9BcLDK1y9uPrmpA';

            $sandboxApiUrl = "https://sandbox-quickbooks.api.intuit.com/v3/company/{$realmId}/query?query=select * from Item&minorversion=40";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->get($sandboxApiUrl);

            $items = $response->json()['QueryResponse']['Item'] ?? [];



            foreach ($items as $item) {

                Product::updateOrCreate(
                    ['ItemId' => $item['Id']],
                    [
                        'Name' => $item['Name'],
                        'Description' => $item['Description'] ?? null,
                        'Active' => $item['Active'],
                        'FullyQualifiedName' => $item['FullyQualifiedName'],
                        'Taxable' => $item['Taxable'] ?? null,
                        'UnitPrice' => $item['UnitPrice'] ?? null,
                        'Type' => $item['Type'] ?? null,
                        'IncomeAccountRef' => isset($item['IncomeAccountRef']) ? json_encode($item['IncomeAccountRef']) : null,                        'PurchaseCost' => $item['PurchaseCost'] ?? null,
                        'TrackQtyOnHand' => $item['TrackQtyOnHand'] ?? null,
                        'domain' => $item['domain'] ?? null,
                        'sparse' => $item['sparse'] ?? null,
                        'SyncToken' => $item['SyncToken'],
                        'realm_id' =>$realmId ?? null,
                        'createdby' => $user_id,
                        'updatedby' => $user_id,
                    ]
                );
            }

            return redirect()->route('products.index')->with('success', 'Items synchronized successfully');
        } catch (QueryException $e) {
            return redirect()->route('products.index')->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    // Your actual implementation of getAccessToken

    public function autocomplete(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $setting = $user->setting;
        $realmId=$setting['QBORealmID'];
        $term = $request->input('product_name');

        // Use the "limit" method to restrict the number of results
        $products = Product::where('Name', 'LIKE', '%' . $term . '%')->where('realm_id',$realmId)->limit(10)->get(['id', 'Name','UnitPrice']);

        return response()->json($products);
    }

}
