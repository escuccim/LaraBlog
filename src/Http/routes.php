<?php

Route::group(['middleware' => ['web']], function() {
/* Blog admin */
Route::get('/blog/labels/{id}', 'BlogController@tags');
Route::resource('blog', 'BlogController');

/* RSS Feed for Blog */
Route::get('feed', 'FeedController@generate');

/* Blog comments */
Route::post('/blog/comment/add', 'BlogController@comment');

/* Site maps */
Route::get('sitemap', 'SiteMapController@index');
Route::get('sitemap/blog', 'SiteMapController@blog');
Route::get('sitemap/labels', 'SiteMapController@labels');
Route::get('sitemap/pages', 'SiteMapController@pages');
});