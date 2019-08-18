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
		$count = (int)$this->command->ask('How many users do you need? (default password is: 12345)', 10);
		$this->command->info("Creating {$count} users.");

		if (User::where('email', 'admin@domain.com')->count() == 0)
		{
			factory(User::class)->create([
				'name' => 'Admin',
				'email' => 'admin@domain.com',
				'password' => Hash::make('admin')
			]);
			$count--;
		}

		factory(User::class, $count)->create();
		$this->command->info('Users Created!');
	}
}