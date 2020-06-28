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

Route::resource('exercise-books', 'ExerciseBookController')->only(['store', 'show', 'index']);
// Route::post('exercise-books', 'CreateExerciseBookCheck');
// Route::get('ownExercizeBooks/{id}', 'GetOwnExercizeBooksController');

Route::resource('categories', 'CategoryController')->only(['store']);

// Route::post('problems/categoryCheck', 'CategoryDuplicateCheck');
Route::resource('profile', 'ProfileController')->only(['show', 'edit', 'update']);

// 問題集の取得（項目別）
Route::get('order-favorite-exercise-books', 'OrderFavoriteController');
Route::get('own-favorite-exercise-books', 'OwnFavoriteExerciseBooksController');


// お気に入り関連
Route::put('like', 'LikeController@like')->name('like');
Route::delete('unlike', 'LikeController@unlike')->name('unlike');

Route::get('isLikedby/{id}', 'isLikedByController');
Route::get('likes/{id}/count', 'CountLikesController');


// user関連
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('{id}/follow', 'UserController@follow')->name('follow');
        Route::delete('{id}/follow', 'UserController@unfollow')->name('unfollow');
        Route::get('{id}/followers', 'UserController@followersCount')->name('followers');
        Route::get('{id}/follwings', 'UserController@follwingsCount')->name('follwings');
    });
});
