<?php

function isUserAdmin(){
	if(Auth::guest())
		return false;
	else {
		return (Auth::user()->isAdmin());
	}
}

function app_url(){
	$default = env('APP_URL', 'http://localhost');

	return $default;
}