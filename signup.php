<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:4200'); 
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type'); 
require 'classes/User.php';
require 'classes/Database.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    User::create($conn);
}
