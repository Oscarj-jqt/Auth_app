<?php

require_once __DIR__ . '/../../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Repository\UserRepository;


session_start();

$config = require(__DIR__ . '/../../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'email';
    $_SESSION['2fa_type'] = $type;

    $usersFile = $config['users_file'];
    $userId = $_SESSION['id'] ?? null;
    if (!$userId) {
        echo "<p>Erreur : identifiant utilisateur manquant dans la session.</p>";
        exit;
    }
    $userRepo = new UserRepository($usersFile);
    $user = $userRepo->findById($userId);
    $user['twofa_type'] = $type;



    if ($type === 'email') {
        // Générer le code
        $code = random_int(100000, 999999);
        $_SESSION['2fa_code'] = $code;
        // Récupérer l'email de l'utilisateur (depuis la session ou la base)
        $email = $_SESSION['user_email'] ?? $user['email'] ?? ($_ENV['MAIL_FROM'] ?? null);
        $_SESSION['user_email'] = $email;
        $user['twofa_secret'] = null;


        if ($email) {
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

    echo "<form method='post' action='/2fa_verify'>
        <label for='code'>Code reçu par email :</label>
        <input type='text' name='code' required>
        <button type='submit'>Vérifier</button>
    </form>";
    } elseif ($type === 'totp') {
        // Générer ou récupérer le secret TOTP
        $secret = $user['twofa_secret'] ?? TOTP::create()->getSecret();
        $user['twofa_secret'] = $secret;
        $userRepo->update($userId, $user); 
        $_SESSION['totp_secret'] = $secret;
        // Générer le QR code
        $totp = TOTP::create($secret);
        $totp->setLabel($user['username'] ?? $user['login'] ?? 'user');
        $totp->setIssuer('MonApp');
        $qrUri = $totp->getProvisioningUri();

        $qrCode = new QrCode($qrUri);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        echo "<h2>Scannez ce QR code avec Google Authenticator</h2>";
        echo '<img src="data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString()) . '" />';
        echo "<form method='post' action='/2fa_verify'>
            <label for='code'>Code généré par l'app :</label>
            <input type='text' name='code' required>
            <button type='submit'>Vérifier</button>
        </form>";
    } else {
        echo "<p>Type de 2FA inconnu.</p>";
    }

}