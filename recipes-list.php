<?php session_start(); ?>

<head>
    <title>Recettes Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/recipes-list.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body>

<div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Liste de recettes </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
        <a href="login.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
        <a href="login.php" class="svg-button login-button"> <?php echo file_get_contents("utilities/account.svg"); ?> </a>
    </div>

    <div class="recipes-container">
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
        <a href="recipe.php" class="recipe-box">
            recette
        </a>
    </div>

    <?php GenerateFooter(); ?>
</body>