<?php

class LoginController {

    public function requestUrl() :void {
        $url = 'https://'.$_SERVER['SERVER_NAME'].'/igapi/main.php';

        echo json_encode([
            'login_url' => $url
        ]);
    }

}