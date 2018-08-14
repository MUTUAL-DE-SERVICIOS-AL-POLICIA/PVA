<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
	$faker->addProvider(new \Faker\Provider\Lorem($faker));
	$faker->addProvider(new \Faker\Provider\en_US\Company($faker));

	$document_type = App\DocumentType::first();

	return [
		'document_type_id' => $document_type->id,
		'name' => $faker->unique()->company,
		'description' => $faker->sentence(),
	];
});