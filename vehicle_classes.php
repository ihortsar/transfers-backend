<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

function get_vehicle_classes($conn)
{
    $sql = "SELECT * FROM vehicle_classes";
    $results = $conn->query($sql);

    if ($results === false) {
        $conn->errorInfo();
    } else {
        $vehicle_classes = $results->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $vehicle_classes
        ]);
    }
}
get_vehicle_classes($conn);
