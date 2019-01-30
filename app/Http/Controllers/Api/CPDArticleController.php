<?php

namespace App\Http\Controllers\Api;

use Validator;
use Exception;
use App\Models\CPDArticle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CPDArticleController extends Controller
{
    
    public function getCPDArticles(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }
            if($request->has('mostPopular') && !empty($request->mostPopular)){
                $articles = CPDArticle::orderBy('views','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            }else{
                $articles = CPDArticle::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            }
            
            if($request->has('status') && !empty($request->status)){
                $articles->where('status',1);
            }

            if($request->has('keyword')){
                $articles->orWhere('title','like',''. $request->keyword.'%')
                      ->orWhere('subtitle', 'like',''. $request->keyword.'%');
            }

            $articleList = $articles->get();
            
            if(!$articleList->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $articleList;
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

    public function getCPDArticlesCount(Request $request){
        
        try{
            if($request->has('status') && !empty($request->status)){
                $articlesCount = CPDArticle::where('status',1)->count();
            }else{
                $articlesCount = CPDArticle::count();
            }
            
            if($articlesCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = "Found $articlesCount Results";
                $this->apiResponse['data']       = $articlesCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }
    
    public function getCPDArticleDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $article = CPDArticle::find($id);
            $article->views = $article->views+1;
            $article->save();
            
            if($article){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $article;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createCPDArticle(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'title'              => 'required|regex:/^([^0-9]*)$/|max:255',
                'youtube_video_url'  => 'unique:news,youtube_video_url',
                'news_category_id'   => 'required',
                'user_id'            =>  'required',
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $article = new CPDArticle;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'CPDArticle created sucessfully!';
                $this->apiResponse['data']       = $article->create($request);
            }
        }catch(Exception $e){

            $this->apiResponse['message'] = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function updateCPDArticle(Request $request){
        try{
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->except('id');
                
                $article = CPDArticle::find($id);
                
                if($article){
                    $validator = Validator::make($request, [
                        'title'              => 'required|regex:/^([^0-9]*)$/|max:255',
                        'youtube_video_url'  => 'unique:news,youtube_video_url,'.$id,
                        'news_category_id'   => 'required',
                        'user_id'            =>  'required',
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
                    } 
                    
                    $article->fill($request);
                    
                    if($article->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'CPDArticle updated successfully';
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

    public function toggleCPDArticle(Request $request){
        try
        {
            $id = $request->get('id');
            $status = $request->get('status');
            $article = CPDArticle::find($id);
            if($article){
                $article->status = $status;
                $article->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'CPDArticle is enabled successfully' : 'CPDArticle is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }
}
