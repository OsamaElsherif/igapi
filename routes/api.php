<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create', function(Request $request) {
    $request = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password
    ];
    User::create($request);
    return redirect('/login');
});

Route::post('/login', function(Request $request) {
    $email = $request->email;
    $password = $request->password;

    $user = User::where('email', $email)->get();
    if (!empty($user[0])) {
        if ($user[0]->password == $password) {
            $username = ($user[0]->username != '') ? $user[0]->username : $user[0]->name ;
            return redirect("/me/$username");
        } else {
            return "password is incorrect";
        }
    } else {
        return "user does not exist";
    }
});