<?php
// Inclure la connexion à la base de données
require 'connection.php';

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

// Initialiser une variable pour stocker les recettes
$recipes = [];

// Récupérer le nom de l'utilisateur depuis la session
$user_name = $_SESSION['username'] ?? ''; // Récupérer le nom d'utilisateur de la session

try {
    // Récupérer toutes les recettes depuis la base de données
    $sql = "SELECT id, title, description, ingredients, instructions, author, created_at 
            FROM recipes 
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur lors de la récupération des recettes : " . $e->getMessage());
}

// Ajouter un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'], $_POST['comment'])) {
    $recipe_id = $_POST['recipe_id'];
    $comment = htmlspecialchars($_POST['comment']);
    
    try {
        $sql = "INSERT INTO comments (recipe_id, user_name, comment) VALUES (:recipe_id, :user_name, :comment)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipe_id);
        $stmt->bindParam(':user_name', $user_name); // Utilisation du nom récupéré de la session
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
    } catch (Exception $e) {
        echo "Erreur lors de l'ajout du commentaire : " . $e->getMessage();
    }
}
?>

<?php
$title = "View Recipes";
require "./include/headerprv.php";
?>

<h1>Liste des recettes</h1>

<!-- Affichage des recettes -->
<?php if (count($recipes) > 0): ?>
    <div class="recipes-container">
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
                <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($recipe['author']) ?></p>
                <p><strong>Date :</strong> <?= htmlspecialchars($recipe['created_at']) ?></p>
                <p><strong>Description :</strong> <?= htmlspecialchars($recipe['description']) ?></p>

                <!-- Lien pour afficher les détails -->
                <button class="open-modal" data-recipe-id="<?= $recipe['id'] ?>">Voir les détails</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucune recette trouvée. Soyez le premier à en soumettre une !</p>
<?php endif; ?>

<a href="submit_recipes.php" class="soumettre">Soumettre une recette</a>

<!-- Modale pour afficher les détails de la recette -->
<div id="recipeModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title"></h2>
        <p><strong>Description :</strong> <span id="modal-description"></span></p>
        <p><strong>Ingrédients :</strong> <span id="modal-ingredients"></span></p>
        <p><strong>Instructions :</strong> <span id="modal-instructions"></span></p>

        <!-- Section des commentaires dans la modale -->
        <h3>Commentaires :</h3>
        <ul id="modal-comments"></ul>

        <!-- Formulaire d'ajout de commentaire -->
        <h3>Ajouter un commentaire :</h3>
        <form id="comment-form" method="post">
            <input type="hidden" name="recipe_id" id="recipe_id">
            <!-- Champ caché pour le nom de l'utilisateur -->
            <input type="hidden" name="user_name" value="<?= htmlspecialchars($user_name) ?>">
            <label for="comment">Commentaire :</label>
            <textarea name="comment" id="comment" required></textarea>
            <button type="submit">Soumettre</button>
        </form>
    </div>
</div>

<?php
require "./include/footer.inc.php";
?>

<script src="script.js"></script>
