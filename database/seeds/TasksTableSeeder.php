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
    }
}
