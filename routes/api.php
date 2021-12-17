<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/users', [UserController::class, 'index'])->name('get-users');

    // CRUD posts
    // view
    Route::get('/posts', [PostController::class, 'index'])->name('get_posts');
    Route::get('/view-post/{id}', [PostController::class, 'show'])->where('id', '\d+')->name('view_post');

    // CREATE
    Route::post('/create-post', [PostController::class, 'store'])->name('create_post');

    // UPDATE
    Route::post('/update-post/{id}', [PostController::class, 'update'])->where('id', '\d+')->name('update_post');

    // DELETE
    Route::post('/delete-post/{id}', [PostController::class, 'destroy'])->where('id', '\d+')->name('remove_post');
});
