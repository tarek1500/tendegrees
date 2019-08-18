<?php

namespace App\Policies;

use App\Follow;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can follow another user.
	 *
	 * @param  User  $user
	 * @param  User  $follow
	 * @return mixed
	 */
	public function follow(User $user, User $follow)
	{
		$follow = Follow::where([
			'user_id' => $user->id,
			'following_id' => $follow->id
		])->first();

		return is_null($follow);
	}

	/**
	 * Determine whether the user can unfollow another user.
	 *
	 * @param  User  $user
	 * @param  User  $follow
	 * @return mixed
	 */
	public function unfollow(User $user, User $follow)
	{
		$follow = Follow::where([
			'user_id' => $user->id,
			'following_id' => $follow->id
		])->first();

		return !is_null($follow);
	}
}