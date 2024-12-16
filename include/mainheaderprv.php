<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Discover delicious recipes, easy-to-follow cooking guides, and everything you need to master your kitchen with OurKitchen.">
    <meta name="keywords" content="recipes, cooking, kitchen, easy recipes, delicious meals">
    <meta name="author" content="OurKitchen Team">
    <meta name="robots" content="index, follow">
    <meta name="language" content="en">
    <meta name="theme-color" content="#ff6347">
    <title>
        <?php echo $title ?>
    </title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../ressources/icon.png" type="image/x-icon">
</head>

<body>

    <header>
        <div class="logo">
            <a href="../duplicated//index.php">
                <img src="../ressources/logo.png" alt="Logo">
            </a>
        </div>

        <nav>
            <ul>
                <li><a href="../duplicated/index.php">Home</a></li>
                <li><a href="../duplicated/recipies.php">Recipes</a></li>
                <li><a href="../duplicated/about-us.php">About Us</a></li>
                <li><a href="../duplicated/recherche_recettes.php">Search </a></li>
                <li><a href="../view_recipes.php">View</a></li>
                <li><a href="../submit_recipes.php">Submit</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <?php
            // Récupérer le nom d'utilisateur depuis la session ou la base de données
            require "../connection.php";
            $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if ($user) {
                echo '<div class="profile-menu">
                        <button class="profile-button">' . htmlspecialchars($user['username']) . '</button>
                        <div class="profile-dropdown">
                            <a href="../logout.php">Logout</a>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </header>
    <main>