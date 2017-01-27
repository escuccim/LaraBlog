<?php
if ( ! function_exists('isUserAdmin')) {
    function isUserAdmin(){
        if(Auth::guest())
            return false;
        else {
            return (Auth::user()->type);
        }
    }
}
