<?php
class gAHttpResponse {

    public function badRequest($message) {

        http_response_code(400);

        echo json_encode([

            "code"  => "400",

            "message" => $message,

        ]);

    }

    public function notAuthorized($message) {

        http_response_code(401);

        echo json_encode([

            "code"  => "401",

            "message" => $message,

        ]);

    }

    public function OK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo $resultsData;

    }

}
?>