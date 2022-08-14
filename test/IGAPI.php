<?php
// 
// this file is the main class for the api that holds all the main action
// for the API to work, all the other classes are living in the utils folder
// the other classes will be used more, cause this class is just needed for
// ------------LOGIN, GET_TOKEM, AND SET_CREDS-----------------------------
//  other wise this file doesn't have anyother things to do
// 

require_once __DIR__.'/php-graph-sdk/src/Facebook/autoload.php';
require_once __DIR__.'/utils/helpers/Token.php';
require_once __DIR__.'/utils/helpers/Creadintials.php';

class IGAPI {

    private creadintials $creadintials;
    private Token $token;

    public function set_creds($defines) :void {
        $creds = array();

        foreach ($defines as $key => $value) {
            $creds[$key] = $value;
        }
        
        $this->creadintials = new creadintials($creds);
    }

    public function get_creds() :array {
        return $this->creadintials->get_creds();
    }

    // authenitication of the code

    public function accessToken($permessions, bool $longLived = false) {

        $facebook_creds = $this->creadintials->facebook_creadintials();
        $facebook = new Facebook\Facebook($facebook_creds);
        
        $helper = $facebook->getRedirectLoginHelper();
        $oAuth2Client = $facebook->getOAuth2Client();
        
        if (isset($_GET['code'])) {
            $this->require_accessToken($helper, $oAuth2Client, $longLived);
            return true;
        } else {
            $creds = $this->creadintials->get_creds();
            $loginUrl = $helper->getLoginUrl( $creds['facebook_redirect_uri'], $permessions );
            echo $loginUrl;
            return false;
        }
    }

    public function get_accessToken() {
        return $this->token->get_accessToken();
    }

    private function require_accessToken($helper, $oAuth2Client, bool $longLived = false) :void {
        try {
            $accessToken = $helper->getAccessToken();
            $this->token = new Token($accessToken, $oAuth2Client, $longLived);
        } catch ( Facebook\Exceptions\FacebookResponseException $e ) {
            echo "Graph API returned an error ". $e;
        } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
            echo "Facebook SDK returned an error ". $e;
        }
    }
}