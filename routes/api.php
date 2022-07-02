<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::controller(AuthController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login')->middleware('verify');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/verify', 'verifying');

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('/tags', TagController::class)->except(['create' ,'show' ,'edit']);
    Route::resource('/posts', PostController::class)->except(['create' ,'edit']);

    Route::get('/posts/deleted', [PostController::class, 'viewDeleted']);
    Route::get('/posts/restore/{id}', [PostController::class, 'restore']);

    Route::get('/stats', function () {
        $posts_count = Post::query()->count();
        $user_count = User::query()->count();
        $user_0_posts = User::query()->doesntHave('posts')->count();

        \cache(['users_count' => $user_count, 'posts_count' => $posts_count, 'users_0_posts_count' => $user_0_posts]);

//        Cache::add('users_count' , $user_count);
//        Cache::add('posts_count' , $posts_count);
//        Cache::add('users_0_posts_count' , $user_0_posts);
        return response()->json(['users_count' => $user_count, 'posts_count' => $posts_count, 'users_0_posts_count' => $user_0_posts]);
    });
});
