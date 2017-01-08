<?php

namespace Escuccim\LaraBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $fillable = [
			'name',
	];

    /**
     * Each tag can belong to many blogs
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function blogs(){
		return $this->belongsToMany('Escuccim\LaraBlog\Models\Blog');
	}
}
