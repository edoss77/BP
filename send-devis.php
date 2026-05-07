<?php

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {

    echo json_encode([
        "success" => false,
        "message" => "Aucune donnée"
    ]);

    exit;
}

$prenom  = $data['prenom'] ?? '';
$nom     = $data['nom'] ?? '';
$email   = $data['email'] ?? '';
$societe = $data['societe'] ?? '';
$devis   = $data['devis'] ?? '';
$total   = $data['total'] ?? '';

$html = "
<h2>Nouveau devis myvioo</h2>

<p><strong>Client :</strong> {$prenom} {$nom}</p>
<p><strong>Société :</strong> {$societe}</p>
<p><strong>Email :</strong> {$email}</p>

<hr>

{$devis}

<hr>

<h2>Total : {$total}</h2>
";

try {

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host       = 'ssl0.ovh.net';
    $mail->SMTPAuth   = true;

    $mail->Username   = 'noreply@myvioo.io';
    $mail->Password   = 'Millesima1234';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom('contact@myvioo.events', 'myvioo');

    // réception admin
    $mail->addAddress('contact@myvioo.events');

    // copie client
    if (!empty($email)) {
        $mail->addAddress($email);
    }

    $mail->isHTML(true);

    $mail->Subject = 'Votre devis myvioo';

    $mail->Body = $html;

    $mail->send();

    echo json_encode([
        "success" => true
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $mail->ErrorInfo
    ]);
}
