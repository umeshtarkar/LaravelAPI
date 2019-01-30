<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getSale(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            if($request->has('mostPopular') && !empty($request->mostPopular)){
                $sale = Sale::orderBy('views','DESC')->skip($this->record_offset)->take($this->records_per_page);
            }else{
                $sale = Sale::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            }
            
            
            if($request->has('status') && !empty($request->status)){
                $sale->where('status',1);
            }
            
            

            if($request->has('keyword') && !empty($request->keyword)){
                $sale->orWhere('name','like',''. $request->keyword.'%')
                      ->orWhere('detail', 'like',''. $request->keyword.'%')
                      ->orWhere('city', 'like',''. $request->keyword.'%');
                      
            }

            $sales = $sale->get();

            if(!$sales->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $sales;
            }else{
                $this->apiResponse['statusCode'] = 204;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = array();
	}
		
        }catch(Exception $e){
            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }


    public function getSaleCount(Request $request){
        
        try{
            if($request->has('status') && !empty($request->status)){
                $saleCount = Sale::where('status',1)->count();
            }else{
                $saleCount = Sale::count();
            }
            
            if($saleCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = "Found $saleCount Results";
                $this->apiResponse['data']       = $saleCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }
    
    public function getSaleDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $sale = Sale::find($id);
            $sale->views = $sale->views+1;
            $sale->save();

            if($sale){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $sale;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createSale(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'name'         => 'required|regex:/^([^0-9]*)$/|max:255',
                'detail'       => 'required',
                'website'      => 'unique:vacancies,website',
                'user_id'      => 'required',
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $sale = new Sale;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'Sale created sucessfully!';
                $this->apiResponse['data']       = $sale->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateSale(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $sale = Sale::find($id);
                
                if($sale){
                    $validator = Validator::make($request, [
                        'name'         => 'required|regex:/^([^0-9]*)$/|max:255',
                        'detail'       => 'required',
                        'website'      => 'unique:vacancies,website,'.$id,
                        'user_id'      => 'required',
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $sale->fill($request);
                    
                    if($sale->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'Sale updated successfully';
                    }
                } else {
                    throw new Exception("Invalid Request");
                }
            }else{
                throw new Exception('Invalid Request');
            }

        }catch(Exception $e){
            return $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function toggleSaleStatus(Request $request){
        try
        {
            $id       = $request->get('id');
            $status   = $request->get('status');
            $sale  = Sale::find($id);

            if($sale){
                $sale->status = $status;
                $sale->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'Sale is enabled successfully' : 'Sale is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }
}
