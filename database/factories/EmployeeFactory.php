<?php

use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\en_US\Person($faker));
	$faker->addProvider(new \Faker\Provider\en_US\Address($faker));
	$faker->addProvider(new \Faker\Provider\en_US\Payment($faker));
	$faker->addProvider(new \Faker\Provider\DateTime($faker));

	$management_entities = App\ManagementEntity::get()->all();
	$insurance_companies = App\InsuranceCompany::get()->all();
	$genders = ['M', 'F'];

	$gender = $genders[array_rand($genders)];

	return [
		'insurance_company_id' => $insurance_companies[array_rand($insurance_companies)]->id,
		'city_identity_card_id' => rand(1, 9),
		'management_entity_id' => $management_entities[array_rand($management_entities)]->id,
		'identity_card' => $faker->randomNumber(8, true),
		'first_name' => strtoupper((rand(0, 1) == 0) ? $faker->firstname(($gender == 'M') ? 'male' : 'female') : $faker->firstname(($gender ?? 'M') ? 'male' : 'female') . ' ' . $faker->firstname(($gender == 'M') ? 'male' : 'female')),
		'last_name' => strtoupper($faker->lastname),
		'mothers_last_name' => strtoupper($faker->lastname),
		'birth_date' => $faker->date($format = 'Y-m-d', $max = '1999-12-31'),
		'city_birth_id' => rand(1, 9),
		'account_number' => $faker->randomNumber(8, true),
		'country_birth' => 'Bolivia',
		'nua_cua' => $faker->randomNumber(8, true),
		'gender' => $gender,
		'location' => $faker->state(),
		'zone' => $faker->city(),
		'street' => $faker->streetName(),
		'address_number' => $faker->postcode(),
		'phone_number' => $faker->bankRoutingNumber(),
	];
});
