<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apiKey = 'AIzaSyCFHWFqS7so7BKfoeECJvb6KfW-FusXqmY';
$departure_place = isset($_GET['departure_place']) ? $_GET['departure_place'] : '';
$arrival_place = isset($_GET['arrival_place']) ? $_GET['arrival_place'] : '';
$url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=" . urlencode($departure_place) . "&destinations=" . urlencode($arrival_place) . "&key=" . $apiKey;
$response = file_get_contents($url);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to fetch data']);
} else {
    echo $response;
}
