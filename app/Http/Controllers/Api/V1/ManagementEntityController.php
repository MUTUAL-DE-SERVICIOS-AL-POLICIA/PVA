<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ManagementEntity;

class ManagementEntityController extends Controller
{
    /**
   	* Display a listing of the resource.
   	*
   	* @return \Illuminate\Http\Response
   	*/
	public function index()
	{
		return ManagementEntity::all();
	}
}
