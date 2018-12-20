<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
                $data['country_code'] =  $request['country_code'];
                $data['country']     =  $request['country'];
                $data['profession']  =  $request['profession'];
                $data['council_reg_no'] =  $request['council_reg_no'];

                $data['status'] =  0;
                $user = new User;

                $this->apiResponse['statusCode']    = 201;
                $this->apiResponse['status'] = 'success';
                $this->apiResponse['message']   = 'User created sucessfully!';
                $this->apiResponse['data']      = $user->create($data);
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
                        'name' => 'required|regex:/^([^0-9]*)$/|max:50',
                        'surname'  => 'required|regex:/^([^0-9]*)$/|max:50',
                        'email'      => 'required|email|max:150|unique:users,email,'.$id,
                        'password'   => 'required|min:6|max:25',
                        'mobile_no'   => 'required|min:10|max:10|unique:users,mobile_no,'.$id
                        
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
                    $data['country']     =  $request['country'];
                    $data['profession']  =  $request['profession'];
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


    public function activate_deactivate(Request $request){
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

}
