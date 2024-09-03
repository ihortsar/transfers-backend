<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
include 'classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

function get_addresses($conn)
{
    $sql = "SELECT * FROM addresses";
    $results = $conn->query($sql);

    if ($results === false) {
        $conn->errorInfo();
    } else {
        $addresses = $results->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $addresses
        ]);
    }
}
get_addresses($conn);
