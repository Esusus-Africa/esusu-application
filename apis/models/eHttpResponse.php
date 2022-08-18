<?php
class eHttpResponse {

    public function badRequest($message) {

        http_response_code(400);

        echo json_encode([

            "resposeCode"  => "400",

            "message" => $message

        ]);

    }

    public function duplicateEntry($message) {

        http_response_code(207);

        echo json_encode([

            "resposeCode"  => "207",

            "message" => $message

        ]);

    }

    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([

            "resposeCode"  => "401",

            "message" => $message

        ]);

    }

    public function notFound($message) {

        http_response_code(404);

        echo json_encode([

            "resposeCode"  => "404",

            "message" => $message

        ]);

    }

    public function insufficientFund($message) {

        http_response_code(402);

        echo json_encode([

            "resposeCode"  => "402",

            "message" => $message

        ]);

    }

    public function customOK($statusCode, $responseCode, $responseMessage) {

        http_response_code($statusCode);

        echo json_encode([

            "resposeCode"  => "$statusCode",
            
            "message" => $responseMessage

        ]);

    }

    public function OK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode($resultsData);

    }

}
?>