<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $transferDetails = $input;



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ihortsarkov@gmail.com';
    $mail->Password   = 'elim zzpb lsby wfyj';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('ihortsarkov@gmail.com', 'World Transfer');
    $mail->addAddress('igortsarkov1988@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Ihre Transferbestätigung';
    $mail->Body    = '
            <h1>Transfer Bestätigung</h1>
            <p>Damit bestätigen wir Ihren Transfer:</p>
            <p>Route: ' . htmlspecialchars($transferDetails['route']) . '</p>
            <p>Datum: ' . htmlspecialchars($transferDetails['date']) . '</p>
            <p>Abholzeit: ' . htmlspecialchars($transferDetails['pick_up_time']) . '</p>
            <p>Anzahl der Passagiere: ' . htmlspecialchars($transferDetails['number_of_passengers']) . '</p>
            <p>Fahrpreis: ' . htmlspecialchars($transferDetails['fare']) . '</p>
            <p>Fahrzeugklasse: ' . htmlspecialchars($transferDetails['vehicle_class']) . '</p>';

    $mail->send();
    echo 'Transfer confirmation email sent successfully.';
}
