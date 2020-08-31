<?php

use Illuminate\Support\Facades\Auth;

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// パスワードリセット
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

// 問題関連
Route::resource('problems', 'ProblemController')->except(['index']);

// 問題集関連
Route::resource('exercise-books', 'ExerciseBookController')->only(['index', 'store', 'show', 'destroy']);
Route::get('own/exercise-books/problems', 'OwnExerciseBooksController');

// ユーザー関連
Route::resource('users', 'UserController')->only(['show', 'edit', 'update']);
Route::prefix('users')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('{id}/followers', 'OwnFollowersController');
        Route::get('{id}/follows', 'OwnFollowsController');
        Route::put('{id}/follow', 'FollowController');
        Route::delete('{id}/follow', 'unFollowController');
    });
});
Route::prefix('users')->group(function () {
    Route::get('{id}/followers/count', 'FollowersCountController');
    Route::get('{id}/follwings/count', 'FollwingsCountController');
});
Route::get('/user', function () {
    return Auth::user();
});


// お気に入り関連
Route::put('favorites', 'FavoritesController');
Route::delete('unfavorites', 'UnFavoritesController');
Route::get('favorites/asc', 'FavoritesOrderByDescController');
Route::get('favorites/asc/{user_id}', 'OwnFavoritesOrderByDescController');
Route::get('{id}/favorites/counts', 'FavoritesCountController');
