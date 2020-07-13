<?php

use Illuminate\Support\Facades\Auth;

Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::resource('problems', 'ProblemController')->except(['index']);

Route::resource('exercise-books', 'ExerciseBookController')->only(['index', 'store', 'show', 'destroy']);

Route::resource('users', 'UserController')->only(['show', 'edit', 'update']);

Route::prefix('users')->name('users.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('{id}/follow', 'FollowController')->name('follow');
        Route::delete('{id}/follow', 'unFollowController')->name('unfollow');
        Route::get('{id}/followers', 'FollowersCountController')->name('followers');
        Route::get('{id}/follwings', 'FollwingsCountController')->name('follwings');
    });
});

Route::put('favorites', 'Favorites')->name('favorites');
Route::delete('unfavorites', 'UnFavoritesController');
Route::get('favorites/asc', 'FavoritesOrderByDescController');
Route::get('favorites/asc/{user_id}', 'OwnFavoritesOrderByDescController');
Route::get('{id}/favorites/counts', 'FavoritesCount');
Route::get('own/exercise-books/problems', 'OwnExerciseBooksController');
Route::get('/user', function () {
    return Auth::user();
})->name('user');
