<?php
require 'connection.php';

if (isset($_GET['recipe_id'])) {
    $recipe_id = $_GET['recipe_id'];

    try {
        // Récupérer les détails de la recette
        $sql = "SELECT id, title, description, ingredients, instructions FROM recipes WHERE id = :recipe_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipe_id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les commentaires de la recette
        $stmt = $conn->prepare("SELECT user_name, comment, created_at FROM comments WHERE recipe_id = :recipe_id ORDER BY created_at DESC");
        $stmt->bindParam(':recipe_id', $recipe_id);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les données au format JSON
        echo json_encode([
            'title' => $recipe['title'],
            'description' => $recipe['description'],
            'ingredients' => $recipe['ingredients'],
            'instructions' => $recipe['instructions'],
            'comments' => $comments
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error while processing']);
    }
}
?>
