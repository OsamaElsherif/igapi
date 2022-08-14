<?php
// 
// This is the respondible file for making the requests ti the endpoints of the API
// this file is very important for the whole script to work so be carefill while editing on it
// if you will make any script that need to make requests you habe to include this file in it.
// 

abstract class Basic_APICall {
    public string $endpoint;
    private ?array $params;
    public function __construct(){}
    public function make_call() :?array {}
    private function create_url() :string {}
    public function get_params() :array {}
}

class APICall extends Basic_APICall {
    public function __construct(string $endpoint, ?array $params) {
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    public function make_call() :?array {
        $url = ($this->params) ? $this->create_url($this->endpoint, $this->params) : $this->endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode( curl_exec($ch), true );
        curl_close($ch);

        if (isset( $response['data'][0]['access_token'] )) {
            unset( $response['data'][0]['access_token'] );
        }


        return $response;
    }

    public function get_params() :array {
        return $this->params;
    }

    private function create_url($endpoint, $params) :string {
        return $endpoint .= '?'.http_build_query($params);
    }
}

class DownloadAPICall {
    public string $path;

    public function __construct($path) {
        $this->path = $path;
    }

    public function make_call($url) :array {


        $ch = curl_init($url);
        $fp = fopen($this->path, 'wb');
        
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        
        curl_exec($ch);
        curl_close($ch);
        
        fclose($fp);

        return array(
            'STATUS' => true,
            'MSG' => 'file downloaded',
            'url' => $url,
            'path' => $this->path
        );
    }
}