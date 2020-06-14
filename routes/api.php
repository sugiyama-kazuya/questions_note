<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/user', function () {
    return Auth::user();
})->name('user');

Route::resource('problems', 'ProblemController');
Route::resource('profile', 'ProfileController');

Route::post('problems/newExerciseName', 'ProblemController@createExerciseBooksName')->name('problems.newExerciseName');

Route::get('likes/count', 'LikeController@index')->name('likes.count');
Route::get('islikedby/{id}', 'isLikedByController');
Route::get('countLikes/{id}', 'CountLikesController');
Route::put('like', 'LikeController@like')->name('like');
Route::delete('unlike', 'LikeController@unlike')->name('unlike');

Route::get('ownExercizeBooks/{id}', 'GetOwnExercizeBooksController');

Route::get('orderFavorite', 'OrderFavoriteController');
Route::get('ownFavoriteExerciseBooks', 'OwnFavoriteExerciseBooksController');

// userに関するルーティング
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('{id}/follow', 'UserController@follow')->name('follow');
        Route::delete('{id}/follow', 'UserController@unfollow')->name('unfollow');
        Route::get('{id}/followers', 'UserController@followersCount')->name('followers');
        Route::get('{id}/follwings', 'UserController@follwingsCount')->name('follwings');
    });
});
