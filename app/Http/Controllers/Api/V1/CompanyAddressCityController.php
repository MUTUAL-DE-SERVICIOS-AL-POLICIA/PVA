<?php

namespace App\Http\Controllers\Api\V1;

use App\City;
use App\CompanyAddress;
use App\Http\Controllers\Controller;

/** @resource CompanyAddressCity
 *
 * Resource to show and update CompanyAddress-City relation
 */

class CompanyAddressCityController extends Controller {
	/**
	 * Display the specified city related to a company address.
	 *
	 * @param  \App\CompanyAddress  $employer_number_id
	 * @param  \App\City  $city_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_city($company_address_id, $city_id) {
		$company_address = CompanyAddress::findOrFail($company_address_id);
		if ($company_address->city_id == $city_id) {
			return City::findOrFail($city_id);
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Attach city related to a company address.
	 *
	 * @param  \App\CompanyAddress  $employer_number_id
	 * @param  \App\City  $city_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_city($company_address_id, $city_id) {
		$company_address = CompanyAddress::findOrFail($company_address_id);
		$company_address->city()->associate(City::findOrFail($city_id))->save();
		$company_address->city;
		return $company_address;
	}
}
