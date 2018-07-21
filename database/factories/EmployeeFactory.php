<?php

use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
  $faker->addProvider(new \Faker\Provider\en_US\Person($faker));
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
    'first_name' => ($gender == 'M') ? $faker->firstname('male') : $faker->firstname('female'),
    'second_name' => ($gender == 'M') ? $faker->firstname('male') : $faker->firstname('female'),
    'last_name' => $faker->lastname,
    'mothers_last_name' => $faker->lastname,
    'surname_husband' => '',
    'birth_date' => $faker->date($format = 'Y-m-d', $max = '1999-12-31'),
    'city_birth_id' => rand(1, 9),
    'account_number' => $faker->randomNumber(8, true),
    'country_birth' => 'Bolivia',
    'nua_cua' => $faker->randomNumber(8, true),
    'gender' => $gender,
  ];
});
