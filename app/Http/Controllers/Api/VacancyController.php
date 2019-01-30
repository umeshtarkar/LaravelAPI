<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getVacancy(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }
            
            if($request->has('mostPopular') && !empty($request->mostPopular)){
                $vacancy = Vacancy::orderBy('views','DESC')->skip($this->record_offset)->take($this->records_per_page);
            }else{
                $vacancy = Vacancy::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            }
            
            if($request->has('status') && !empty($request->status)){
                $vacancy->where('status',1);
            }

            if($request->has('keyword') && !empty($request->keyword)){
                $vacancy->orWhere('name','like',''. $request->keyword.'%')
                      ->orWhere('detail', 'like',''. $request->keyword.'%')
                      ->orWhere('city', 'like',''. $request->keyword.'%');
                      
            }

            if($request->has('type') && !empty($request->type)){
                $vacancy->orWhere('type', $request->type);
            }

            $vacancies = $vacancy->get();
            
            if(!$vacancies->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $vacancies;
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


    public function getVacancyCount(Request $request){
        
        try{
            if($request->has('status') && !empty($request->status)){
                $vacancyCount = Vacancy::where('status',1)->count();
            }else{
                $vacancyCount = Vacancy::count();
            }
            
            
            if($vacancyCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = "Found $vacancyCount Results";
                $this->apiResponse['data']       = $vacancyCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }
    
    public function getVacancyDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }

            $vacancy = Vacancy::find($id);
            $vacancy->views = $vacancy->views+1;
            $vacancy->save();

            if($vacancy){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $vacancy;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createVacancy(Request $request){
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

                $vacancy = new Vacancy;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'Vacancy created sucessfully!';
                $this->apiResponse['data']       = $vacancy->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateVacancy(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $vacancy = Vacancy::find($id);
                
                if($vacancy){
                    $validator = Validator::make($request, [
                        'name'         => 'required|regex:/^([^0-9]*)$/|max:255',
                        'detail'       => 'required',
                        'website'      => 'unique:vacancies,website,'.$id,
                        'user_id'      => 'required',
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $vacancy->fill($request);
                    
                    if($vacancy->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'Vacancy updated successfully';
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

    public function toggleVacancyStatus(Request $request){
        try
        {
            $id       = $request->get('id');
            $status   = $request->get('status');
            $vacancy  = Vacancy::find($id);

            if($vacancy){
                $vacancy->status = $status;
                $vacancy->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'Vacancy is enabled successfully' : 'Vacancy is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }
}
