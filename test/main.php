<?php
session_start();

// the main file for the IGAPI class
include 'IGAPI.php';

$igapi = new IGAPI();

// defines is an array that contains all the needed credentials for
// IGAPI to work, all the following information are needed, fill it with
// yout own data
$defines = array(
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v3.2',
    'presistent_data_handler' => 'session',
    'facebook_redirect_uri' => "https://".$_SERVER['SERVER_NAME']."/igapi/main.php",
    'endpoint_base' => 'https://graph.facebook.com/v14.0/'
);

// set all the credintials in the class
$creds = $igapi->set_creds($defines);

// getting the access token or getting the url
// at this point we need to use the url
// accessToken methos takes 2 params, permesstions, and longlived (which is bool value to get a longlived accestoken ot shortlived)
$reqire_accessToken = $igapi->accessToken(['public_profile', 'instagram_basic', 'pages_show_list', 'instagram_manage_insights', 'pages_read_engagement'], true);

// here we create a session with the data that will be user after that,
// and it is used with the token
// here we chech if the access token has been returned or not
if ($reqire_accessToken == true) {
    $accessToken = $igapi->get_accessToken();
    $_SESSION['data'] = array(
        'token' => $accessToken,
        'igapi_creds' => $igapi->get_creds()
    );
    header('Location: get_information.php');
}