<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Project;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->words(1, true),
        'description' => $faker->text(),
        'budget' => round($faker->numberBetween(5000, 15000), -3),
        'user_id' => 1,
        'start_date' => Carbon::now(),
        'deadline_date' => Carbon::now()->addMonth()
    ];
});
