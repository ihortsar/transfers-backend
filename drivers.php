<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require 'classes/Driver.php';
require 'classes/Database.php';

$db = new Database();
$conn = $db->getConnection();


Driver::fetch_drivers($conn);
