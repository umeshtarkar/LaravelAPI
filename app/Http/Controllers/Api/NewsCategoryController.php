<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }


    public function getCategory(Request $request){
        
        try{
            
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $categories = NewsCategory::where('status',1)->skip($this->record_offset)->take($this->records_per_page)->select('id','name','picture','status','created_at')->get();
            if(!$categories->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $categories;
            }else{
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['status']     = 'No data found';
            }
        }catch(Exception $e){
            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }

    public function getCategoryCount(Request $request){
        
        try{
            if($request->has('status') && !empty($request->status)){
                $categoryCount = NewsCategory::where('status',1)->count();
            }else{
                $categoryCount = NewsCategory::count();
            }
            
            
            if($categoryCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = "Found $categoryCount Results";
                $this->apiResponse['data']       = $categoryCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function getCategoryNews( Request $request,$id){
        
        try{
            if(!$id){
                throw new Exception("Invalid Request.", 1);
            }

            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $offset  = $this->record_offset;
            $page_no = $this->records_per_page;

            $categories = NewsCategory::with(['news' => function($query) use ($offset,$page_no){
                $query->where('status',1)->orderBy('id','DESC')->skip($offset)->take($page_no);
            }])->where(['status' => 1,'id' => $id])->select('id','name','picture','created_at')->get();

            if(!$categories->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $categories;
            }else{
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['status']     = 'No data found';
            }
        }catch(Exception $e){
            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }

    public function getCategoryDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $category = NewsCategory::find($id);
            
            if($category){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $category;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function createCategory(Request $request){
        try
        {
            $data = $request->all();
            $newsCat = new NewsCategory;
                
            $validator = Validator::make($data, [
                'name'     => 'required|regex:/^([^0-9]*)$/|max:255|unique:news_categories,name',
                ]);
            
            if($validator->fails()){ 
                throw new Exception($this->getErrorMessage($validator));
            }

            if ($request->hasFile('picture')) {
                
                $Imagefile = $request->file('picture');
                $file_name = $Imagefile->getClientOriginalName();
                $file_extn = $Imagefile->getClientMimeType();
                $file_size = $Imagefile->getClientSize();
                
                $file_tmp_path = $Imagefile->getPathname();
                $file_name     = time() . $file_name;
                $data['picture'] = $file_name;
                //Upload new profile picture
                $Imagefile->move(public_path('images'), $file_name);
            }

            $this->apiResponse['statusCode'] = 201;
            $this->apiResponse['status']     = 'success';
            $this->apiResponse['message']    = 'News Category created sucessfully!';
            $this->apiResponse['data']       = $newsCat->create($data);

        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        
        return $this->apiResponse;
    }

    public function updateCategory(Request $request){
        try
        {
            $id = $request->get('id');
            $data = $request->except('id');

            $newsCat = NewsCategory::find($id);
                
            $validator = Validator::make($data, [
                'name'     => 'required|regex:/^([^0-9]*)$/|max:255|unique:news_categories,name,'.$id,
                ]);
            
            if($validator->fails()){ 
                throw new Exception($this->getErrorMessage($validator));
            }

            if ($request->hasFile('picture')) {
                
                $Imagefile = $request->file('picture');
                $file_name = $Imagefile->getClientOriginalName();
                $file_extn = $Imagefile->getClientMimeType();
                $file_size = $Imagefile->getClientSize();
                
                $file_tmp_path = $Imagefile->getPathname();
                $file_name     = time() . $file_name;
                $data['picture'] = $file_name;
                //Upload new profile picture
                $Imagefile->move(public_path('images'), $file_name);
            }
            $newsCat->fill($data);
            if($newsCat->push()){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'News Category updated sucessfully!';
            }
            

        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        
        return $this->apiResponse;
    }

    public function toggleCategoryStatus(Request $request){
        try
        {
            $id = $request->get('id');
            $status = $request->get('status');
            $user = NewsCategory::find($id);
            if($user){
                $user->status = $status;
                $user->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'NewsCategory is enabled successfully' : 'NewsCategory is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }


}
