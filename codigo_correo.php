<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Nivel de depuración
    $mail->isSMTP(); // Usar SMTP
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
    $mail->SMTPAuth = true; // Habilitar autenticación SMTP
    $mail->Username = 'depruebac310@gmail.com'; // Usuario SMTP
    $mail->Password = 'vjyeitfrmywcqgoz'; // Contraseña SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar cifrado TLS
    $mail->Port = 587; // Puerto TCP para TLS

    // Remitente y destinatarios
    $mail->setFrom('johansrodriguezc@juandelcorral.edu.co', 'Johan');
    $mail->addAddress('johanstevenrodriguezcardoso@gmail.com');
    $mail->addCC('johanrodriguez007@hotmail.com');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo';
    $mail->Body = 'Esta es una prueba de <b>correo</b>';

    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    echo 'Mensaje: ' . $mail->ErrorInfo;
}
?>

