<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
		if ($this->routeIs('api.login'))
			$validation = [
				'secret' => ['required', 'string'],
				'email' => ['required', 'string', 'email', 'max:255'],
				'password' => ['required', 'string', 'between:5,255']
			];
		else if ($this->routeIs('api.register'))
			$validation = [
				'secret' => ['required', 'string'],
				'name' => ['required', 'string', 'between:3,255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
				'password' => ['required', 'string', 'between:5,255', 'confirmed'],
				'image' => ['required', 'image', 'max:5120']
			];
		else if ($this->routeIs('api.profile.update'))
			$validation = [
				'name' => ['string', 'between:3,255'],
				'email' => ['string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
				'password' => ['string', 'between:5,255'],
				'image' => ['image', 'max:5120']
			];

		return $validation;
	}
}