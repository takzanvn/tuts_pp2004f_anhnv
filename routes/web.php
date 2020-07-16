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
app('debugbar')->disable();

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->as('admin.')->middleware('admin.auth')->group(function(){
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('login.submit');
    Route::fallback('AdminController@page404');

    Route::get('/', 'AdminController@dashboard');
    Route::get('logout', 'Auth\AdminLoginController@logout');

    Route::match(['PUT', 'PATCH'], 'posts/{post}/unpublish', 'PostController@unpublish')->name('posts.unpublish');
    Route::resource('posts', 'PostController')->except(['edit', 'update', 'destroy']);

    Route::get('drafts/trashed', 'DraftController@trashedIndex')->name('drafts.trashed');
    Route::get('drafts/{draft}/trash', 'DraftController@trash')->name('drafts.trash');
    Route::match(['PUT', 'PATCH'], 'drafts/{draft}/trashed-update', 'DraftController@trashedUpdate')->name('drafts.trashed_update');
    Route::match(['PUT', 'PATCH'], 'drafts/{draft}/publish', 'DraftController@publish')->name('drafts.publish');
    Route::resource('drafts', 'DraftController')->except(['create', 'store', 'destroy']);
});
