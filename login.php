<?php
require "connection.php";
$title = "login";
require "./include/headerbis1.inc.php";
session_start();

// Inclure les classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php"; // Pour charger PHPMailer via Composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['confirmed'] == 1) {
            // Générer un code 2FA à 6 chiffres
            // Ajoutez le nom d'utilisateur à la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];  // Ajoutez cette ligne
            
            $two_factor_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            // Mettre à jour la base de données avec le code 2FA et son expiration
            $update_stmt = $conn->prepare("UPDATE users SET two_factor_code = ?, two_factor_expires_at = ? WHERE id = ?");
            $update_stmt->execute([$two_factor_code, $expiresAt, $user['id']]);

            // Récupérer l'email de l'utilisateur
            $email = $user['email'];

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "L'email associé à ce compte n'est pas valide.";
            } else {
                // Envoyer le code 2FA par email
                $mail = new PHPMailer(true);
                try {
                    // Paramétrage de PHPMailer
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
                    $mail->Subject = "2FA login";
                    $mail->Body = "Hello, your 2FA code is : <b>$two_factor_code</b>. It expires in 10 minutes.";

                    // Envoyer l'email
                    $mail->send();

                    // Stocker l'utilisateur dans la session pour la suite
                    $_SESSION['2fa_user_id'] = $user['id'];

                    // Rediriger vers la page de vérification 2FA
                    header("Location: verify_2fa.php");
                    exit();
                } catch (Exception $e) {
                    $error = "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
                }
            }
        } else {
            $error = "Veuillez confirmer votre adresse email pour vous connecter.";
        }
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<div class="container">
    <div class="form_area">
        <p class="title">LOGIN</p>
        <form method="POST" action="login.php">
            <div class="form_group">
                <label class="sub_title" for="username">Username</label>
                <input id="username" placeholder="Enter your username" name="username" class="form_style" type="text"
                    required>
            </div>
            <div class="form_group">
                <label class="sub_title" for="password">Password</label>
                <input id="password" placeholder="Enter your password" name="password" class="form_style"
                    type="password" required>
            </div>
            <div>
                <button type="submit" class="btn">LOGIN</button>
                <p>Don't Have an Account? <a class="link" href="signup.php">Sign Up Here!</a></p>
            </div>
        </form>
    </div>
</div>

<?php if (isset($error))
    echo "<p style='color:red;'>$error</p>"; ?>
<?php require "./include/footer.inc.php"; ?>