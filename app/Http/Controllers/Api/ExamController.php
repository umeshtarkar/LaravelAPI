<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getExams(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $exam = Exam::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            if($request->has('keyword') && !empty($request->keyword)){
                $exam->orWhere('name','like',''. $request->keyword.'%')
                      ->orWhere('detail', 'like',''. $request->keyword.'%');
                      
            }

            $exams = $exam->get();

            if(!$exams->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $exams;
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

    public function getExamDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $exam = Exam::find($id);
            
            if($exam){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $exam;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createExam(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'name'              => 'required|regex:/^([^0-9]*)$/|max:255',
                'detail'            => 'required',
                'cpd_article_id'    => 'required',
                'total_questions'   => 'required|numeric',
                'total_marks'       => 'required|numeric',
                'passing_marks'     => 'required|numeric'
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $exam = new Exam;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'Exam created sucessfully!';
                $this->apiResponse['data']       = $exam->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateExam(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $exam = Exam::find($id);
                
                if($exam){
                    $validator = Validator::make($request, [
                        'name'              => 'required|regex:/^([^0-9]*)$/|max:255',
                        'detail'            => 'required',
                        'cpd_article_id'    => 'required',
                        'total_questions'   => 'required|numeric',
                        'total_marks'       => 'required|numeric',
                        'passing_marks'     => 'required|numeric'
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $exam->fill($request);
                    
                    if($exam->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'Exam updated successfully';
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

    public function toggleExamStatus(Request $request){
        try
        {
            $id       = $request->get('id');
            $status   = $request->get('status');
            $exam  = Exam::find($id);

            if($exam){
                $exam->status = $status;
                $exam->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'Exam is enabled successfully' : 'Exam is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }
}
