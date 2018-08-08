<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InsuranceCompany;

class InsuranceCompanyController extends Controller
{
	/**
   	* Display a listing of the resource.
   	*
   	* @return \Illuminate\Http\Response
   	*/
	public function index()
	{
		return InsuranceCompany::all();
	}
}
