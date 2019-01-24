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
		$supply_request = SupplyRequest::create(['employee_id'=>$request->employee['id']]);				
		$articles = [];
		foreach($request->supplyRequest as $article) {			
			$supply_request->subarticles()->attach([
					$article['id']=> ['amount' => $article['request'] ]
				]);				
		}
        return $supply_request;    
    }
}
