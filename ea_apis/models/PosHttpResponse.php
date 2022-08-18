<?php

class PosHttpResponse {

    public function badRequest($message) {

        http_response_code(200);

        echo json_encode([

            "responsecode" => "999",

            "responsemessage" => $message,

        ]);

    }

    public function accessForbidden($message) {

        http_response_code(400);

        echo json_encode([

            "statuscode"  => "400",

            "responsecode" => "01",

            "responsemessage" => $message,

        ]);

    }

    public function customAccessForbidden($message, $customData) {

        http_response_code(400);

        echo json_encode([

            "statuscode"  => "400",

            "responsecode" => "01",

            "responsemessage" => $message,
            
            "merchantinfo" => $customData

        ]);

    }

    public function insufficientFund($message) {

        http_response_code(402);

        echo json_encode([

            "statuscode"  => "400",

            "responsecode" => "31",

            "responsemessage" => $message,

        ]);

    }

    public function OK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "statuscode" => "200",

            "responsecode" => "00",

            "data" => $resultsData,
            
            "responsemessage" => "success"

        ]);

    }

    public function CustomOK($resultsInfo, $resultsData, $customData) {

        http_response_code(200);

        echo json_encode([

            "statuscode" => "200",

            "responsecode" => "00",

            "data" => $resultsData,
            
            "responsemessage" => "success",

            "merchantinfo" => $customData

        ]);

    }
    
    public function NewOK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo $resultsData;

    }

    public function specialOK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "statuscode" => "200",

            "responsecode" => "00",

            "banklist" => $resultsData,
            
            "responsemessage" => "success"

        ]);

    }
    
    public function tOK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "statuscode" => 200,

            "responsecode" => "00",

            "data" => $resultsData

        ]);

    }


    public function duplicateEntry($message) {

        http_response_code(207);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "Duplicate Entry",

            "message" => $message,

        ]);

    }


    public function notFound($message) {

        http_response_code(404);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "Not Found",

            "message" => $message,

        ]);

    }


    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "unauthorized",

            "message" => $message,

        ]);

    }

}
?>