<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Story;
use App\Models\Report;
use App\Models\Insight;

use App\Libraries\APICall;
use App\Libraries\sStory;

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

session_start();

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create', function(Request $request) {
    $request = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password
    ];
    if(!empty($user[0])) {
        return "user exists";
    } else {
        User::create($request);
    }
    return redirect('/login');
});

Route::post('/login', function(Request $request) {
    $email = $request->email;
    $password = $request->password;

    $user = User::where('email', $email)->get();
    if (!empty($user[0])) {
        if ($user[0]->password == $password) {
                    
            if (isset($_SESSION['user'])) {
                session_destroy();
            }
            $_SESSION['user'] = [ 'email' => $email, 'password' => $password ];
            $user = User::where('email', $request->email)->get();

            $username = ($user[0]->username != '') ? $user[0]->username : $user[0]->name ;
            return redirect("/me/");
        } else {
            return "password is incorrect";
        }
    } else {
        return "user does not exist";
    }
});

Route::post('/connect', function(Request $request) {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $user_db = User::where('email', $email);
        $user = $user_db->get();
        $facebook_user_id = $user[0]->facebook_user_id;
        if($facebook_user_id !== null) {
            if ($user[0]->access_token == $request->access_token) {
                echo "Access Token is already exists";
                return redirect("/me/");
            }
            $user_db->update(['access_token' => $request->access_token, 'signed_request' => $request->signed_request, 'experies_in' => $request->experies_in, 'data_access_expiration_time' => $request->data_access_expiration_time]);
            echo "data had been updated successfully for $facebook_user_id";
            return redirect("/me/");
        } else {
            $user_db->update(['access_token' => $request->access_token, 'signed_request' => $request->signed_request, 'experies_in' => $request->experies_in, 'data_access_expiration_time' => $request->data_access_expiration_time, 'facebook_user_id' => $request->facebook_user_id]);
            echo "data had been updated successfully";
            return redirect("/me/");
        }
    } else {
        echo "you need to be loged in to connect your facebook account";
        return redirect("/login");
    }
});

Route::get('/connect/ig', function() {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $user_db = User::where('email', $email);
        $ig_user_id = $user_db->get(['ig_user_id']);
        if ($ig_user_id[0]['ig_user_id'] !== null) {
            echo "your IG is already connected";
            return redirect("/me/");
        } else {

            $access_token = $user_db->get('access_token')[0]['access_token'];
            $baseUrl = "https://graph.facebook.com/v14.0/";
            $facebook_user_id = $user_db->get(['facebook_user_id']);
            $url = $baseUrl . $facebook_user_id[0]['facebook_user_id'] . '/accounts';

            $params = array(
                'access_token' => $access_token
            );
            $APICall = new APICall($url, $params);
            $response = $APICall->make_call();
            
            $id = $response['data'][0]['id'];
            $url = $baseUrl . $id;
            $params = array(
                'fields' => 'instagram_business_account',
                'access_token' => $access_token
            );
            $APICall = new APICall($url, $params);
            $response = $APICall->make_call();
            $ig_id = $response['instagram_business_account']['id'];
            
            $url = $baseUrl . $ig_id;
            $params = array(
                'fields' => 'username',
                'access_token' => $access_token
            );
            $APICall = new APICall($url, $params);
            $response = $APICall->make_call();            
            $user_db->update(['ig_user_id' => $ig_id, 'username' => $response['username']]);

            if ( !file_exists(__DIR__."\users\\".$ig_id ) ) {
                mkdir( __DIR__."\users\\".$ig_id , 0777, true );
            }
            
            return $response;
        }
    } else {
        return "You need to be loged in to get the access token";
    }
});

Route::get('/access_token', function() {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['user']['email'];
        $user_db = User::where('email', $email);
        return $user_db->get(['access_token']);
    } else {
        echo "You need to be loged in to get the access token";
        return redirect("/login");
    }
});

Route::get('/information', function(Request $request) {
    if($request->username !== null) {
        $username = $request->username;
        $user = User::where('username', $username)->get(['email', 'name', 'username', 'facebook_user_id', 'ig_user_id']);
        if(empty($user[0])) {
            return array("Error" => "User with this username doesn't exist");
        } else {
            return $user;
        }
    } else {
        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            $user_db = User::where('email', $email);
            return $user_db->get(['email', 'name', 'facebook_user_id', 'ig_user_id', 'username']);
        } else {
            echo "You need to be loged in to get the access token";
            return redirect("/login");
        }
    }
});

Route::get('/stories', function(Request $request) {
    if ($request->username == 'current') {
        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            $user_db = User::where('email', $email);
            $ig_user_id = $user_db->get(['ig_user_id'])[0]['ig_user_id'];

            if ($request->stories == 'new') {

                $access_token = $user_db->get(['access_token'])[0]['access_token'];
                
                $baseUrl = "https://graph.facebook.com/v14.0/";
                $url = $baseUrl . $ig_user_id . '/stories';
                $params = array(
                    'access_token' => $access_token
                );
                $APICall = new APICall($url, $params);
                $response = $APICall->make_call();
                
                $stories = array();
                $feedback = array();

                if (isset($response['data'])) {

                    foreach ($response['data'] as $key => $value) {

                        $story = $response['data'][$key];
                        $story = new sStory($baseUrl, $access_token, $story['id']);
                        $story = $story->get_story();
                        $stories[$response['data'][$key]['id']] = $story;
                        $report = $story->save(__DIR__."\users\\$ig_user_id");
                        $insights = $story->insights('impressions, reach');
                        $feedback[$key] = array(
                            'feedback' => array(
                                'story' => $story,
                                'insights' => $insights,
                                'report' => $report
                            )
                        );

                        $story_id = $response['data'][$key]['id'];
                        $reach = 0;
                        $impressions = 0;

                        if (!isset($insights['error'])) {
                            foreach ($insights['data'] as $key => $value) {
                                if ($insights['data'][$key]['name'] == 'reach') {
                                    $reach = $insights['data'][$key]['values'][0]['value'];
                                }
                                if ($insights['data'][$key]['name'] == 'impressions') {
                                    $impressions = $insights['data'][$key]['values'][0]['value'];
                                }
                            }
                        }

                        
                        $story_exists = Story::where('story_id', $story_id)->get();

                        if(empty($story_exists[0])) {
                            Insight::create(['story_id' => $story_id, 'reach' => $reach, 'impressions' => $impressions]);
                            $insight_id = Insight::where('story_id', $story_id)->get(['id'])[0]['id'];
                            
                            Report::create(['story_id' => $story_id, 'path' => "$ig_user_id/$story_id"]);
                            $report_id = Report::where('story_id', $story_id)->get(['id'])[0]['id'];

                            $thumbnail_url = ($story->thumbnail_url !== null) ? $story->thumbnail_url : "NaN";
                        
                            Story::create([
                                'story_id' => $story_id,
                                'media_url' => $story->media_url,
                                'media_type' => $story->media_type,
                                'media_product_type' => $story->media_product_type,
                                'thumbnail_url' => $thumbnail_url,
                                'user_id' => $ig_user_id,
                                'report_id' => $report_id,
                                'insight_id' => $insight_id
                            ]);
                        } else {
                            Insight::where('story_id', $story_id)->update(['reach' => $reach, 'impressions' => $impressions]);
                        }

                    }
            
                    return $feedback;
                } else {
                    return $response;
                }

            } elseif ($request->stories == 'all') {
                $feedback = array();
                
                $stories_db = Story::where('user_id', (int)$ig_user_id);
                $stories = Story::where('user_id', (int)$ig_user_id)->get(['media_url', 'media_type', 'thumbnail_url', 'media_product_type','report_id', 'insight_id']);
                
                foreach ($stories as $key => $value) {
                    $feedback[$key] = array(
                        'feedback' => array(
                            'story' => $value,
                            'insights' => Insight::find($value['insight_id']),
                            'report' => Report::find($value['report_id'])
                        )
                    );
                }

                return $feedback;
            } else {
                return "invalid parameter for stories argument";
            }
        } else {
            echo "You need to be loged in to get the access token";
            return redirect("/login");
        }
    } else {
        $ig_user_id = User::where('username', $request->username)->get('ig_user_id')[0]['ig_user_id'];
        if ($request->stories == 'new') {
            
            $access_token = User::where('username', $request->username)->get('access_token')[0]['access_token'];
                
            $baseUrl = "https://graph.facebook.com/v14.0/";
            $url = $baseUrl . $ig_user_id . '/stories';
            $params = array(
                'access_token' => $access_token
            );
            $APICall = new APICall($url, $params);
            $response = $APICall->make_call();
            
            $stories = array();
            $feedback = array();

            if (isset($response['data'])) {

                foreach ($response['data'] as $key => $value) {

                    $story = $response['data'][$key];
                    $story = new sStory($baseUrl, $access_token, $story['id']);
                    $story = $story->get_story();
                    $stories[$response['data'][$key]['id']] = $story;
                    $insights = $story->insights('impressions, reach');
                    $feedback[$key] = array(
                        'feedback' => array(
                            'story' => $story,
                            'insights' => $insights
                        )
                    );
                }
                
                return $feedback;
            } else {
                return $response;
            }

        } elseif ($request->stories == 'all') {
            $feedback = array();
            
            $stories_db = Story::where('user_id', (int)$ig_user_id);
            $stories = Story::where('user_id', (int)$ig_user_id)->get(['media_url', 'media_type', 'thumbnail_url', 'media_product_type','report_id', 'insight_id']);
            
            foreach ($stories as $key => $value) {
                $feedback[$key] = array(
                    'feedback' => array(
                        'story' => $value,
                        'insights' => Insight::find($value['insight_id']),
                        'report' => Report::find($value['report_id'])
                    )
                );
            }

            return $feedback;
        }
    }
});