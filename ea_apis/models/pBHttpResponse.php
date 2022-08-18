<?php
class pBHttpResponse {

    public function systemError($sessionId,$message) {

        http_response_code(500);
        
        echo json_encode([
            
            "requestSuccessful" => true,
            
            "sessionId" => $sessionId, 
            
            "responseMessage" => $message, 
            
            "responseCode" => "03",

        ]);

    }

    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([
            
            "requestSuccessful" => true,
            
            "sessionId" => null, 
            
            "responseMessage" => $message, 
            
            "responseCode" => "02",

        ]);

    }

    public function duplicateRecord($sessionId,$message) {

        http_response_code(403);
        
        echo json_encode([
            
            "requestSuccessful" => true,
            
            "sessionId" => $sessionId, 
            
            "responseMessage" => $message, 
            
            "responseCode" => "01",

        ]);

    }

    public function OK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo $resultsData;

    }

}
?>