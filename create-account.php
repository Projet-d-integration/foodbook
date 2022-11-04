<?php session_start(); ?>

<head>
    <title>Create Account Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/create-account.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

<body>
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            AddAnimation();
    ?>
    <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            if(!isset($_POST['name-input'])){ $name = ""; } 
            else { $name = $_POST['name-input']; } 
            
            if(!isset($_POST['last-name-input'])){ $last_name = ""; } 
            else { $last_name = $_POST['last-name-input']; } 

            if(!isset($_POST['email-input'])){ $email = ""; } 
            else { $email = $_POST['email-input']; } 

            if(!isset($_POST['password-input'])){ $pwd = ""; } 
            else { $pwd = $_POST['password-input']; } 
            
            if(!isset($_POST['password-input-confirm'])){ $pwdconfirm = ""; } 
            else { $pwdconfirm = $_POST['password-input-confirm']; } 

            // Vérifie si tous les champs ont été entrés.
            if(!empty($name)
            && !empty($last_name)
            && !empty($email)
            && !empty($pwd)
            && !empty($pwdconfirm))
            {
                // Affiche un message d'erreur si le courriel est déjà utilisé
                if (UserExist($_POST['email-input'])){
                    echo '
                    <script>
                        window.onload = () => { document.getElementById("error_email_used").style.display = "block"; }
                    </script>';
                }
                else if ($pwd != $pwdconfirm){
                    echo '
                    <script>
                        window.onload = () => { document.getElementById("error_mdp_confirm").style.display = "block"; }
                    </script>';
                }
                else if(ValidateEmailInput($email)) {
                    echo '
                    <script>
                        window.onload = () => { document.getElementById("error_email").style.display = "block"; }
                    </script>';
                }
                else if(!ValidateNameInput($name) || 
                        !ValidateNameInput($last_name)) {
                    echo '
                    <script>
                        window.onload = () => { document.getElementById("error_name").style.display = "block"; }
                    </script>';
                }
                else if (!ValidatePasswordInput($pwd)){
                    echo '  
                    <script>
                        window.onload = () => { document.getElementById("error_mdp").style.display = "block"; }
                    </script>';
                }
                else {
                    AddUser($_POST['last-name-input'], $_POST['name-input'], $_POST['email-input'], $_POST['password-input']);
                }
            }
            else {
                echo '
                <script>
                    window.onload = () => { document.getElementById("error_entries").style.display = "block"; }
                </script>';
            }
        }
    ?>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Créer un compte </div>
    </div>

    <div class="wrapper">
        <form method="post" class="create-account-form">
            <input class="text-input name-input" name="name-input" type="text" placeholder="Nom" value="<?= $name ?>">
            <input class="text-input last-name-input" name="last-name-input" type="text" placeholder="Prénom" value="<?= $last_name?>">
            <input class="text-input email-input" name="email-input" type="text" placeholder="Addresse courriel" value="<?=$email?>">
            <input class="text-input password-input" name="password-input" type="password password-input" placeholder="Mot de passe">
            <input class="text-input password-input" name="password-input-confirm" type="password password-input" placeholder="Confirmer le mot de passe">

            <div class="error_message" id="error_entries">Tous les champs sont obligatoires</div>
            <div class="error_message" id="error_email_used">Courriel déjà utilisé</div>
            <div class="error_message" id="error_email">Courriel invalide</div>
            <div class="error_message" id="error_mdp_confirm">Les mots de passe ne correspondent pas</div>
            <div class="error_message" id="error_name">Nom ou nom de famille invalide : ne doivent que contenir des lettres</div>
            <div class="error_message" id="error_mdp">Mot de passe invalide, il doit contenir minumum 5 caractères, dont au moins 1 lettre et 1 chiffre</div>

            <div class="buttons-wrapper">
                <div class="create-account-button">
                    <?php GenerateButtonTertiary("Se connecter", "login.php");?>
                </div>
                
                <input class="button button-primary" type="submit" value="S'inscrire">
            </div>
        </form>
    </div>

    <?php GenerateFooter(); ?>
</body>