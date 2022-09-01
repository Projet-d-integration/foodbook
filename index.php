<?php session_start(); ?>

<head>
    <title>Acceuil Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/index.css'; ?>
        <?php require 'styles/must-have.css'; ?>
    </style>
</head>

<?php 
    // close session
    // if(!empty($_SESSION))
    // {
    //     session_unset();
    //     session_destroy();
    // } 
?>

<body> 

    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Accueil </div>
    </div>
    <div class="wrapper">
        <a href="ui-kit.php" class="button button-primary">Accéder au ui-kit</a>
    </div>  
</body>