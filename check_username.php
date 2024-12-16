<?php
require 'connection.php'; // Assurez-vous que ce chemin est correct

if (isset($_POST['username']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Préparer la requête pour vérifier l'existence du nom d'utilisateur et de l'adresse e-mail
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);

    if ($stmt->rowCount() > 0) {
        $messages = [];

        // Vérifier si le nom d'utilisateur est déjà utilisé
        $user = $stmt->fetch();
        if ($user['username'] === $username) {
            $messages[] = "Nom d'utilisateur déjà utilisé.";
        }

        // Vérifier si l'adresse e-mail est déjà utilisée
        if ($user['email'] === $email) {
            $messages[] = "Adresse e-mail déjà utilisée.";
        }

        // Afficher tous les messages d'erreur
        foreach ($messages as $message) {
            echo $message . "<br>";
        }
    } else {
        echo "Nom d'utilisateur et adresse e-mail disponibles.";
    }
} else {
    echo "Nom d'utilisateur ou adresse e-mail non fournis.";
}
?>
