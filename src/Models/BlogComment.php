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
            'parent_comment_id',
	];

    /**
     * Assign each comment to a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function author(){
		return $this->belongsTo('App\User', 'user_id');
	}

    /**
     * Assign each comment to an article
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function post(){
		return $this->belongsTo('Escuccim\LaraBlog\Models\Blog', 'blog_id');
	}

	public function replies(){
	    $replies = BlogComment::where('parent_comment_id', $this->id)->orderBy('created_at', 'asc')->get();
	    return $replies;
    }
}
