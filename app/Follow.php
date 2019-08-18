<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'following_id'
	];

	/**
	 * Get the user of the current relation.
	 *
	 * @return BelongsTo
	 */
	public function User()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the following user of the current relation.
	 *
	 * @return BelongsTo
	 */
	public function Following()
	{
		return $this->belongsTo(User::class, 'following_id');
	}
}