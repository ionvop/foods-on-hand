<?php

chdir("../../");
require_once "common.php";
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

try {
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            $data = json_decode(file_get_contents("php://input"), true);

            $response = fetch("https://api.openai.com/v1/chat/completions", [
                "method" => "POST",
                "headers" => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $OPENAI_API_KEY
                ],
                "body" => json_encode([
                    "model" => "gpt-4o-mini",
                    "messages" => $data["messages"]
                ])
            ]);

            echo json_encode($response["json"]);
            exit;
        case "OPTIONS":
            http_response_code(204);
            exit;
        default:
            throw new Exception("Invalid request method.");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}