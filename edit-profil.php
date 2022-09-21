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

   <?php GenerateFormEditProfil(); ?>
</body>


<?php


if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['edit-confirm-profil']))
    {


        #vérifier que le nom et prenom ne contiennent pas de chiffre



        if(empty($_POST["name-profil-input"]) || empty($_POST["last-name-profil-input"]) || empty($_POST["email-profil-input"]) || empty($_POST["pwd-profil-input"]) || empty($_POST["confirm-pwd-profil-input"]) )
        {
            echo "Tous les champs doivent être remplis.";
        }

        else if(UserExist($_POST['email-profil-input']))
        {
            echo "Cet email existe déja!";
        }

        else if($_POST["pwd-profil-input"] != $_POST["confirm-pwd-profil-input"])
        {
            echo "Les mots de passes ne correspondent pas.";
        }
    }
}



?>