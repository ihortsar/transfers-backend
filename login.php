<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type'); 
require 'classes/User.php';
require 'classes/Database.php';

$db = new Database();
$conn = $db->getConnection();
$isAuthenticated = false;

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (isset($data['email']) && isset($data['password'])) {
        $isAuthenticated = User::authenticate($conn, $data['email'], $data['password']);
    }

    if (!$isAuthenticated) {
        echo json_encode(
            [
                'status' => 'failure',
                'message' => 'invalid email or password'
            ]
        );
    }
}
