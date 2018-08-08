<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
  'middleware' => 'api',
  'prefix' => 'v1/auth',
], function ($router) {
  Route::post('login', 'AuthController@login');  
  Route::get('logout', 'AuthController@logout');
  Route::get('refresh', 'AuthController@refresh');
  Route::get('me', 'AuthController@me');
});

Route::group([
  //'middleware' => ['api', 'jwt.auth'],
  'middleware' => ['api'],
  'prefix' => 'v1'
], function ($router) {
  Route::resource('company', 'CompanyController')->except(['create', 'edit']);  
  Route::resource('employee', 'EmployeeController')->except(['create', 'edit']);
  Route::resource('city', 'CityController')->except(['create', 'edit']);
  Route::resource('management_entity', 'ManagementEntityController')->except(['create', 'edit']);
  Route::resource('insurance_company', 'InsuranceCompanyController')->except(['create', 'edit']);

});