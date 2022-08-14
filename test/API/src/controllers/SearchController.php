<?php

class SearchController {

    public function searchRequest(string $username) :void {
        $users_db = json_decode(file_get_contents('http://127.0.0.1/igapi/users.json'), true);

        if (isset($users_db[$username])) {
            echo json_encode(
                [
                    "user_information" => $users_db[$username],
                    "user_dir_path" => "/users/".$users_db[$username]['id']."/",
                    "stories" => $this->searchStoryRequest("users/".$users_db[$username]['id']."/")
                ]
            );
        } else {
            echo json_encode(['msg' => "there is no user with that username"]);
        }
    }

    private function searchStoryRequest(string $path) :array {
        $base_dir = '../';
        $dir = $base_dir.$path;
        $files = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    array_push($files, $path.$entry);
                }
            }
            closedir($handle);
        }
        return $files;
    }

}