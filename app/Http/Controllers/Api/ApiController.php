<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
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
