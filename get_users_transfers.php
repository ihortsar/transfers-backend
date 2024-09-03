<?php
header('Content-Type: application/json');
include 'classes/Database.php';
include 'classes/Transfer.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Transfer::get_users_transfers($conn);
}
