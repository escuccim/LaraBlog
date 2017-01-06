<?php

namespace Escuccim\LaraBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $fillable = [
			'name',
	];

	/* Get the articles associated with this tag */
	public function blogs(){
		return $this->belongsToMany('Escuccim\LaraBlog\Models\Blog');
	}
}
