<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tweet extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'content'
	];

	/**
	 * Get the user of this tweet.
	 *
	 * @return BelongsTo
	 */
	public function User()
	{
		return $this->belongsTo(User::class);
	}
}