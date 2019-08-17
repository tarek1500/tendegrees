<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TweetRequest;
use App\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param  Request  $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		// Get a list of current user's tweets paginated
		return response(['tweets' => $request->user()->tweets()->paginate(10)]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  TweetRequest  $request
	 *
	 * @return Response
	 */
	public function store(TweetRequest $request)
	{
		// Create a tweet for the current user
		$data = $request->only('content');
		$tweet = $request->user()->tweets()->create($data);

		// Return created tweet
		return response(['tweet' => $tweet], 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Request  $request
	 * @param  Tweet  $tweet
	 *
	 * @return Response
	 */
	public function show(Request $request, Tweet $tweet)
	{
		// If user can show this tweet, return it
		if ($request->user()->can('show', $tweet))
			return response(['tweet' => $tweet]);
		// Otherwise return forbidden response
		else
			return response('', 403);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  TweetRequest  $request
	 * @param  Tweet  $tweet
	 *
	 * @return Response
	 */
	public function update(TweetRequest $request, Tweet $tweet)
	{
		// If user can update this tweet
		if ($request->user()->can('update', $tweet))
		{
			// Update new data
			$data = $request->only('content');
			$tweet->update($data);

			// Return updated tweet
			return response(['tweet' => $tweet]);
		}
		// Otherwise return forbidden response
		else
			return response('', 403);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Request  $request
	 * @param  Tweet  $tweet
	 *
	 * @return Response
	 */
	public function destroy(Request $request, Tweet $tweet)
	{
		// If user can delete this tweet
		if ($request->user()->can('delete', $tweet))
		{
			// Update it
			$tweet->delete();

			// Return no content response
			return response('', 204);
		}
		// Otherwise return forbidden response
		else
			return response('', 403);
	}
}