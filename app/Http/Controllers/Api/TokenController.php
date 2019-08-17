<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Http\Traits\JsonTrait;
use App\Http\Traits\ResponseTrait;
use DateTime;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleException;
use Illuminate\Http\Response;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser;

class TokenController extends Controller
{
	use JsonTrait, ResponseTrait;

	/**
	 * Check if token is expired.
	 *
	 * @param  TokenRequest  $request
	 *
	 * @return Response
	 */
	public function checkToken(TokenRequest $request)
	{
		// Check token validation
		$valid = $this->checkTokenValidation($request->access_token);

		// Return token state
		return response(['valid' => $valid]);
	}

	/**
	 * Refresh access token.
	 *
	 * @param  TokenRequest  $request
	 *
	 * @return Response
	 */
	public function refreshToken(TokenRequest $request)
	{
		// Refresh tokens
		$tokens = $this->refreshTokens($request->access_token, $request->refresh_token);

		// Return response to source
		return $this->getAuthResponse($tokens);
	}

	/**
	 * Refresh the existed tokens.
	 *
	 * @param  string  $accessToken
	 * @param  string  $refreshToken
	 *
	 * @return array|null
	 */
	private function refreshTokens(string $accessToken, string $refreshToken)
	{
		// Get the token instance
		$token = $this->decodeAccessToken($accessToken);

		// If the token is not valid
		if ($token === null)
			return [
				'response' => [
					'error' => 'invalid_request',
					'error_description' => 'The access token is invalid.',
					'hint' => 'Cannot decrypt the access token',
					'message' => 'The access token is invalid.'
				],
				'status' => 401
			];

		// Get the client for this token
		$client = $token->client;

		// If there is no clients, return null
		if ($client === null)
			return null;

		// Otherwise refresh tokens
		try
		{
			$response = (new GuzzleClient)->post(route('passport.token'), [
				'form_params' => [
					'grant_type' => 'refresh_token',
					'refresh_token' => $refreshToken,
					'client_id' => $client->id,
					'client_secret' => $client->secret
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
	 * Check if the access token is valid.
	 *
	 * @param  string  $token
	 *
	 * @return bool
	 */
	private function checkTokenValidation(string $token)
	{
		// Get the token instance
		$token = $this->decodeAccessToken($token);

		if ($token)
		{
			// Check if the token is revoked or expired
			$currentDate = new DateTime();
			$expireDate = new DateTime($token->expires_at);

			// Return true, if the token is valid
			if (!$token->revoked && $expireDate > $currentDate)
				return true;
		}

		// Return false, if the token is not valid
		return false;
	}

	/**
	 * Decode the given access token, and returns the token instance.
	 *
	 * @param  string  $token
	 *
	 * @return Token|null
	 */
	private function decodeAccessToken(string $token)
	{
		// Get the token id from the header
		try
		{
			$id = (new Parser)->parse($token)->getHeader('jti');
		}
		// Error while trying to read the header
		catch (Exception $ex)
		{
			return null;
		}

		// Return the token instance
		return Token::find($id);
	}
}