<?php session_start(); ?>

<head>
    <title>Acceuil Foodbook</title>
    <meta charset="utf-8">
    <?php require_once('fonctions.php'); ?>
    <?php require_once('sql.php'); ?>
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
        Hello i am a wrapper
    </div>  
</body>

