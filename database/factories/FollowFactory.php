<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Follow;
use Faker\Generator as Faker;

$factory->define(Follow::class, function (Faker $faker) {
	return [
		'user_id' => $faker->randomNumber(),
		'following_id' => $faker->randomNumber()
	];
});