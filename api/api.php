<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["url"]) || !isset($data["method"])) {
    echo json_encode(["error" => "Parametri mancanti"]);
    exit;
}

$url = $data["url"];
$method = strtoupper($data["method"]);
$body = $data["body"] ?? null;

$response = makeApiRequest($method, $url, $body);
echo json_encode($response);

function makeApiRequest($method, $url, $body)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($body && ($method === "POST" || $method === "PUT")) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    return [
        "status" => $httpCode,
        "response" => $response ?: $error
    ];
}
?>
