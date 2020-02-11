<?php

use App\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::truncate();
        factory(Project::class, 3)->create();
        factory(Project::class, 3)->create([
            'user_id' => 3
        ]);
    }
}
