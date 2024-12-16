<?php
require "connection.php";
$title = "Sign UP";
require "./include/headerbis.inc.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Vérification du CAPTCHA
    $recaptchaSecret = '6LeXT4UqAAAAAGRDSZfCElIjZuEhFusOaHFRvQqB'; // Remplacez ceci par votre clé secrète
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    // Si le CAPTCHA est invalide
    if (intval($responseKeys['success']) !== 1) {
        $error = "Confirm you're not a robot.";
    } elseif (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all the gaps.";
    } elseif (!$email) {
        $error = "Invalid mail adress .";
    } else {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username already in use.";
        } else {
            // Vérifier si l'email existe déjà
            $emailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $emailStmt->execute([$email]);

            if ($emailStmt->rowCount() > 0) {
                $error = "Email already in use.";
            } else {
                // Insérer le nouvel utilisateur avec un token de confirmation
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $confirmationToken = bin2hex(random_bytes(16));

                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, confirmed, confirmation_token) VALUES (?, ?, ?, ?, ?, 0, ?)");

                if ($stmt->execute([$firstName, $lastName, $username, $email, $passwordHash, $confirmationToken])) {
                    // Envoi de l'email de confirmation
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'ourkitchency@gmail.com';
                        $mail->Password = 'llxpspneydlofsxe';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('ourkitchency@gmail.com', 'ourkitchen');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Inscription confirmationn';
                        $mail->Body = 'Thank you for your inscription. <br><br>
                            Cliquez <a href="http://ourkitchen.alwaysdata.net/confirm.php?token=' . $confirmationToken . '">ici</a> pour confirmer votre inscription.';
                        
                        $mail->send();
                        echo 'Email sent to your adress.';
                    } catch (Exception $e) {
                        echo "Mailer error: {$mail->ErrorInfo}";
                    }
                } else {
                    $error = 'Error.';
                }
            }
        }
    }
}
?>

<div class="container">
    <div class="form_area">
        <p class="title">SIGN UP</p>
        <form method="POST" action="signup.php">
            <div class="form_group">
                <label class="sub_title" for="first_name">First name</label>
                <input placeholder="Enter your first name" name="first_name" class="form_style" type="text" required>
            </div>
            <div class="form_group">
                <label class="sub_title" for="last_name">Last name</label>
                <input placeholder="Enter your last name" name="last_name" class="form_style" type="text" required>
            </div>
            <div class="form_group">
                <label class="sub_title" for="username">Username</label>
                <input placeholder="Enter a username" name="username" class="form_style" type="text" required>
            </div>
            <div class="form_group">
                <label class="sub_title" for="email">Email</label>
                <input placeholder="Enter your email" name="email" class="form_style" type="email" required>
            </div>
            <div class="form_group">
                <label class="sub_title" for="password">Password</label>
                <input placeholder="Enter your password" name="password" class="form_style" type="password" required>
            </div>

            <!-- Section CAPTCHA -->
            <div class="form_group">
                <label class="sub_title">Verify you are human</label>
                <div class="g-recaptcha" data-sitekey="6LeXT4UqAAAAAIYgycgcgFZx7BiFrKll8QRGxca5"></div>
            </div>

            <div>
                <button type="submit" class="btn">SIGN UP</button>
                <p>Already Have an Account? <a class="link" href="login.php">Login Here!</a></p>
            </div>
        </form>
    </div>
</div>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<!-- Ajoutez le script de Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php require "./include/footer.inc.php"; ?>
