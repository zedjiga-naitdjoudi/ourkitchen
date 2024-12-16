<?php
require "connection.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE confirmation_token = ?");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        // Update user to mark as confirmed and remove token
        $updateStmt = $conn->prepare("UPDATE users SET confirmed = 1, confirmation_token = NULL WHERE confirmation_token = ?");
        $updateStmt->execute([$token]);
        
        // Redirect to login page after confirmation
        echo "Your account has been successfully confirmed. You will be redirected to the login page...";
        header("Refresh: 3; URL=login.php");
        exit();
    } else {
        echo "Invalid or expired confirmation link.";
    }
} else {
    echo "No confirmation token found.";
}
?>
