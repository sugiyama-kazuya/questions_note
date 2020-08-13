<?php

use Illuminate\Support\Facades\Auth;

Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::resource('problems', 'ProblemController')->except(['index']);

Route::resource('exercise-books', 'ExerciseBookController')->only(['index', 'store', 'show', 'destroy']);

Route::resource('users', 'UserController')->only(['show', 'edit', 'update']);

Route::prefix('users')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('{id}/followers', 'OwnFollowersController');
        Route::get('{id}/follows', 'OwnFollowsController');
    });
});
Route::prefix('users')->group(function () {
    Route::get('{id}/followers/count', 'FollowersCountController');
    Route::get('{id}/follwings/count', 'FollwingsCountController');
});

Route::put('favorites', 'FavoritesController');
Route::delete('unfavorites', 'UnFavoritesController');
Route::get('favorites/asc', 'FavoritesOrderByDescController');
Route::get('favorites/asc/{user_id}', 'OwnFavoritesOrderByDescController');
Route::get('{id}/favorites/counts', 'FavoritesCountController');
Route::get('own/exercise-books/problems', 'OwnExerciseBooksController');

Route::get('/user', function () {
    return Auth::user();
});
