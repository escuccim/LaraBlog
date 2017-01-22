<?php

if ( ! function_exists('setLanguage')) {
    function setLanguage(){
        App::setLocale( session('locale') ? session('locale') : config('app.locale'));
        if (App::getLocale() != config('app.locale')) {
            setlocale(LC_TIME, 'fr_CH.UTF-8');
    }
}