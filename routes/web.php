<?php

Route::get('/', function () {
    return redirect('login');
});

//login
Route::get('/login', 'UserController@viewLogin');
Route::post('/login', 'UserController@postLogin');

//logout
Route::get('/logout', 'UserController@logout');

// register
Route::get('/register', 'UserController@viewRegister');
Route::post('/register', 'UserController@postRegister');
