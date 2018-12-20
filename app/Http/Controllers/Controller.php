<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $apiResponse = ['statusCode'=>500,'status' => 'error', 'message' => '', 'data' => []];
    
    protected $statusCode;

    public function getErrorMessage($validation)
    {
        $error_messages=array();
        foreach($validation->errors()->toArray() as $error)
        {
            $error_messages[]=$error[0];
        }

        return implode(" ",$error_messages);  
    }
}
