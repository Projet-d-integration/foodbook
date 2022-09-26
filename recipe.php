<?php session_start(); ?>

<head>
    <title> Recette Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/recipe.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<bod>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Recette </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
        <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
        <a href="login.php" class="svg-button account-button"> <?php echo file_get_contents("utilities/account.svg"); ?> </a>
    </div>

    <div class="recipe-container">
        <div class="recipe-header">
            <div class="recipe-image">
                image
            </div>
    
            <div class="recipe-title">
                titre
            </div>
        </div>
        <div class="recipe-ingredients">
            <div class="checkboxes-container">
                <div>Ingrédients : </div>
                <input class="checkbox" type="checkbox" id="ingrédient1" name="ingrédient1" value="ingrédient1">
                <label for="ingrédient1"> ingrédient 1</label><br>
                <input class="checkbox" type="checkbox" id="ingrédient2" name="ingrédient2" value="ingrédient2">
                <label for="ingrédient2">ingrédient 2</label><br>
                <input class="checkbox" type="checkbox" id="ingrédient3" name="ingrédient3" value="ingrédient3">
                <label for="ingrédient3" class="strike-through"> ingrédient 3</label><br>
            </div>
        </div>

        <div class="recipe-steps">
            <div class="checkboxes-container">
                <div>Étapes : </div>
                <input class="checkbox" type="checkbox"  id="étape1" name="étapes1" value="étapes1"></input> <label class="strike-through" for="étape1">Étape 1</label><br>
                <input class="checkbox" type="checkbox"  id="étape2" name="étapes2" value="étapes2"></input> <label class="strike-through" for="étape2">Étape 2</label><br> 
                <input class="checkbox" type="checkbox"  id="étape3" name="étapes3" value="étapes3"></input> <label class="strike-through" for="étape3">Étape 3</label><br> 
                        
            </div>
        </div>

        <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="recipe-video">
            Vidéo tutoriel (facultatif)
        </a>

        <div class="recipe-save-like">
            <div class="interractible-svg">
            <?php echo file_get_contents("utilities/like.svg"); ?>
            </div>
                
            <div class="interractible-svg">
            <?php echo file_get_contents("utilities/floppy-disk.svg"); ?>
            </div>
        </div>

        <div class="publisher-info">
            Informations sur l'utilisateur qui a publié la recette
        </div>

        <div class="recipe-comments">
            Section commentaires
        </div>
    </div>
    
    <?php GenerateFooter(); ?>
</body>