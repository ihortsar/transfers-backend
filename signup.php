<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type'); 
header("Access-Control-Allow-Origin: *");

require 'classes/User.php';
require 'classes/Database.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    User::create($conn);
}
