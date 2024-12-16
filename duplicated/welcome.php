<?php
// Inclure la connexion à la base de données
require '../connection.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: ../login.php");
    exit();
}



// Récupérer le nom de l'utilisateur depuis la session
$user_name = $_SESSION['username'] ?? ''; // Récupérer le nom d'utilisateur de la session




?>

<?php
$title = "Welcome";
require "../include/mainheaderprv.php";



// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    header("Location: ../login.php");
    exit;
}

?>

<div class="container1">
    <h2>Welcome to Recipe Heaven!</h2>
    <p>We're glad to have you here. Explore a world of delicious recipes tailored just for you. Whether you're looking
        for quick weeknight dinners, healthy meal prep ideas, or indulgent desserts, you'll find everything you need.
    </p>
    <p>Feel free to browse through our collection and discover new culinary delights. Don't forget to save your favorite
        recipes and share them with friends!
     </p>
</div>

<?php
require "../include/footerprv.php";
?>
