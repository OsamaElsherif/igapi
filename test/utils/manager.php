<?php
// 
// This is the account insitance that is responsible for getting the account data, and
// this file must be included in the main file for your progects, and it requires from you
// to start the session, to get the data that is saved from the IGAPI object.
// 


require_once __DIR__.'/APIcall.php';
require_once __DIR__.'/handelers/Media.php';

class Account {
    public string $id;
    public string $ig_id;
    public array $stories;
    private string $endpoint_base;
    private string $token;

    public function __construct(string $token, string $endpoint_base) {
        // $this->get_session();
        $this->token = $token;
        $this->endpoint_base = $endpoint_base;

        $this->id = $this->get_id();
        $this->ig_id = $this->get_ig_id();

        if ( !file_exists( "../users/".$this->ig_id ) ) {
            mkdir( "../users/".$this->ig_id , 0777, true );
        }
    }

    public function search(string $username, string $fields) :array {
        $endpoint = $this->endpoint_base . $this->ig_id;
        $params = array(
            'fields' => "business_discovery.username($username){".$fields."}",
            'access_token' => $this->token
        );

        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response;
    }

    public function information(string $fileds) :array {
        $endpoint = $this->endpoint_base . $this->ig_id;
        $params = array(
            'fields' => $fileds,
            'access_token' => $this->token
        );

        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response;
    }

    public function stories() :array {
        $endpoint = $this->endpoint_base . $this->ig_id . '/stories';
        $params = array(
            'access_token' => $this->token
        );

        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        $feedback = array();

        foreach ($response['data'] as $key => $value) {
            $story = $response['data'][$key];
            $story = new Story($this->endpoint_base, $this->token, $story['id']);
            $story = $story->get_story();
            $this->stories[$response['data'][$key]['id']] = $story;
            $report = $story->save("../users/$this->ig_id");
            $feedback[$key] = array(
                'feedback' => array (
                    'stroy' => $story,
                    'insights' => $story->insights('impressions, reach'),
                    'report' => $report
                )
            );
        }

        return $feedback;
    }

    private function get_ig_id() {
        $endpoint = $this->endpoint_base . $this->id;
        $params = array(
            'fields' => 'instagram_business_account',
            'access_token' => $this->token
        );
        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response['instagram_business_account']['id'];
    }

    private function get_id() {
        $endpoint = $this->endpoint_base . '/me/accounts';
        $params = array(
            'access_token' => $this->token
        );
        $APICall = new APICall($endpoint, $params);
        $response = $APICall->make_call();

        return $response['data'][0]['id'];
    }

    private function get_session() :void {
        $this->endpoint_base = $_SESSION['data']['igapi_creds']['endpoint_base'];
        $this->token = $_SESSION['data']['token'];
    }
}