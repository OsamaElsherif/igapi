<?php
// 
// this file contains the credintial class that holds all the needed creadintials for the API
// to work, if you will add any params in the class please go and check the other files tp
// make all the needed changes, otherwise, don't make any changes ... be careful
// 

class creadintials {
    private $app_id;
    private $app_secret;
    private $graph_version;
    private $data_handler;
    private $redirect_uri;
    private $endpoint_base;

    public function __construct($creds) {
        $this->app_id = $creds['app_id'];
        $this->app_secret = $creds['app_secret'];
        $this->graph_version = $creds['default_graph_version'];
        $this->data_handler = $creds['presistent_data_handler'];
        $this->redirect_uri = $creds['facebook_redirect_uri'];
        $this->endpoint_base = $creds['endpoint_base'];
    }

    public function get_creds() :array {
        $app_id = $this->app_id;
        $app_secret = $this->app_secret;
        $graph_version = $this->graph_version;
        $data_handler = $this->data_handler;
        $redirect_uri = $this->redirect_uri;
        $endpoint_base = $this->endpoint_base;

        // echo "CREDINTIALS ( $app_id, $app_secret, $graph_version,
        //                     $data_handler, $redirect_uri, $endpoint_base );";
        
        return array( 'default_graph_version' => $graph_version,
                      'facebook_redirect_uri' => $redirect_uri,
                      'endpoint_base' => $endpoint_base );
    }

    public function facebook_creadintials() :array {
        return array(
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => $this->graph_version,
            'presistent_data_handler' => $this->data_handler
        );
    }
}