<?php
header('Content-Type: application/json');

require 'classes/Driver.php';
require 'classes/Database.php';

$db = new Database();
$conn = $db->getConnection();


Driver::fetch_drivers($conn);
