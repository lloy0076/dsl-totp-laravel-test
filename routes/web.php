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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/welcome', 'HomeController@index')->name('welcome');

Auth::routes();

Route::any('/home', 'HomeController@index')->name('home');
Route::any('/newToken', 'HomeController@generateToken')->name('generateToken');
Route::any('/qrcode', 'HomeController@generateQr')->name('generateQr');
Route::any('/verify', 'HomeController@verify')->name('verify');
Route::any('/performVerify', 'HomeController@performVerify')->name('performVerify');
Route::any('/info', 'HomeController@info')->name('info');
Route::any('/clear', 'HomeController@clear')->name('clear');
