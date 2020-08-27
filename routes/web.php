<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', function () {
    return redirect('home');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
//    Route::resource('users', 'UserController');

    /*
     |--------------------------------------------------------------------------
     | Teste Routes
     |--------------------------------------------------------------------------
    */

    Route::get('/filtrar', 'Controller@getDados');
    Route::get('/updated', 'Controller@updatedActivity');
});
