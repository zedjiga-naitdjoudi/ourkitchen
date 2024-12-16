
<?php
// Include the database connection
require '../connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Initialize variables for success or error messages
$success_message = "";
$error_message = "";

// Handle the form submission when the user submits a recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate data from $_POST before using it
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $ingredients = isset($_POST['ingredients']) ? trim($_POST['ingredients']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $author = isset($_POST['author']) ? trim($_POST['author']) : '';

    // Check for required fields
    if (empty($title) || empty($description) || empty($ingredients) || empty($instructions) || empty($author)) {
        $error_message = "All fields are required. Please fill them out.";
    } else {
        try {
            // Prepare and execute the SQL query to insert a recipe
            $sql = "INSERT INTO recipes (title, description, ingredients, instructions, author, created_at)
                    VALUES (:title, :description, :ingredients, :instructions, :author, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':ingredients' => $ingredients,
                ':instructions' => $instructions,
                ':author' => $author
            ]);

            $success_message = "Recipe submitted successfully!";
        } catch (Exception $e) {
            $error_message = "An error occurred while submitting the recipe: " . $e->getMessage();
        }
    }
}
?>

<?php
$title = "Submit a Recipe";
require "../include/mainheaderprv.php";
?>

<!-- Display success or error messages -->
<?php if (!empty($success_message)): ?>
    <p class="success"><?= htmlspecialchars($success_message) ?></p>
<?php elseif (!empty($error_message)): ?>
    <p class="error"><?= htmlspecialchars($error_message) ?></p>
<?php endif; ?>

<!-- Recipe submission form -->
<div class="submit">
    <form action="submit_recipes.php" method="POST">
        <h2>Submit a Recipe</h2>

        <!-- Input fields -->
        <label for="title">Recipe Name</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="ingredients">Ingredients</label>
        <textarea id="ingredients" name="ingredients" required></textarea>

        <label for="instructions">Instructions</label>
        <textarea id="instructions" name="instructions" required></textarea>

        <label for="author">Author</label>
        <input type="text" id="author" name="author" required>

        <button type="submit">Submit</button>
    </form>

    <!-- Link to the home page -->
    <a href="welcome.php">Back</a>
</div>

<?php
require "../include/footerprv.php";
?>
