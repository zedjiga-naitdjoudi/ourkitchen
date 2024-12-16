<?php
// Inclure la connexion à la base de données
require '../connection.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}



// Récupérer le nom de l'utilisateur depuis la session
$user_name = $_SESSION['username'] ?? ''; // Récupérer le nom d'utilisateur de la session




?>

<?php
$title = "Accueil";
require "../include/headerprv.php";
?>

<div class="card cookie-card" id="cookie-message">
    <svg version="1.1" id="cookieSvg" x="0px" y="0px" viewBox="0 0 122.88 122.25" xml:space="preserve">
        <!-- SVG content here -->
    </svg>
    <p class="cookieHeading">We use cookies.</p>
    <p class="cookieDescription">This website uses cookies to ensure you get the best experience on our site.</p>

    <div class="buttonContainer">
        <button class="acceptButton" id="accept-cookies">Allow</button>
        <button class="declineButton" id="decline-cookies">Decline</button>
    </div>
</div>



<section class="main-content">
    <div class="promo">
        <h2>Enjoy Your <span class="highlight">Special</span> Delicious Meal</h2>
        <p>We make it easy for you to give your guests the same experience online that they expect within your doors.</p>
        <a href="recipies.php" class="btn">Start Now</a>
        <a href="about-us.php" class="btn">Get To Know Us!</a>
    </div>
</section>

<?php
require "../include/footer.inc.php";
?>

<script src="script.js"></script>
