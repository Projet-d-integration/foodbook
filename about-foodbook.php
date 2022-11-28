<?php
session_start();
if (array_key_exists('buttonDeconnecter', $_POST)) {
    session_destroy();
    header('Location: index.php');
}
?>

<head>
    <title>À propos de Foodbook</title>
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    <style>
        <?php require 'styles/about-page.css'; ?><?php require 'styles/must-have.css'; ?><?php require 'styles/ui-kit.css'; ?><?php require 'scripts/body-scripts.php'; ?><?php require 'scripts/db.php'; ?>
    </style>
    <?php RenderFavicon(); ?>
</head>

<div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
        AddAnimation();
    ?>
    <div class="header-banner hide-mobile">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <?php AddSearchBar(); ?>
        <div class="svg-wrapper">
            <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
            <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php
            if (!empty($_SESSION['idUser'])) {
                echo '<a href="edit-profil.php" class="svg-button login-button"> ' . file_get_contents("utilities/account.svg") . '</a>';
                echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button logout-button" value="buttonDeconnecter" />' . file_get_contents("utilities/logout.svg") . '</form>';
            } else {
                echo '<a href="login.php" class="svg-button login-button"> ' . file_get_contents("utilities/account.svg") . '</a>';
            }
            ?>
        </div>
    </div>

    <!-- mobile header -->
    <div class="header-mobile-banner hide-desktop">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <button class="menu-icon" onclick="ShowMenu()"><?php echo file_get_contents("utilities/menu.svg"); ?></button>
    </div>

    <div class="mobile-popup-menu hide-desktop" id="mobile-popup-menu">
        <div class="mobile-svg-wrapper hide-desktop">
            <?php AddSearchBar(); ?>

            <a href="personal-recipes.php" class="svg-button list-button">
                <?php echo file_get_contents("utilities/book.svg"); ?>
                <span class="header-text">Vos recettes</span>
            </a>
            <a href="groceries-list.php" class="svg-button list-button">
                <?php echo file_get_contents("utilities/list.svg"); ?>
                <span class="header-text">Liste d'épicerie</span>
            </a>
            <a href="inventory.php" class="svg-button inventory-button">
                <?php echo file_get_contents("utilities/food.svg"); ?>
                <span class="header-text">Inventaire</span>
            </a>
            <div class="form-exit" onclick='HideMenu()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
            <?php
            if (!empty($_SESSION['idUser'])) {
                echo '<a href="edit-profil.php" class="svg-button login-button"> 
                        ' . file_get_contents("utilities/account.svg");
                echo "
                        <span class='header-text'>" . User($_SESSION['idUser'])[2] . " " . User($_SESSION['idUser'])[1] . "</span>
                    </a>";
                echo '<form method="post">
                    <button type="submit" name="buttonDeconnecter" class="svg-button login-button logout-button" value="buttonDeconnecter" />
                        ' . file_get_contents("utilities/logout.svg") . '
                        <span class="header-text">Se déconnecter</span>
                    </form>';
            } else {
                echo '<a href="login.php" class="svg-button login-button logout-button"> 
                    ' . file_get_contents("utilities/account.svg") . '
                    <span class="header-text">Se connecter</span>
                    </a>';
            }
            ?>
        </div>
    </div>
</div>

<body>
    <div class="main-wrapper">
        <div class="wrapper-about">

            <div class="image-about-page">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/CollegeLionelGroulx.jpg/1024px-CollegeLionelGroulx.jpg">
            </div>
            <div class="message-about-us">
                <h2>Qui sommes-nous?</h2>
                <p>
                    Notre site FoodBook réalisé par trois jeunes étudiants travaillants et inventifs du Collège Lionel-Groulx est
                    une plateforme qui permet la gestion de nourriture et de recettes ainsi qu'un réseau social dynamique. C'est le site idéal pour Il est à seul but de faciliter la vie des
                    gens lorsqu'il est question d'alimentation car nous savons de nos jours que c'est un sujet qui peut être compliqué.
                </p>
                <p>
                    Pour ce qui est du réseau social, nous voulons que nos utilisateurs se sentent à l'aisent de publier, commenter et évaluer
                    les recettes des autres utilisateurs dans le plus grand respect. Nous voulons que cela puissent également créer des amitiés entre les utilisateurs
                    afin que notre site soit propice à un environnement agréable pour tous.
                </p>

                <p>
                    Comme mentionné plutôt, nous sommes trois étudiants du Collège Lionel-Groulx qui se sont données coprs et âmes à la confection
                    de FoodBook. Étant tous jeunes et passionés d'informatique nous avons fait tout ce qui étaient en notre pouvoir grâce l'aide de nos connaissances et aptitudes, nous voulions simplement facilité et amélioré la vie des gens qui auraient de
                    la difficulté à gérer leur nourriture ainsi que pouvoir créer des liens d'amitiés sain et affectueux. Nous vous remercions infiniement de faire confiance à notre site web
                    et espèrons que vous allez prendre plaisir à toutes ses fonctionnalités.
                </p>
                <p>
                    <?php GenerateButtonPrimary("Visitez le site", "index.php") ?>
                </p>
            </div>

        </div>


    </div>
    <?php GenerateFooter(); ?>
</body>

<script>
    function ShowMenu() {
        document.getElementById("mobile-popup-menu").style.display = "flex";
    }

    function HideMenu() {
        document.getElementById("mobile-popup-menu").style.display = "none";
    }
</script>