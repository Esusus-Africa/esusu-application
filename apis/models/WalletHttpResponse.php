<?php

class WalletHttpResponse {

    public function badRequest($message) {

        http_response_code(400);

        echo json_encode([

            "responsecode" => "999",

            "responsemessage" => $message,

        ]);

    }

    public function accessForbidden($message) {

        http_response_code(200);

        echo json_encode([

            "statusCode"  => "02",

            "responseMessage" => $message,

        ]);

    }


    public function insufficientFund($message) {

        http_response_code(402);

        echo json_encode([

            "statusCode"  => "01",

            "responseMessage" => $message,

        ]);

    }

    

    public function specialOK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "statusCode" => "00",

            "data" => $resultsData,
            
            "responseMessage" => "success"

        ]);

    }


    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([

            "statusCode"  => "99",

            "responseMessage" => $message,

        ]);

    }

}

$wallethttp = new WalletHttpResponse();

?>