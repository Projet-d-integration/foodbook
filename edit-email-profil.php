<?php 
    session_start(); 
    if(empty($_SESSION['idUser'])){
        echo "<script>window.location.href='index.php'</script>";
    }
?>

<head>
    <title>Modifier votre Email Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/edit-profil.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>


<body>
    
</body>