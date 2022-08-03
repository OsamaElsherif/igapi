<?php
session_start();
require_once __DIR__.'/utils/manager.php';

$base_url = "https://".$_SERVER['SERVER_NAME']."/igapi/API";

// creating an account instance
// $myAccount = new Account();


// getting the needed information about your account
// it takes just on paramaetter which is the fields that you want,
// check the the fields that you can get from instagram graph API DOCs
echo "<h1>My Account Information</h1>";
$api_call = new APICall($base_url."/account/".$_SESSION['data']['token']."/me", ['fields' => 'username, biography']);
$response = $api_call->make_call();
echo "<pre>";
echo print_r($response);
echo "</pre>";

$users_db = json_decode(file_get_contents('users.json'));

if ($users_db) {
    $users = json_decode(json_encode($users_db), true);
    $users[$response['username']] = $response;
    $fp = fopen('users.json', 'w');
    fwrite($fp, json_encode($users));
    fclose($fp);
} else {
    $users = array();
    $users[$response['username']] = $response;
    $fp = fopen('users.json', 'w');
    fwrite($fp, json_encode($users));
    fclose($fp);
}

echo "<hr>";

// search for a specific user with the username, however the user must be a business account to
// get information about the account, see the instagram policies for getting searching about users
// it takes just on paramaetter which is the fields that you want,
// check the the fields that you can get from instagram graph API DOCs
echo "<h1>Searched for @haltaalam</h1>";
$api_call = new APICall($base_url."/account/".$_SESSION['data']['token']."/me", ['fields' => 'username, biography', 'username'=>'haltaalam']);
echo "<pre>";
echo print_r($api_call->make_call());
echo "</pre>";

echo "<hr>";

// getting the logged in account information from instagram and save the stories in 
// users/{user_instagram_id}/{story_id}.jpg or .mp4, it depends on the media type of the 
// story, and getting you some extra information about the path and the url, in addation to
// insights, insghts will now work once story published, but it works after a few hours.
// you can change the default metrics in the Story Handler in you the handeler utility
echo "<h1>my stories</h1>";
$api_call = new APICall($base_url."/account/".$_SESSION['data']['token']."/stories", []);
echo "<pre>";
echo print_r($api_call->make_call());
echo "</pre>";