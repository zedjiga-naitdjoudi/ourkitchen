<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'], $_POST['user_name'], $_POST['comment'])) {
    $recipe_id = $_POST['recipe_id'];
    $user_name = htmlspecialchars($_POST['user_name']);
    $comment = htmlspecialchars($_POST['comment']);
    
    try {
        // Vérifier si le même utilisateur a déjà commenté cette recette avec le même commentaire
        $sql_check = "SELECT COUNT(*) FROM comments WHERE recipe_id = :recipe_id AND user_name = :user_name AND comment = :comment";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':recipe_id', $recipe_id);
        $stmt_check->bindParam(':user_name', $user_name);
        $stmt_check->bindParam(':comment', $comment);
        $stmt_check->execute();
        
        $count = $stmt_check->fetchColumn();
        
        if ($count > 0) {
            // Si le commentaire existe déjà, retourner une erreur
            echo json_encode(['error' => 'Vous avez déjà soumis ce commentaire pour cette recette.']);
            exit;
        }

      // Après l'insertion dans la base de données
$sql = "INSERT INTO comments (recipe_id, user_name, comment) VALUES (:recipe_id, :user_name, :comment)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':recipe_id', $recipe_id);
$stmt->bindParam(':user_name', $user_name);
$stmt->bindParam(':comment', $comment);
$stmt->execute();

// Assurez-vous que la date est formatée correctement
$created_at = date('Y-m-d H:i:s');

// Retourner les données sous forme de JSON
echo json_encode([
    'user_name' => $user_name,
    'comment' => $comment,
    'created_at' => $created_at
]);

    } catch (Exception $e) {
        // Retourner une erreur en cas d'exception
        echo json_encode(['error' => 'Erreur lors de l\'ajout du commentaire: ' . $e->getMessage()]);
    }
}
?>
