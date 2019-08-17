<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait ResponseTrait
{
	/**
	 * Generate the response for authentication (login/register/token)
	 *
	 * @param  array $tokens
	 *
	 * @return Response
	 */
	public function getAuthResponse(array $tokens = null, $status = 200)
	{
		// If the given secret does not match with any client, return validation errors
		if ($tokens === null)
			return response([
				'message' => 'The given data was invalid.',
				'errors' => ['secret' => ['The secret does not match our records.']]
			], 422);
		// If there is invalid given data
		else if($tokens['status'] == 401)
		{
			// If the user's info is invalid, return validation errors
			if ($tokens['response']['error'] == 'invalid_credentials')
				return response([
					'message' => 'The given data was invalid.',
					'errors' => ['error' => ['These credentials do not match our records.']]
				], 422);
			// If the access/refresh token is invalid (for refresh), return validation errors
			else if ($tokens['response']['error'] == 'invalid_request')
				return response([
					'message' => $tokens['response']['message'],
					'errors' => $tokens['response']['hint']
				], 422);
			// If the client is invalid, return internal server error
			else
				return response('', 500);
		}
		// The user successfully logged in/registered/token refreshed
		else
			return response($tokens['response'], $status);
	}
}