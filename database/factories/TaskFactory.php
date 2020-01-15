<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use App\Task;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->text(20),
        'project_id' => 1,
        'user_id' => 1,
        'deadline_date' => Carbon::now()->addDays(20)
    ];
});
