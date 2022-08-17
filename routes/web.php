<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

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

// page for Welcoming to the project
Route::get('/', function () {
    return view('welcome');
});

// ---- these two endpoints depends with the database ----

// login to the project
Route::get('login', function() {
    return view('login');
});

// logout
Route::get('logout', function() {
    $_SESSION['user'] = '';
    session_unset();
    return redirect('login');
});

// register for the project
Route::get('register', function() {
    return view('register');
});

// ---- these depends on the acces token in the database ----

// going to the account
Route::get('me/', function() {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $name = User::where('email', $email)->get(['name'])[0]['name'];
        return view('account', ['username' => $name]);
    } else {
        return redirect('login');
    }
});

// getting the stories
Route::get('stories', function() {
    return view('stories', ['username' => 'current']);
});

// ---- these depends on the username in the database ----

// searching for the users
Route::get('user/{username}', function($username) {
    return view('user', ['username' => $username]);
});


// getting the access token for the loged in user
Route::get('user/{username}/stories', function($username) {
    return view('stories', ['username' => $username]);
});
