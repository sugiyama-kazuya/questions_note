<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::resource('problems', 'ProblemController')->except(['index']);

Route::resource('exercise-books', 'ExerciseBookController')->only(['index', 'store', 'show']);
Route::prefix('exercise-books')->name('exercise-books.')->group(function () {
    Route::put('favorites', 'Favorites')->name('like');
    Route::delete('unfavorites', 'unFavorites')->name('unlike');
    Route::get('favorites/asc', 'FavoritesOrderByAsc');
    Route::get('favorites/asc/{user_id}', 'OwnFavoritesOrderByAsc');
    Route::get('{id}/favorites/counts', 'FavoritesCount');
});

Route::resource('users', 'UserController')->only(['show', 'edit', 'update']);
Route::prefix('users')->name('users.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('{id}/follow', 'Follow')->name('follow');
        Route::delete('{id}/follow', 'unFollow')->name('unfollow');
        Route::get('{id}/followers', 'FollowersCount')->name('followers');
        Route::get('{id}/follwings', 'FollwingsCount')->name('follwings');
    });
});

Route::resource('categories', 'CategoryController')->only(['store']);






// お気に入り関連
Route::put('like', 'LikeController@like')->name('like');
Route::delete('unlike', 'LikeController@unlike')->name('unlike');

Route::get('isLikedby/{id}', 'isLikedByController');
Route::get('{id}/favorites/counts', 'FavoritesCount');


Route::get('/user', function () {
    return Auth::user();
})->name('user');
