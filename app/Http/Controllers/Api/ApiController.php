<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\User;
use App\Models\Admin;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }

    protected function create(Request $request)
    {
        try
        {
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'name' => 'required|regex:/^([^0-9]*)$/|max:50',
                'surname'  => 'required|regex:/^([^0-9]*)$/|max:50',
                'email'      => 'required|email|max:150|unique:users,email',
                'password'   => 'required|min:6|max:25',
                'mobile_no'   => 'required|min:10|max:10|unique:users,mobile_no',
                
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {

                $data = [];
                $data['name']       =  ucfirst($request['name']);
                $data['surname']    =  $request['surname'];
                $data['email']      =  strtolower($request['email']);
                $data['password']   =  bcrypt($request['password']);
                $data['mobile_no']  =  $request['mobile_no'];
                $data['address']    =  $request['address'];

                $data['country_code']   =  $request['country_code'];
                $data['country']        =  $request['country'];
                $data['profession']     =  $request['profession'];
                $data['council_reg_no'] =  $request['council_reg_no'];

                $data['status']     =  0;
                $user = new User;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'User created sucessfully!';
                $this->apiResponse['data']       = $user->create($data);
                // $this->apiResponse['token']     = $user->createToken('Spott')->accessToken;
            }
        }catch(Exception $e) {
            $this->apiResponse['message'] = $e->getMessage();   
        }  
        return $this->apiResponse;
    }


    public function update(Request $request)
    {
        try
        {
            if($request->has('id') && !empty($request->id)){
                
                $id = $request->id;
                $request = $request->all();
                
                $user = User::find($id);
                
                if($user){

                    $validator = Validator::make($request, [
                        'name'     => 'required|regex:/^([^0-9]*)$/|max:50',
                        'surname'  => 'required|regex:/^([^0-9]*)$/|max:50',
                        'email'    => 'required|email|max:150|unique:users,email,'.$id,
                        'password' => 'required|min:6|max:25',
                        'mobile_no'=> 'required|min:10|max:10|unique:users,mobile_no,'.$id
                        
                    ]);
                    
                    if($validator->fails()){ 
                        throw new Exception($this->getErrorMessage($validator));
                    }

                    $data = [];
                    $data['name']       =  ucfirst($request['name']);
                    $data['surname']    =  ucfirst($request['surname']);
                    $data['email']      =  strtolower($request['email']);
                    $data['password']   =  bcrypt($request['password']);
                    $data['mobile_no']  =  $request['mobile_no'];
                    $data['address']    =  $request['address'];
                    
                    $data['country_code'] =  $request['country_code'];
                    $data['country']      =  $request['country'];
                    $data['profession']   =  $request['profession'];
                    $data['council_reg_no'] =  $request['council_reg_no'];
                        
                    $user->fill($data);
                    if($user->push()){
                        $this->apiResponse['statusCode'] = 200;
                        $this->apiResponse['status']     = 'success';
                        $this->apiResponse['message']    = 'User updated successfully';
                    }

                }else{
                    throw new Exception('Invalid Request');
                }
            }
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        
        return $this->apiResponse;
    }


    public function toggleUserStatus(Request $request){
        try
        {
            $id = $request->get('id');
            $status = $request->get('status');
            $user = User::find($id);
            if($user){
                $user->status = $status;
                $user->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'User is enabled successfully' : 'User is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }

    public function createNews(Request $request){
        try{
            $request = $request->all();
            
            $validator = Validator::make($request, [
                'title'              => 'required|regex:/^([^0-9]*)$/|max:255',
                'youtube_video_url'  => 'required|regex:/^([^0-9]*)$/|unique:news,youtube_video_url',
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

            return $this->apiResponse['message'] = $e->getMessage();
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
                        'youtube_video_url'  => 'required|regex:/^([^0-9]*)$/|unique:news,youtube_video_url,'.$id,
                        'news_category_id'   => 'required',
                        'user_id'            =>  'required',
                    ]);
                  
                    if($validator->fails()){
                        throw new Exception($this->getErrorMessage($validator));
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

    public function createAdmin(Request $request){
        try
        {
            $data = $request->all();
            
            $validator = Validator::make($data, [
                'email'      => 'required|email|max:150|unique:admin,email',
                'password'   => 'required|min:6|max:25',
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {
                $request['password'] = bcrypt($request['password']);

                $admin = new Admin;

                $this->apiResponse['statusCode'] = 201;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'Admin created sucessfully!';
                $this->apiResponse['data']       = $admin->create($data);
                // $this->apiResponse['token']     = $user->createToken('Spott')->accessToken;
            }
        }catch(Exception $e) {
            $this->apiResponse['message'] = $e->getMessage();   
        }  
        return $this->apiResponse;
    }
    
    public function updateAdmin(Request $request){
        try
        {
            $id = $request->get('id');
            $data = $request->except('id');
            
            $validator = Validator::make($data, [
                'email'      => 'required|email|max:150|unique:admin,email,'.$id,
                'password'   => 'required|min:6|max:25',
            ]);

            if($validator->fails()){ 

                throw new Exception($this->getErrorMessage($validator));
            } else {
                $request['password'] = bcrypt($request['password']);

                $admin = Admin::find($id);
                $admin->fill($data);
                if($admin->push()){
                    $this->apiResponse['statusCode'] = 200;
                    $this->apiResponse['status']     = 'success';
                    $this->apiResponse['message']    = 'Admin updated sucessfully!';
                }
            }
        }catch(Exception $e) {
            $this->apiResponse['message'] = $e->getMessage();   
        }  
        return $this->apiResponse;
    }

    public function toggleAdminStatus(Request $request){
        try
        {
            $id = $request->get('id');
            $status = $request->get('status');
            $admin = Admin::find($id);
            if($admin){
                $admin->status = $status;
                $admin->save();

                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = ($status == 1) ? 'Admin is enabled successfully' : 'Admin is disabled successfully';
            }else{
                throw new Exception('Invalid Request');
            }
        
        } catch(Exception $e) {

            $this->apiResponse['message'] = $e->getMessage();   
        }
        return $this->apiResponse;
    }

}
