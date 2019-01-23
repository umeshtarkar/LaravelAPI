<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }
    
    public function getNews(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }
            if($request->has('mostPopular') && $request->get('mostPopular')){
                $news = News::orderBy('views','DESC')->skip($this->record_offset)->take($this->records_per_page)->get();
            }else{
                $news = News::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page)->get();
            }
            
            
            if(!$news->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $news;
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

    public function getNewsCount(){
        
        try{
            $newsCount = News::where('status',1)->count();
            
            if($newsCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $newsCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function getNewsDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $news = News::find($id);
            
            if($news){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $news;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createNews(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'title'              => 'required|regex:/^([^0-9]*)$/|max:255',
                'news_category_id'   => 'required',
                'user_id'            =>  'required',
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $news = new News;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'News created sucessfully!';
                $this->apiResponse['data']       = $news->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateNews(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $news = News::find($id);
                
                if($news){
                    $validator = Validator::make($request, [
                        'title'              => 'required|regex:/^([^0-9]*)$/|max:255',
                        'news_category_id'   => 'required',
                        'user_id'            =>  'required',
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    if(empty($request['picture_large']) ){
                        
                        unset($request['picture_large']);
                    }

                    if(empty($request['picture_small'])){
                        unset($request['picture_small']);
                    }
                   
                    $news->fill($request);
                    
                    if($news->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'News updated successfully';
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

    public function toggleNewsStatus(Request $request){
        try
        {
            $id = $request->get('id');
            $status = $request->get('status');
            $user = News::find($id);
            if($user){
                $user->status = $status;
                $user->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'News is enabled successfully' : 'News is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }
}
