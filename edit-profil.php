<?php session_start(); ?>

<head>
    <title>Modifier Profil Foodbook</title>
    
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
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Modifier mon profil </div>
    </div>

    <?php $info_user = UserInfo($_SESSION['email']) ?>

    <form method="POST" class="edit-profil-form">
        <input type="text" name="name-profil-input" placeholder="New name..." class="text-input-profil">

        <input type="text" name="last-name-profil-input" placeholder="New last name..." class="text-input-profil">

        <input type="email" name="email-profil-input" placeholder="New email..." class="text-input-profil">

        <input type="password" name="pwd-profil-input" placeholder="New password..." class="text-input-profil">

        <input type="password" name="confirm-pwd-profil-input" placeholder="Confirm new password..." class="text-input-profil">

        <input type="submit" value="Modifier" class="button button-primary" name="edit-confirm-profil">
    </form>
</body>




<?php


if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['edit-confirm-profil']))
    {

        if(UserExist($_POST['email-profil-input']))
        {
            echo "Cet email existe déja!";
        }

        else if($_POST["pwd-profil-input"] != $_POST["confirm-pwd-profil-input"])
        {
            echo "Les mots de passes ne correspondent pas";
        }
    }
}



?>