<?php

namespace App\Policies;

use App\Tweet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can show the tweet.
	 *
	 * @param  User  $user
	 * @param  Tweet  $tweet
	 * @return mixed
	 */
	public function show(User $user, Tweet $tweet)
	{
		return $user->id == $tweet->user_id;
	}

	/**
	 * Determine whether the user can update the tweet.
	 *
	 * @param  User  $user
	 * @param  Tweet  $tweet
	 * @return mixed
	 */
	public function update(User $user, Tweet $tweet)
	{
		return $user->id == $tweet->user_id;
	}

	/**
	 * Determine whether the user can delete the tweet.
	 *
	 * @param  User  $user
	 * @param  Tweet  $tweet
	 * @return mixed
	 */
	public function delete(User $user, Tweet $tweet)
	{
		return $user->id == $tweet->user_id;
	}
}