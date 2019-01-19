<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getExamQuest(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $examQ = ExamQuestion::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            if($request->has('exam_id') && !empty($request->exam_id) ){
                $examQ->orWhere('exam_id',$request->exam_id);
            }

            $examQuests = $examQ->get();

            if(!$examQuests->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $examQuests;
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

    public function getExamQuestDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }

            $examQ = ExamQuestion::find($id);
            
            if($examQ){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $examQ;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createExamQuest(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'exam_id'   => 'required',
                'question'  => 'required',
                'answer'    => 'required',
                'marks'     => 'required|numeric'
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $examQ = new ExamQuestion;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'ExamQuestion created sucessfully!';
                $this->apiResponse['data']       = $examQ->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateExamQuest(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $exam = ExamQuestion::find($id);
                
                if($exam){
                    $validator = Validator::make($request, [
                        'exam_id'   => 'required',
                        'question'  => 'required',
                        'answer'    => 'required',
                        'marks'     => 'required|numeric'
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $exam->fill($request);
                    
                    if($exam->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'ExamQuestion updated successfully';
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
