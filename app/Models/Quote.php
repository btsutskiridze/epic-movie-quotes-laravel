<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory,HasTranslations;

	public $translatable = ['title'];

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class, 'movie_id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
}