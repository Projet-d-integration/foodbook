<?php session_start(); ?>

<head>
    <title>Accueil Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/index.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Accueil </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <div class="svg-wrapper">
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <a href="login.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="login.php" class="svg-button login-button"> <?php echo file_get_contents("utilities/account.svg"); ?> </a>
            <?php 
                if(!empty($_SESSION['idUser'])){
                    echo '<button onclick class="svg-button login-button"> '.file_get_contents("utilities/logout.svg").'</button>';
                }
            ?>
        </div>
    </div>

    <div class="separators">
        <a href="recipes-list.php" class="separator">
           <div class="separator-text">Poulet</div>
           <div class="arrow">></div>
        </a>
        <a href="recipes-list.php" class="separator">
           <div class="separator-text">Boeuf</div>
           <div class="arrow">></div>
        </a>
        <a href="recipes-list.php" class="separator">
           <div class="separator-text">Végé</div>
           <div class="arrow">></div>
        </a>
        <a href="recipes-list.php" class="separator">
           <div class="separator-text">Pâtes</div>
           <div class="arrow">></div>
        </a>
    </div>  

    <?php GenerateFooter(); ?>
</body>