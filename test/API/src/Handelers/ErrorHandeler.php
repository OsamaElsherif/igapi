<?php

class ErrorHandeler {

    public static function handelException(Throwable $exception) :void {
        http_response_code(500);
        
        echo json_encode(
            [
                "code" => $exception->getCode(),
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
            ]
        );
    }
    
}