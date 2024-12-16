<?php
$title = "Recipe Details";
require "./include/mainheader.inc.php";

// Ta clé API Spoonacular
$api_key = '4c2a9913c3814c0badaa0638053ddde7';

// Fonction pour récupérer les détails d'une recette par son ID
function getRecipeDetails($id) {
    global $api_key;

    // URL pour récupérer les détails de la recette
    $url = "https://api.spoonacular.com/recipes/$id/information?apiKey=$api_key";

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response === false) {
        return null;
    }
    
    return json_decode($response, true);
}

// Récupérer l'ID de la recette depuis l'URL
if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];
    $recipe_details = getRecipeDetails($recipe_id);
} else {
    $recipe_details = null;
}

?>

<!-- Contenu principal de la page de détails -->
<div class="recipe-detail">
    <?php if ($recipe_details): ?>
        <h1><?= htmlspecialchars($recipe_details['title']); ?></h1>
        <img src="<?= $recipe_details['image']; ?>" alt="<?= htmlspecialchars($recipe_details['title']); ?>" class="recipe-image">
        
        <h3>Ingredients</h3>
        <ul>
            <?php foreach ($recipe_details['extendedIngredients'] as $ingredient): ?>
                <li><?= htmlspecialchars($ingredient['original']); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <h3>Instructions</h3>
        <?php
        // Vérifie si les instructions sont présentes
        if (!empty($recipe_details['instructions'])) {
            // Diviser les instructions en éléments en utilisant le saut de ligne \n
            // Si les instructions sont déjà séparées par des balises <li> dans l'API, tu peux les afficher directement
            $instructions = $recipe_details['instructions']; // Peut-être déjà formaté en HTML

            // Si les instructions ne sont pas formatées, on peut les traiter :
            if (strpos($instructions, '<li>') === false) {
                // Découper les instructions en une liste de lignes (chaque ligne devient une étape)
                $instructions = explode("\n", $instructions);
                ?>
                <ol>
                    <?php foreach ($instructions as $instruction): ?>
                        <li><?= htmlspecialchars(trim($instruction)); ?></li>
                    <?php endforeach; ?>
                </ol>
                <?php
            } else {
                // Si les instructions contiennent déjà des balises <li>, on les affiche telles quelles
                echo $instructions;
            }
        } else {
            echo "<p>No instructions available.</p>";
        }
        ?>
        
        <h3>Additional Information</h3>
        <p><strong>Ready in:</strong> <?= $recipe_details['readyInMinutes']; ?> minutes</p>
        <p><strong>Servings:</strong> <?= $recipe_details['servings']; ?></p>
        
        <!-- Bouton de retour -->
        <a href="recipies.php" class="btn">Back to Recipes List</a>
        
    <?php else: ?>
        <p>Sorry, the details for this recipe are not available.</p>
    <?php endif; ?>
</div>

<?php
// Inclure le footer
require "./include/footer.inc.php";
?>
