<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name' => 'Roman',
            'email' => 'romgnatyuk@gmail.com',
            'password' => \Hash::make('123456')
        ]);

        factory(User::class, 2);
    }
}
