<?php
// 
// this file is containg the media class, that haneles media data from the facebook graph api
// there Media calss is the main class, and anyother media types must be inheretened from it,
// feel free ti create your own types but don't make any chanhes in the main class.
// 
// ----------------------- !!! This file need the APICall file !!! --------------------------
// 
//  *** include it in your own file ***
//  *** you will need it anyway to make requests ***
// 

class Media {
    protected string $media_id;
    protected string $endpoint_base;
    protected string $token;

    public function __construct($endpoint_base, $token, $media_id) {
        $this->endpoint_base = $endpoint_base;
        $this->token = $token;
        $this->media_id = $media_id;
    }

    public function get_media($fields) :array {
        $endpoint = $this->endpoint_base . $this->media_id;
        $params = array(
            'fields' => "$fields",
            'access_token' => $this->token
        );

        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response;
    }
}

class Story extends Media {
    public string $media_url;
    public string $media_type;
    public string $media_product_type;
    public $thumbnail_url;

    public function get_story() :Story {
        $media = $this->get_media('media_product_type,media_type,media_url,thumbnail_url');
        
        $this->media_url = $media['media_url'];
        $this->media_type = $media['media_type'];
        $this->media_product_type = $media['media_product_type'];
        if ($this->media_type == 'VIDEO') {
            $this->thumbnail_url = $media['thumbnail_url'];
        }

        return $this;
    }

    public function save($path) :array {
        if ( !file_exists( $path.'/'.$this->media_id.'.jpg' ) && !file_exists( $path.'/'.$this->media_id.'.mp4' ) ) {
            if ( $this->media_type == 'IMAGE' ) {
                $Download = new DownloadAPICall($path.'/'.$this->media_id.'.jpg');
                $response = $Download->make_call($this->media_url);
            } else if ( $this->media_type == 'VIDEO' ) {
                $Download = new DownloadAPICall($path.'/'.$this->media_id.'.mp4');
                $response = $Download->make_call($this->media_url);
            }

            return $response;
        } else {
            return array(
                'STATUS' => false,
                'MSG' => 'file exists',
                'path' => $path.'/'.$this->media_id
            );
        }
    }

    public function insights(string $metric) :array {
        $endpoint = $this->endpoint_base . $this->media_id .'/insights';
        $params = array(
            'metric' => $metric,
            'access_token' => $this->token
        );

        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response;
    }
}