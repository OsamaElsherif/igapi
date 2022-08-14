<?php

class AccountController {
    private Account $account;
    private String $endboint_base = 'https://graph.facebook.com/v14.0/';

    public function __construct(string $token) {
        if ($token == null) {
            http_response_code(300);
            exit;
        }
        $this->account = new Account($token, $this->endboint_base);
    }

    public function processRequest(string $method, ?string $endboint, ?array $params) :void {
        $username = ( isset($params['username']) ) ? $params['username'] : null;
        $fields = ( isset($params['fields']) ) ? $params['fields'] : null;

        switch ($endboint) {
            case 'me':
            case $username :
                $fields = ($fields == null) ? 'username' : $fields;
                if ($username) {
                    $this->proccessSearchRequest($username, $fields);
                } else {
                    $this->proccessUserRequest($fields);
                }
                break;

            case 'stories':
                $this->processStoriesRequest();
                break;
            
            default:
                http_response_code(404);
                echo json_encode( [ 'msg' => 'Not valid endpoint' ] );
                exit;
                break;
        }

    }
    
    private function processStoriesRequest() :void {
        echo json_encode( $this->account->stories() );
    }

    private function proccessUserRequest(string $fields) :void {
        echo json_encode($this->account->information($fields));
    }
    
    private function proccessSearchRequest(string $username, string $fields) :void {
        $result = $this->account->search($username, $fields);
        if ( isset( $result['error'] ) ) {
            echo json_encode($result);
        } else {
            echo json_encode($result['business_discovery']);
        }
    }
}