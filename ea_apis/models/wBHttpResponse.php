<?php
class wBHttpResponse {

    public function duplicateRecord($sessionId,$message) {

        http_response_code(403);
        
        echo json_encode([

            "transactionreference" => $sessionId,
                    
            "status" => "01",
                    
            "status_desc" => $message

        ]);

    }

    public function badRequest($sessionId,$message,$statusCode) {

        http_response_code(400);

        echo json_encode([

            "transactionreference" => $sessionId,
                    
            "status" => $statusCode,
                    
            "status_desc" => $message

        ]);

    }

    public function notAuthorized($sessionId,$message) {

        http_response_code(401);

        echo json_encode([

            "transactionreference" => $sessionId,
                    
            "status" => "03",
                    
            "status_desc" => $message

        ]);

    }

    public function OK($resultsData) {

        http_response_code(200);

        echo $resultsData;

    }

}
?>