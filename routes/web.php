<?php

use App\Events\ChatMessage;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

Route::get('/admins-only', function () {

    if (Gate::allows('visitAdminPages')) {
        return 'only admins';
    }
    return 'You cannot view this page.';
});

//User Routes
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register')->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->name('loginUser')->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->name('manage-avatar')->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->name('post-avatar')->middleware('mustBeLoggedIn');

//Blog Post Routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->name('create-post')->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->name('submit-post')->middleware('mustBeLoggedIn');
Route::delete('/post/{post}', [PostController::class, 'delete'])->name('post-delete')->middleware('can:delete,post');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->name('post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->name('edit-post')->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->name('submit-edit')->middleware('can:update,post');
Route::get('search/{term}', [PostController::class, 'search'])->name('search');

//Profile Routes
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->name('profile');
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers'])->name('profile-followers');
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing'])->name('profile-following');

Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw'])->name('profile-raw');
Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw'])->name('profile-followers-raw');
Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw'])->name('profile-following-raw');

//Follow Routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->name('create-follow')->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->name('remove-follow')->middleware('mustBeLoggedIn');

//Chat Route
Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();
    return response()->noContent();
})->middleware('mustBeLoggedIn');
