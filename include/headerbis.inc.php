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
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./ressources/icon.png" type="image/x-icon">
    <script>
        function checkUsername(username) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_username.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                document.getElementById("username-check").innerText = this.responseText;
            };
            xhr.send("username=" + encodeURIComponent(username));
        }
    </script>
</head>

<body>

    <header>
        <div class="logo">
            <a href="./index.php">
                <img src="./ressources/logo.png" alt="Logo">
            </a>
        </div>

        <nav>
            <ul>
                <li><a href="./index.php">Home</a></li>
                <li><a href="./recipies.php">Recipies</a></li>
                <li><a href="./recherche_recettes.php">Search </a></li>
                <li><a href="./about-us.php">About Us</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <a href="login.php" class="login">Log In</a>


        </div>
    </header>
    <main>