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
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!$email) {
        $error = "Adresse email invalide.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // If username exists, show an error message
        if ($stmt->rowCount() > 0) {
            $error = "Nom d'utilisateur déjà utilisé.";
        } else {
            // Check if email already exists
            $emailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $emailStmt->execute([$email]);

            // If email exists, show an error message
            if ($emailStmt->rowCount() > 0) {
                $error = "Cette adresse email est déjà utilisée.";
            } else {
                // Insert new user with a confirmation token
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $confirmationToken = bin2hex(random_bytes(16));
                
                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, confirmed, confirmation_token) VALUES (?, ?, ?, ?, ?, 0, ?)");
                
                if ($stmt->execute([$firstName, $lastName, $username, $email, $passwordHash, $confirmationToken])) {
                    // Send confirmation email
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
                        $mail->Subject = 'Confirmation d\'inscription';
                        $mail->Body = 'Merci de vous être inscrit sur notre site. <br><br>
                            Cliquez <a href="http://ourkitchen.alwaysdata.net/confirm.php?token=' . $confirmationToken . '">ici</a> pour confirmer votre inscription.';
                        
                        $mail->send();
                        echo 'L\'email de confirmation a été envoyé.';
                    } catch (Exception $e) {
                        echo "L'email n'a pas pu être envoyé. Erreur Mailer: {$mail->ErrorInfo}";
                    }
                } else {
                    $error = 'Erreur lors de l\'inscription.';
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
            <div>
                <button type="submit" class="btn">SIGN UP</button>
                <p>Already Have an Account? <a class="link" href="login.php">Login Here!</a></p>
            </div>
        </form>
    </div>
</div>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php require "./include/footer.inc.php"; ?>
