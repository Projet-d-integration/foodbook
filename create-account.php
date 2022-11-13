<?php session_start(); ?>

<head>
    <title>Create Account Foodbook</title>
    
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    
    <style>
        <?php require 'styles/create-account.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>



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
<div> 
    <div class="header-banner hide-mobile">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <?php AddSearchBar(); ?>
        <div class="svg-wrapper">
            <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
            <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php 
                if(!empty($_SESSION['idUser'])){
                    echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                    echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button logout-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                }
                else{
                    echo '<a href="login.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                }
            ?>
        </div>
    </div>

    <!-- mobile header -->
    <div class="header-mobile-banner hide-desktop">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <button class="menu-icon" onclick="ShowMenu()"><?php echo file_get_contents("utilities/menu.svg"); ?></button>
    </div>

    <div class="mobile-popup-menu hide-desktop" id="mobile-popup-menu">
        <div class="mobile-svg-wrapper hide-desktop">
            <?php AddSearchBar(); ?>

            <a href="personal-recipes.php" class="svg-button list-button"> 
                <?php echo file_get_contents("utilities/book.svg"); ?> 
                <span class="header-text">Vos recettes</span>
            </a>
            <a href="groceries-list.php" class="svg-button list-button"> 
                <?php echo file_get_contents("utilities/list.svg"); ?> 
                <span class="header-text">Liste d'épicerie</span>
            </a>
            <a href="inventory.php" class="svg-button inventory-button"> 
                <?php echo file_get_contents("utilities/food.svg"); ?> 
                <span class="header-text">Inventaire</span>
            </a>
            <div class="form-exit" onclick='HideMenu()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
            <?php 
                if(!empty($_SESSION['idUser'])){
                    echo '<a href="edit-profil.php" class="svg-button login-button"> 
                        '.file_get_contents("utilities/account.svg");
                        echo "
                        <span class='header-text'>" . User($_SESSION['idUser'])[2] . " " . User($_SESSION['idUser'])[1] . "</span>
                    </a>";
                    echo '<form method="post">
                    <button type="submit" name="buttonDeconnecter" class="svg-button login-button logout-button" value="buttonDeconnecter" />
                        '.file_get_contents("utilities/logout.svg").'
                        <span class="header-text">Se déconnecter</span>
                    </form>';
                }
                else{
                    echo '<a href="login.php" class="svg-button login-button logout-button"> 
                    '.file_get_contents("utilities/account.svg").'
                    <span class="header-text">Se connecter</span>
                    </a>';
                }
            ?>
        </div>
    </div>
</div>

<body>
    <div class="wrapper">
        <div class="banner-title"><span>Créer un comtpe</span></div>
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

<script>
    function ShowMenu() {
        document.getElementById("mobile-popup-menu").style.display = "flex";
    }

    function HideMenu() {
        document.getElementById("mobile-popup-menu").style.display = "none";
    }
</script>