<?php

class Driver
{

    public $name;
    public $city;
    public $phone_number;


    public static function fetch_drivers($conn)
    {
        $stmt = $conn->prepare('SELECT * FROM drivers');
        $stmt->execute();
        $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'status' => 'success',
            'data' => $drivers
        ]);
    }
}
