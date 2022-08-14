<?php
// 
// this file contains the Token class which is used for saving the important
// token data, don't touch this file or miss with it code, till you change all the other
// files ... be careful
// 

class Token {
    public bool $isLongLived = false;
    private $accessToken;

    public function __construct($accessToken, $oAuth2Client, bool $longLived = false) {
        $this->accessToken = $accessToken;

        if ($longLived) {
            $this->getLongLived($oAuth2Client);
        }

        return $this->accessToken;
    }

    public function get_accessToken() {
        return (string)$this->accessToken;
    }

    private function getLongLived($oAuth2Client) :void {
        if ( !$this->accessToken->isLongLived() ) {
            try {
                $this->$accessToken = $oAuth2Client->getLongLivedAccessToken( $this->accessToken );
                $this->isLongLived = true;
            } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
                echo "Error getting the long lived access token". $e ;
            }
        } else {
            $this->isLongLived = true;
        }
    }
}