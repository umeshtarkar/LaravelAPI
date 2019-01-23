<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->apiResponse['data'] = new \stdClass();
    }

    public function guard()
    {
     return Auth::guard('api');
   }

   public function signin(Request $request){
    
    try{
        $parameters = $request->all();

        $validator = Validator::make( $parameters, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:25',
        ] );
        
        if ( $validator->fails() ) {
            throw new Exception($this->getErrorMessage($validator));
        }
        dd($this->guard());
        if($this->guard()->attempt($parameters)){
            echo 2;
            $token = $this->_generateUserToken();
            $admin = User::where('email',$request->email)->first();
            $admin->auth_token = $token;

            if($admin->save()){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['message']    = 'Logged in sucessfully!';
                $this->apiResponse['data']       = $admin;
            }
        }else{
            $this->apiResponse['statusCode']     = 401;
            $this->apiResponse['message']        = 'Invalid Credentials';
        }
        
        }catch(Exception $e){
            $this->apiResponse['message'] = $e->getMessage();
        }
    }

    public function getUsers(Request $request){
        
        try{
            $this->paginationParametersCheck($request);
            
            if(!$this->is_pagination_params){
                throw new Exception("Pagination parameters are missing.", 1);
            }

            $user = User::orderBy('id','DESC')->skip($this->record_offset)->take($this->records_per_page);
            
            if($request->has('keyword') && !empty($request->keyword)){
                $user->orWhere('name','like',''. $request->keyword.'%')
                      ->orWhere('surname', 'like',''. $request->keyword.'%')
                      ->orWhere('email', 'like',''. $request->keyword.'%')
                      ->orWhere('mobile_no', 'like',''. $request->keyword.'%')
                      ->orWhere('council_reg_no', 'like',''. $request->keyword.'%');
            }
            $usersList = $user->get();
            
            if(!$usersList->isEmpty()){
                
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $usersList;
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

    public function getUsersCount(){
        
        try{
            $userCount = User::where('status',1)->count();
            
            if($userCount){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $userCount;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function getUserDetail($id){
        try{
            
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $user = User::find($id);
            
            if($user){
                $this->apiResponse['statusCode'] = 200;
                $this->apiResponse['status']     = 'success';
                $this->apiResponse['data']       = $user;
            }
        }catch(Exception $e){
            $this->apiResponse = $e->getMessage();
        }
        return $this->apiResponse;
    }

    public function create(Request $request)
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

}
