<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'profile_image'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'image'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::deleting(function ($user) {
			// Delete image whenever delete method is called in this model
			Storage::delete($user->image);
		});
	}

	/**
	 * Get the profile image.
	 *
	 * @return \Illuminate\Support\Facades\Storage
	 */
	public function getProfileImageAttribute()
	{
		$path = $this->image;

		if (Storage::disk('local')->exists($path))
			return 'data:' . Storage::mimeType($path) . ';base64,' . base64_encode(Storage::get($path));

		return null;
	}
}