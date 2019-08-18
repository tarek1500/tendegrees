<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		if ($this->command->confirm('Do you wish to refresh migration before seeding? NOTE: it will clear all old data.'))
		{
			Storage::deleteDirectory('images');
			$this->command->call('migrate:fresh');
			$this->command->call('passport:install', ['--force' => true]);
			$this->command->line("Database cleared.");
		}

		$this->call(UsersTableSeeder::class);
		$this->call(TweetsTableSeeder::class);
		$this->call(FollowsTableSeeder::class);
    }
}