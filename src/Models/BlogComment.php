<?php

namespace Escuccim\LaraBlog\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
	protected $table = 'blogcomments';
	
	protected $guarded = [];

	protected $fillable = [
			'body',
			'user_id',
			'blog_id',
	];
	
	public function author(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function post(){
		return $this->belongsTo('App\Blog', 'blog_id');
	}
}
