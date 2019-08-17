<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$validation = [
			'access_token' => ['required', 'string']
		];

		if ($this->routeIs('api.token.refresh'))
			$validation['refresh_token'] = ['required', 'string'];

		return $validation;
	}
}