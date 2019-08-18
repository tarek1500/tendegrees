<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowRequest;
use App\User;
use Illuminate\Http\Request;

class FollowingController extends Controller
{
	/**
	 * Get a list of followers by the current user.
	 *
	 * @param  Request  $request
	 *
	 * @return Response
	 */
	public function following(Request $request)
	{
		return response(['following' => $request->user()->following()->with('user')->get()]);
	}

	/**
	 * Get a list of current user's followers.
	 *
	 * @param  Request  $request
	 *
	 * @return Response
	 */
	public function followers(Request $request)
	{
		return response(['followers' => $request->user()->followers()->with('user')->get()]);
	}

	/**
	 * Follow a user.
	 *
	 * @param  FollowRequest  $request
	 *
	 * @return Response
	 */
	public function follow(FollowRequest $request)
	{
		$user = $request->user();

		// If current user can follow another user
		if ($user->can('follow', User::find($request->following_id)))
		{
			// Follow him
			$data = $request->only('following_id');
			$user->following()->create($data);

			// Return no content response
			return response('', 204);
		}
		// Otherwise return forbidden response
		else
			return response('', 403);
	}

	/**
	 * Unfollow a user.
	 *
	 * @param  FollowRequest  $request
	 *
	 * @return Response
	 */
	public function unfollow(FollowRequest $request)
	{
		$user = $request->user();

		// If current user can follow another user
		if ($user->can('unfollow', User::find($request->following_id)))
		{
			// Unfollow him
			$data = $request->only('following_id');
			$user->following()->where($data)->delete();

			// Return no content response
			return response('', 204);
		}
		// Otherwise return forbidden response
		else
			return response('', 403);
	}
}