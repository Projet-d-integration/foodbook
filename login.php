<?php session_start(); ?>

<head>
    <title>Login Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/login.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Login </div>
    </div>

    <div class="wrapper">
        <div class="input-username">
            <input class="text-input" type="text" placeholder="Username...">
        </div>

        <div class="input-password">
            <input type="password" class="text-input" placeholder="Password...">
        </div>

        <div class="validation-login-button">
            <?php GenerateButtonPrimary("Se connecter", "index.php") ?>

        </div>
    </div>

    <?php GenerateFooter(); ?>
</body>