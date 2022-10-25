<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }

    if(empty($_SESSION['idUser']))
    {
        echo '<script>window.location.href = "login.php";</script>';
    }
?>
<head>
    <title>Accueil Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/personal-recipes.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Vos Recettes </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <div class="svg-wrapper">
            <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php 
                if(!empty($_SESSION['idUser'])) {
                    echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                    echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                }
                else {
                    echo '<a href="login.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                }
            ?>
        </div>
    </div>

    <div class="wrapper">
        <div class="personal-recipes-wrapper">
            <div class="add-new-recipe" id="add_new_recipe" onclick='ShowFormRecipeCreation()'>Ajouter un emplacement</div>
            <div class="neutral_message" id="error_no_recipes">Vous n'avez pas de recettes pour l'instant.</div>

            <div class="recipe-creation-form"></div>
        </div>
    </div>

    <?php GenerateFooter(); ?>
</body>


<script>
window.onload = () => {
    <?php
        if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
            echo 'document.getElementById("add_new_recipe").style.display = "block";';

            // Vérfie la quantité de recettes de l'utilisateur, et affiche un message
            // $tabRecettes = ShowRecipe($_SESSION['idUser']);
            // $numTabRecette = count($tabRecettes);
            // $tabRecettes <= 0
            if(-1 <= 0) {
                echo 'document.getElementById("error_no_recipes").style.display = "block";';
            }
        }
    ?>

    function ShowFormRecipeCreation() {
        document.getElementById("recipe-creation-form").style.display = "block";
    }

    function HideFormRecipeCreation() {
        document.getElementById("recipe-creation-form").style.display = "none";
    }
}
</script>