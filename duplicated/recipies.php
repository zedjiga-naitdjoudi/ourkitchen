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
$title = "Recipes";
require "../include/mainheaderprv.php";

// Ta clé API Spoonacular
$api_key = '4c2a9913c3814c0badaa0638053ddde7';

// Fonction pour récupérer des recettes de Spoonacular
function getRecipes($query) {
    global $api_key;

    $url = "https://api.spoonacular.com/recipes/complexSearch?query=" . urlencode($query) . "&apiKey=$api_key";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    return $data['results'] ?? [];
}

// Fonction pour récupérer 8 recettes populaires
function getPopularRecipes() {
    global $api_key;

    $url = "https://api.spoonacular.com/recipes/random?number=8&apiKey=$api_key"; // 8 recettes populaires

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);

    return $data['recipes'] ?? [];
}

// Si un utilisateur soumet un ingrédient ou un mot-clé
if (isset($_GET['query'])) {
    $query = $_GET['query']; // Récupérer la recherche
    $recipes = getRecipes($query); // Récupérer les recettes
} else {
    $query = '';
    $recipes = [];
}

// Récupérer les recettes populaires
$popularRecipes = getPopularRecipes();
?>

<!-- Contenu principal de la page des recettes -->
<div class="content">
    <h1>Search for Recipes</h1>

    <!-- Formulaire de recherche -->
    <form method="get" action="recipies.php" class="search-container">
        <input type="text" name="query" placeholder="Search for a recipe..." value="<?= htmlspecialchars($query); ?>" required>
        <button type="submit">Search</button>
    </form>

    <!-- Affichage du carrousel de recettes populaires seulement si aucune recherche n'a été effectuée -->
    <?php if (empty($query)): ?>
        <h2>Popular Recipes</h2>
        <div class="recipe-carousel">
            <?php foreach ($popularRecipes as $recipe): ?>
                <div class="carousel-item">
                    <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                    <img src="<?= $recipe['image']; ?>" alt="<?= htmlspecialchars($recipe['title']); ?>" class="recipe-image">

                    <p><a href="recipedetail.php?id=<?= $recipe['id']; ?>" class="recipe-detail-link">Get Recipe Details</a></p>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Affichage des résultats de la recherche -->
    <?php if (isset($query) && !empty($query)): ?>
        <?php if ($recipes): ?>
            <h2>Found Recipes</h2>
            <div class="recipes">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe">
                        <h3><?= htmlspecialchars($recipe['title']); ?></h3>
                        <img src="<?= $recipe['image']; ?>" alt="<?= htmlspecialchars($recipe['title']); ?>" class="recipe-image">
                        <p><a href="recipedetail.php?id=<?= $recipe['id']; ?>">Get Recipe Details</a></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No recipes found for "<?= htmlspecialchars($query); ?>"</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
// Inclure le footer
require "../include/footerprv.php";
?>
