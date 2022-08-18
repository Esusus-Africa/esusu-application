<?php
class sBHttpResponse {

    public function duplicateRecord($message) {

        http_response_code(403);
        
        echo json_encode([
                    
            "status" => "01",
                    
            "status_desc" => $message

        ]);

    }

    public function badRequest($message,$statusCode) {

        http_response_code(400);

        echo json_encode([
                    
            "status" => $statusCode,
                    
            "status_desc" => $message

        ]);

    }

    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([
                    
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