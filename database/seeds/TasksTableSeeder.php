<?php

use App\Task;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::truncate();
        Task::create([
            'title' => 'test',
            'project_id' => 1,
            'user_id' => 1,
            'is_done' => 1,
            'finished_date' => Carbon::now(),
            'deadline_date' => Carbon::now()->addDays(20)
        ]);
        factory(Task::class, 4)->create();
        factory(Task::class, 4)->create([
            'user_id' => 2,
            'project_id' => 2,
            'deadline_date' => new Carbon('2020-02-11 00:00:00')
        ]);

        Task::create([
            'title' => 'test',
            'project_id' => 2,
            'user_id' => 3,
            'is_done' => 1,
            'finished_date' => null,
            'deadline_date' => new Carbon('2020-02-01 00:00:00')
        ]);

        Task::create([
            'title' => 'test',
            'project_id' => 1,
            'user_id' => 3,
            'is_done' => 1,
            'finished_date' => null,
            'deadline_date' => new Carbon('2020-03-01 00:00:00')
        ]);
    }
}
