<?php
session_start();
require 'connection.php'; // Connexion à la base de données
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php";




if (!isset($_SESSION['temp_user_login'])) {
    header("Location: login.php");
    exit();
}

$login = $_SESSION['temp_user_login'];

// Générer un code 2FA à 6 chiffres
$code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiresAt = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Mettre à jour la base de données avec le code et son expiration
$stmt = $conn->prepare("UPDATE users SET two_factor_code = ?, two_factor_expires_at = ? WHERE username = ?");
$stmt->execute([$code, $expiresAt, $login]);

// Récupérer l'email de l'utilisateur
$stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt->execute([$login]);
$user = $stmt->fetch();
$email = $user['email'];

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Erreur : L'email associé à ce compte n'est pas valide.";
    exit();
}

// Envoyer le code par email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ourkitchency@gmail.com'; // Remplacez par votre email
    $mail->Password = 'llxpspneydlofsxe'; // Mot de passe d'application Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('no-reply@ourkitchen.com', 'Our kitchen');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Your verification code";
    $mail->Body = "Your verification code is : <b>$code</b>. Ce code expire dans 10 minutes.";

    $mail->send();

    // Rediriger vers la page de vérification 2FA
    header("Location: verify_2fa.php");
    exit();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
    exit();
}
