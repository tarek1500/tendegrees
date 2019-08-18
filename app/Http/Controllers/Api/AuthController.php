<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\JsonTrait;
use App\Http\Traits\ResponseTrait;
use App\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Client as PassportClient;

class AuthController extends Controller
{
	use JsonTrait, ResponseTrait;

	/**
	 * Handle a login request to the application.
	 *
	 * @param  UserRequest  $request
	 *
	 * @return Response
	 */
	public function login(UserRequest $request)
	{
		// Get secret key from request
		$secret = $request->secret;

		// If secret key is valid
		if ($secret == 'test123')
		{
			// Create tokens
			$tokens = $this->createTokens($request->email, $request->password);

			// Return response to source
			return $this->getAuthResponse($tokens);
		}

		// Otherwise return null
		return $this->getAuthResponse(null);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  Request  $request
	 *
	 * @return Response
	 */
	public function logout(Request $request)
	{
		// Get current user token
		$token = $request->user()->token();
		// Revoke refresh token
		DB::table('oauth_refresh_tokens')->where('access_token_id', $token->id)->update([
			'revoked' => true
		]);
		// Revoke access token
		$token->revoke();

		// Return response to source
		return response('');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  UserRequest  $request
	 *
	 * @return Response
	 */
	public function register(UserRequest $request)
	{
		// Get secret key from request
		$secret = $request->secret;

		// If secret key is valid
		if ($secret == 'test123')
		{
			// Register the user
			$data = $request->only(['name', 'email']);
			$data['password'] = Hash::make($request->password);
			$data['image'] = Storage::putFile('images', $request->file('image'));
			User::create($data);

			// Create tokens
			$tokens = $this->createTokens($request->email, $request->password);

			// Return response to source
			return $this->getAuthResponse($tokens, 201);
		}

		// Otherwise return null
		return $this->getAuthResponse(null);
	}

	/**
	 * Create new tokens for the current user.
	 *
	 * @param  string  $email
	 * @param  string  $password
	 *
	 * @return array|null
	 */
	private function createTokens(string $email, string $password)
	{
		// Get the first password grant client
		$client = $this->getPassportClient();

		// If there is no clients, return null
		if ($client === null)
			return null;
		
		// Otherwise create tokens for the current user
		try
		{
			$response = (new GuzzleClient)->post(route('passport.token'), [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => $client->id,
					'client_secret' => $client->secret,
					'username' => $email,
					'password' => $password
				],
			]);
		}
		// Catch any client exception
		catch (GuzzleException $ex)
		{
			$response = $ex->getResponse();
		}

		// Return ok/error messages
		return ['response' => $this->getResponseBodySummary($response), 'status' => $response->getStatusCode()];
	}

	/**
	 * Get the first password grant client.
	 *
	 * @return PassportClient|null
	 */
	private function getPassportClient()
	{
		return PassportClient::where(['personal_access_client' => false, 'password_client' => true])->first();
	}
}