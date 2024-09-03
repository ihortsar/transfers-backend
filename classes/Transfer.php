<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';


class Transfer
{
    public $route;
    public $date;
    public $pick_up_time;
    public $number_of_passengers;
    public $fare;
    public $vehicle_class;
    public $customer_information;
    public $created_by;


    public static function create($conn)
    {

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data) {
            try {
                $stmt = $conn->prepare(
                    'INSERT INTO 
                  transfers ( route, date, pick_up_time, number_of_passengers, fare, vehicle_class, customer_information,created_by, drivers_id) 
                VALUES
                  (:route, :date, :pick_up_time, :number_of_passengers, :fare, :vehicle_class,:customer_information,:created_by,:drivers_id)'
                );

                $stmt->bindParam(':route',  $data['route']);
                $stmt->bindParam(':date', $data['date']);
                $stmt->bindParam(':pick_up_time', $data['pick_up_time']);
                $stmt->bindParam(':number_of_passengers', $data['number_of_passengers']);
                $stmt->bindParam(':fare', $data['fare']);
                $stmt->bindParam(':vehicle_class', $data['vehicle_class']);
                $stmt->bindParam(':customer_information', $data['customer_information']);
                $stmt->bindParam(':created_by', $data['created_by']);
                $stmt->bindParam(':drivers_id', $data['drivers_id']);
                if ($stmt->execute()) {

                    self::fetchAndSetEmailForConfirmation($data, $conn);

                    echo json_encode(['status' => 'success', 'message' => 'Transfer created successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to create transfer.']);
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Database error.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        }
    }


    public static function fetchAndSetEmailForConfirmation($data, $conn)
    {
        $userId = $data['created_by'];
        $emailStmt = $conn->prepare('SELECT email FROM users WHERE id = :user_id');
        $emailStmt->bindParam(':user_id', $userId);
        $emailStmt->execute();
        $user = $emailStmt->fetch(PDO::FETCH_ASSOC);
        if ($user && isset($user['email'])) {
            self::sendConfirmationEmail($data, $user['email']);
        }
    }



    public static function sendConfirmationEmail($data, $email)
    {
        $mail = new PHPMailer(true);
        try {

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ihortsarkov@gmail.com';
            $mail->Password   = 'elim zzpb lsby wfyj';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom('ihortsarkov@gmail.com', 'World Transfer');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Ihre Transferbestätigung';
            $mail->Body    = '
            <h1>Transfer Bestätigung</h1>
            <p>Damit bestätigen wir Ihren Transfer:</p>
            <p>Route: ' . htmlspecialchars($data['route']) . '</p>
            <p>Datum: ' . htmlspecialchars($data['date']) . '</p>
            <p>Abholzeit: ' . htmlspecialchars($data['pick_up_time']) . '</p>
            <p>Anzahl der Passagiere: ' . htmlspecialchars($data['number_of_passengers']) . '</p>
            <p>Fahrpreis: ' . htmlspecialchars($data['fare']) . '</p>
            <p>Fahrzeugklasse: ' . htmlspecialchars($data['vehicle_class']) . '</p>';

            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            echo json_encode(['status' => 'fail', 'message' => 'Failed to send email.']);
        }
    }




    public static function get_users_transfers($conn)
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $user_id = $data['user_id'];

        if ($user_id) {
            try {
                $stmt = $conn->prepare('SELECT transfers.*, users.name AS customer_name FROM transfers INNER JOIN 
                users ON transfers.customer_information = users.id WHERE transfers.created_by =:user_id');
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([
                    'status' => 'success',
                    'transfers' => $transfers
                ]);
            } catch (PDOException $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to retrieve user transfers.'
                ]);
            }
        }
    }
}
