<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;

Route::get('/users', [UsersController::class,'index' ]);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

#Auth::routes();

#Route::get('/home', [HomeController::class, 'index'])->name('home');
#Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ログイン状態
Route::group(['middleware' => 'auth'], function() {

    // ユーザ関連
    Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit', 'update']]);
    
    // フォロー/フォロー解除を追加
    Route::post('follow/{id}', 'UsersController@follow')->name('follow');
    Route::delete('unfollow/{id}', 'UsersController@unfollow')->name('unfollow');

    // ツイート関連
    Route::resource('tweets', 'TweetsController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
});

