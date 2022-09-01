<?php session_start(); ?>

<head>
    <title>Acceuil Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/index.css'; ?>
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
    <div class="wrapper">
        <a href="ui-kit.php">Accéder au ui-kit</a>
    </div>  
</body>