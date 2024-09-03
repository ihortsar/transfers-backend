<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require 'classes/Database.php';
require 'classes/User.php';

$db = new Database();
$conn = $db->getConnection();

function get_users($conn)

{
    $sql = "SELECT * FROM users";
    $results = $conn->query($sql);

    if ($results === false) {
        $conn->errorInfo();
    } else {
        $users = $results->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    }
}



$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === 'GET') {
    get_users($conn);
} elseif ($request_method === 'POST') {
    User::create($conn);
}
