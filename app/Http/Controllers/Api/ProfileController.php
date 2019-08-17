<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	/**
	 * Get the current user profile.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		return response(['user' => $request->user()]);
	}

	/**
	 * Update the current user profile.
	 *
	 * @param  UserRequest  $request
	 * @return Response
	 */
	public function update(UserRequest $request)
	{
		$data = $request->only(['name', 'email']);

		if ($request->has('password'))
			$data['password'] = Hash::make($request->password);

		if ($request->has('image'))
		{
			Storage::delete($request->user()->image);
			$data['image'] = Storage::putFile('images', $request->file('image'));
		}

		$request->user()->update($data);

		return response([], 204);
	}
}