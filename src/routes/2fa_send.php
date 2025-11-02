<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Repository\UserRepository;


session_start();

$config = require(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = 'email';
    $_SESSION['2fa_type'] = $type;

    // Générer le code
    $code = random_int(100000, 999999);
    $_SESSION['2fa_code'] = $code;

    // Récupérer l'email de l'utilisateur (depuis la session ou la base)
    $email = $_SESSION['user_email'] ?? null;
    if (!$email) {
        $usersFile = $config['users_file'];
        $userId = $_SESSION['user_id'];
        $userRepo = new UserRepository($usersFile);
        $user = $userRepo->findById($userId);
        $email = $user['email'] ?? null;
        $_SESSION['user_email'] = $email;
    }

    if ($email) {
        require_once(__DIR__ . '/../../vendor/autoload.php');
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = $config['mail']['from'];
            $mail->Password = $config['mail']['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@tondomaine.com', 'MonApp');
            $mail->addAddress($email);
            $mail->Subject = 'Votre code de vérification 2FA';
            $mail->Body = "Voici votre code de vérification : $code";

            $mail->send();
            echo "<p>Un code vient d'être envoyé à votre adresse email : <strong>$email</strong></p>";
        } catch (Exception $e) {
            echo "<p>Erreur lors de l'envoi du mail : {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>Erreur : email introuvable pour cet utilisateur.</p>";
    }
}

echo "<form method='post' action='/2fa_verify.php'>
    <label for='code'>Code reçu par email :</label>
    <input type='text' name='code' required>
    <button type='submit'>Vérifier</button>
</form>";