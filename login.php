<?php session_start(); ?>

<head>
    <title>Login Foodbook</title>

    <meta charset="utf-8" name="viewport" content="width=device-width" />

    <style>
        <?php require 'styles/login.css'; ?><?php require 'styles/must-have.css'; ?><?php require 'scripts/body-scripts.php'; ?><?php require 'scripts/db.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (
            !empty($_POST['email-input'])
            && !empty($_POST['password-input'])
        ) {
            if (VerifyUser($_POST['email-input'], $_POST['password-input'])) {
                $UserInfo = UserInfo($_POST['email-input']);
                $_SESSION['idUser'] = $UserInfo[0];
                $_SESSION['nom'] = $UserInfo[1];
                $_SESSION['prenom'] = $UserInfo[2];
                $_SESSION['email'] = $UserInfo[3];
                echo "<script>window.location.href='index.php'</script>";
            } else {
                echo '
                <script>
                    window.onload = () => { document.getElementById("error_connexion").style.display = "block"; }
                </script>';
            }
        } else {
            echo '  
            <script>
                window.onload = () => { document.getElementById("error_entries").style.display = "block"; }
            </script>';
        }
    }
?>

<div> 
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            AddAnimation();
    ?>
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
    <div class="login-wrapper">
        <div class="login-image hide-mobile">
            <img src="utilities/background-login.jpg"></img>    
        </div>
    
        <form class="wrapper" method="POST">
            <div class="login-image hide-desktop">
                <img src="utilities/background-login.jpg"></img>    
            </div>

            <div class="banner-title"><span>Connexion</span></div>

            <div class="login-infos">
                <input class="text-input" name="email-input" type="text" placeholder="Addresse courriel">
                <input type="password" name="password-input" class="text-input" placeholder="Mot de passe">
    
                <div class="error_message" id="error_entries">Tous les champs sont obligatoires</div>
                <div class="error_message" id="error_connexion">Nom d'utilisateur ou mot de passe invalide</div>
    
                <div class="success_message" id="successful_login">Vous avez été connecté avec succès!</div>
    
                <div class="buttons-wrapper">
                    <div class="create-account-button">
                        <?php GenerateButtonTertiary("S'inscrire", "create-account.php");?>
                    </div>
    
                    <div>
                        <input type="submit" name="validation-login" value="Se connecter" class="button button-primary">
                    </div>
                </div>
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