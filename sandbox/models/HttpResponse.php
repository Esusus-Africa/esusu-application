<?php

class HttpResponse {

    public function badRequest($message) {

        http_response_code(400);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "Invalid Paramater",

            "message" => $message,

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


    public function accessForbidden($message) {

        http_response_code(403);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "Access Forbidden",

            "message" => $message,

        ]);

    }


    public function insufficientFund($message) {

        http_response_code(402);

        echo json_encode([

            "date"  =>  date("d/m/Y h:i:s:a"),

            "status" => false,

            "error_type" => "Insufficient Fund",

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


    public function OK($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "date" => date("d/m/Y h:i:s:a"),

            "status" => true,

            "data" => $resultsData,

        ]);

    }


    public function OKCust($resultsInfo, $resultsData) {

        http_response_code(200);

        echo json_encode([

            "date" => date("d/m/Y h:i:s:a"),

            "status" => true,

            "data" => $resultsData,

            "billing_details" => [

                "cname" => ($resultsInfo['status'] === "Activated") ? $resultsInfo['cname'] : "",

                "currency" => ($resultsInfo['status'] === "Activated") ? $resultsInfo['currency'] : "",

                "cust_mfee" => ($resultsInfo['status'] === "Activated") ? $resultsInfo['cust_mfee'] : "",

                "charges_type" => ($resultsInfo['status'] === "Activated") ? $resultsInfo['tcharges_type'] : "",

            ],

        ]);

    }

}
?>