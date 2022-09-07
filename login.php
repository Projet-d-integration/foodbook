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

    <?php header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ"); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Login </div>
    </div>

    <div class="wrapper">
        
    </div>

    <?php GenerateFooter(); ?>
</body>