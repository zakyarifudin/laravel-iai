<?php

Route::get('/', function () {
    return redirect('login');
});

//login
Route::get('/login', 'UserController@viewLogin')->name('login')->middleware('isnotlogin');
Route::post('/login', 'UserController@postLogin');

//logout
Route::get('/logout', 'UserController@logout')->name('logout')->middleware('islogin');

// register
Route::get('/register', 'UserController@viewRegister')->name('register')->middleware('isnotlogin');
Route::post('/register', 'UserController@postRegister');

// question
Route::get('/question', 'QuestionController@index')->name('question')->middleware('islogin');
Route::get('/question/{id}', 'QuestionController@show')->name('question.show')->middleware('islogin');
Route::post('/question/store', 'QuestionController@store')->name('question.store')->middleware('islogin');
Route::put('/question/update/{id}', 'QuestionController@update')->name('question.update')->middleware('islogin');
Route::delete('/question/delete/{id}', 'QuestionController@destroy')->name('question.delete')->middleware('islogin');

// comment
Route::post('/question/{id}/comment/store', 'CommentController@store')->name('comment.store')->middleware('islogin');
Route::put('/question/comment/update/{id}', 'CommentController@update')->name('comment.update')->middleware('islogin');
Route::delete('/question/comment/delete/{id}', 'CommentController@destroy')->name('comment.delete')->middleware('islogin');
