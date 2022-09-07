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
        <a href="login.php" class="button button-secondary login-button"> Login </a>
    </div>
    <div class="wrapper">
        <a href="ui-kit.php" class="button button-primary">Accéder au ui-kit</a>
    </div>  

    <?php GenerateFooter(); ?>
</body>