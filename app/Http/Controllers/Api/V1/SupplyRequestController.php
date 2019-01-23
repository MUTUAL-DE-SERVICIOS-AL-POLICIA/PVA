<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupplyRequest;
use Auth;
class SupplyRequestController extends Controller
{
    /**
	 * Store a newly created resource in request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//$supply_request = SupplyRequest::create();
		return 123;
        return $supply_request;    
    }
}
