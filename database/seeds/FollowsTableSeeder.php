<?php

use App\User;
use Illuminate\Database\Seeder;

class FollowsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$range = 1 . '-' . 5;
		$followsRange = $this->command->ask('How many follows per user do you need?', $range);
		$this->command->info("Creating {$followsRange} follows for each user.");

		User::orderBy('id')->each(function ($user) use ($followsRange) {
			$count = $this->count($followsRange);

			for ($i = 0; $i < $count; $i++)
			{
				$follow = User::inRandomOrder()->first();

				if ($user->isNot($follow) && $user->can('follow', $follow))
					$user->following()->create(['following_id' => $follow->id]);
			}
		});

		$this->command->info('Follows Created!');
	}

	/**
	 * Generate random number from range.
	 *
	 * @param  string  $range
	 *
	 * @return int
	 */
	function count(string $range)
	{
		$range = str_replace(',', '-', $range);
		$range = str_replace(' ', '', $range);

		return rand(...explode('-', $range));
	}
}