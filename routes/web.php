<?php

Route::get('/', function () {
    return redirect('login');
});

//login
Route::get('/login', 'UserController@viewLogin')->name('login');
Route::post('/login', 'UserController@postLogin');

//logout
Route::get('/logout', 'UserController@logout')->name('logout');

// register
Route::get('/register', 'UserController@viewRegister')->name('register');
Route::post('/register', 'UserController@postRegister');

// question
Route::get('/question', 'QuestionController@index')->name('question');
Route::get('/question/{id}', 'QuestionController@show')->name('question.show');
Route::post('/question/store', 'QuestionController@store')->name('question.store');
Route::put('/question/update/{id}', 'QuestionController@update')->name('question.update');
Route::delete('/question/delete/{id}', 'QuestionController@destroy')->name('question.delete');
