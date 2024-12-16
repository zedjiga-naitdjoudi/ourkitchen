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
require "../connection.php"; // Connexion à la base de données si nécessaire
$title = "Search recipes"; // Titre de la page
require "../include/mainheaderprv.php"; // Inclusion du header

?>

<div class="container">
    <div class="form_area">
        <p class="title">Search a recipe</p>
        <form id="recipeForm">
            <div class="form_group">
                <label class="sub_title" for="ingredients">Enter your ingredients:</label>
                <input id="ingredients" name="ingredients" class="form_style" placeholder="example: chicken, tomato"
                    type="text" required>
            </div>
            <div>
                <button type="submit" class="btn">Search</button>
            </div>
        </form>
    </div>

    <!-- Zone d'affichage des résultats -->
    <div id="results" class="results"></div>
</div>

<script>
document.getElementById('recipeForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche l'envoi du formulaire traditionnel

    const ingredients = document.getElementById('ingredients').value;

    // L'URL de l'API Spoonacular avec ta clé
    const apiKey = 'a7cf083bfca84882a6ea61ef71d722ff';
    const url = `https://api.spoonacular.com/recipes/findByIngredients?apiKey=${apiKey}&ingredients=${encodeURIComponent(ingredients)}&number=5`;

    // Effectuer la requête fetch
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Affichage des résultats
            let resultsHtml = '<h2 class="sub_title">Results:</h2>';
            
            if (data.length > 0) {
                data.forEach(recipe => {
                    resultsHtml += `
                        <div class="recipe">
                            <h3>${recipe.title}</h3>
                            <img src="${recipe.image}" alt="${recipe.title}" style="width:100%;max-width:300px;border-radius:5px;">
                            <p>Missing ingredients: ${recipe.missedIngredients.length}</p>
                            <button class="btn" onclick="viewDetails(${recipe.id})">View Details</button>
                        </div>
                    `;
                });
            } else {
                resultsHtml += '<p>No recipes found for these ingredients.</p>';
            }

            // Insérer les résultats dans la div
            document.getElementById('results').innerHTML = resultsHtml;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});

// Fonction pour afficher les détails d'une recette
function viewDetails(recipeId) {
    const apiKey = 'a7cf083bfca84882a6ea61ef71d722ff';
    const url = `https://api.spoonacular.com/recipes/${recipeId}/information?apiKey=${apiKey}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let detailsHtml = `
                <div class="recipe-detail">
                    <h1>${data.title}</h1>
                    <img src="${data.image}" alt="${data.title}" class="recipe-image">
                    <h3>Ingredients</h3>
                    <ul>
                        ${data.extendedIngredients.map(ingredient => `<li>${ingredient.original}</li>`).join('')}
                    </ul>
                    <h3>Instructions</h3>
                    <p>${data.instructions || 'No instructions available.'}</p>
                    <h3>Additional Information</h3>
                    <p><strong>Ready in:</strong> ${data.readyInMinutes} minutes</p>
                    <p><strong>Servings:</strong> ${data.servings}</p>
                </div>
            `;
            document.getElementById('results').innerHTML = detailsHtml;
        })
        .catch(error => {
            console.error('Error fetching recipe details:', error);
        });
}
</script>

<?php
require "../include/footerprv.php"; // Inclusion du footer
?>
