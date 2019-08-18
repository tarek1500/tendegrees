<?php

use App\Tweet;
use App\User;
use Illuminate\Database\Seeder;

class TweetsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$range = 1 . '-' . 10;
		$tweetsRange = $this->command->ask('How many tweets per user do you need?', $range);
		$this->command->info("Creating {$tweetsRange} tweets for each user.");

		User::orderBy('id')->each(function ($user) use ($tweetsRange) {
			$count = $this->count($tweetsRange);
			$tweets = factory(Tweet::class, $count)->make();
			$user->tweets()->saveMany($tweets);
		});

		$this->command->info('Tweets Created!');
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