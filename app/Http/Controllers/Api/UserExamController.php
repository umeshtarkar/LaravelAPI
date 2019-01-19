<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\UserExam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getUserExams(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $userExam = UserExam::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            if($request->has('user_id') && !empty($request->user_id) ){
                $userExam->where('user_id',$request->user_id);
            }

            if($request->has('exam_id') && !empty($request->exam_id) ){
                $userExam->where('exam_id',$request->exam_id);
            }

            $userExamList = $userExam->get();

            if(!$userExamList->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $userExamList;
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

    public function getUserExamDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $userExam = UserExam::find($id);
            
            if($userExam){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $userExam;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createUserExam(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'user_id'       => 'required',
                'exam_id'       => 'required',
                'question_id'   => 'required',
                'answer'        => 'required',
                'marks'         => 'required'
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $userExam = new UserExam;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'UserExam created sucessfully!';
                $this->apiResponse['data']       = $userExam->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateUserExam(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id      = $request->id;
                $request = $request->except('id');
                
                $userExam = UserExam::find($id);
                
                if($userExam){
                    $validator = Validator::make($request, [
                        'user_id'       => 'required',
                        'exam_id'       => 'required',
                        'question_id'   => 'required',
                        'answer'        => 'required',
                        'marks'         => 'required'
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $userExam->fill($request);
                    
                    if($userExam->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'UserExam updated successfully';
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

}
