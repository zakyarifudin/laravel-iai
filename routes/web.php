<?php

Route::get('/', function () {
    return redirect('login');
});

//login
Route::get('/login', 'UserController@viewLogin')->name('login');
Route::post('/login', 'UserController@postLogin');

//logout
Route::get('/logout', 'UserController@logout');

// register
Route::get('/register', 'UserController@viewRegister')->name('register');
Route::post('/register', 'UserController@postRegister');

// question
Route::get('/question', 'QuestionController@index');
