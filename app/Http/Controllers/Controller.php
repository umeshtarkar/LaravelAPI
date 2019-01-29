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

    protected $page_number;

    protected $record_offset;

    protected $records_per_page;

    protected $is_pagination_params = false;
        

    public function getErrorMessage($validation)
    {
        $error_messages=array();
        foreach($validation->errors()->toArray() as $error)
        {
            $error_messages[]=$error[0];
        }

        return implode(" ",$error_messages);  
    }


    public function paginationParametersCheck($request)
    {
        try 
        {
            $records_per_page 	= $request->get('records_per_page');
            $page_number 		= $request->get('page_number');

            if ($records_per_page && $page_number) {

                    $this->page_number = $page_number;
                    $this->records_per_page = $records_per_page;

                    $this->record_offset = ($page_number - 1) * $records_per_page;
                    $this->is_pagination_params = true;
            }
        } catch (Exception $e) {
                return $e->getMessage();
        }
    }

    // Generates An Unique Id..
    protected function _generateUserToken() 
    {
    	return md5( uniqid() );
    }
}
