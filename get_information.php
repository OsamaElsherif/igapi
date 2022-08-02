<?php
session_start();
require_once __DIR__.'/utils/manager.php';

// creating an account instance
$myAccount = new Account();

// getting the needed information about your account
// it takes just on paramaetter which is the fields that you want,
// check the the fields that you can get from instagram graph API DOCs
$information = $myAccount->information('username, name, biography');
echo "<h1>My Account Information</h1>";
echo "<pre>";
print_r($information);
echo "</pre>";

// search for a specific user with the username, however the user must be a business account to
// get information about the account, see the instagram policies for getting searching about users
// it takes just on paramaetter which is the fields that you want,
// check the the fields that you can get from instagram graph API DOCs
$search = $myAccount->search('haltaalam', 'name, biography');
echo "<h1>Searching for @haltaalam</h1>";
echo "<pre>";
print_r($search);
echo "</pre>";

// getting the logged in account information from instagram and save the stories in 
// users/{user_instagram_id}/{story_id}.jpg or .mp4, it depends on the media type of the 
// story, and getting you some extra information about the path and the url, in addation to
// insights, insghts will now work once story published, but it works after a few hours.
// you can change the default metrics in the Story Handler in you the handeler utility
$stories = $myAccount->stories();
echo "<h1>My Stories</h1>";
echo "<pre>";
print_r($stories);
echo "</pre>";