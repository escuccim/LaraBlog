<?php

Route::group(['middleware' => ['web']], function() {
    /* Blog admin */
    Route::get('/blog/labels/{id}', 'BlogController@tags');
    Route::resource('blog', 'BlogController');

    /* RSS Feed for Blog */
    Route::get('feed', 'FeedController@generate');

    /* Blog comments */
    Route::post('/blog/comment/add', 'BlogController@comment');
});