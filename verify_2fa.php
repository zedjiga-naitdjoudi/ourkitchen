<?php
require "connection.php";
$title = "2FA Verification";
require "./include/headerbis1.inc.php";
session_start();

// Vérifier si l'utilisateur est en phase de 2FA
if (!isset($_SESSION['2fa_user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['2fa_user_id'];
    $two_factor_code = $_POST['two_factor_code'];

    $stmt = $conn->prepare("SELECT two_factor_code FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && $user['two_factor_code'] == $two_factor_code) {
        // Réinitialiser le code 2FA
        $reset_stmt = $conn->prepare("UPDATE users SET two_factor_code = NULL WHERE id = ?");
        $reset_stmt->execute([$user_id]);

        // Connecter l'utilisateur
        $_SESSION['user_id'] = $user_id;
        unset($_SESSION['2fa_user_id']);
        header("Location: duplicated/welcome.php");
        exit();
    } else {
        $error = "Code incorrect. Veuillez réessayer.";
    }
}
?>

<div class="container">
    <div class="form_area">
        <p class="title">Two-Factor Authentication</p>
        <form method="POST" action="verify_2fa.php">
            <div class="form_group">
                <label class="sub_title" for="two_factor_code">Enter the code sent to your email</label>
                <input id="two_factor_code" name="two_factor_code" class="form_style" type="text" placeholder="6-digit code" required>
            </div>
            <div>
                <button type="submit" class="btn">Verify</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php require "./include/footer.inc.php"; ?>
