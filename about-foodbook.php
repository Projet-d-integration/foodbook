<?php
 session_start(); 
 if(array_key_exists('buttonDeconnecter', $_POST)) {
     session_destroy();
     header('Location: index.php');
 }
?>

<head>
    <title>À propos de Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/about-page.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'styles/ui-kit.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    <?php RenderFavicon(); ?>
</head>

<body>

        <div class="header-banner">
                <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
                <div class="banner-title">À propos de nous </div>
                <div class="svg-wrapper">
                    <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
                    <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
                    <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
                    <?php 
                        if(!empty($_SESSION['idUser'])){
                            echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                            echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                        }
                        else{
                            echo '<a href="login.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                        }
                    ?>
                </div>
        </div>


        <div class="main-wrapper">
            <div class="container-main-display">
                <div class="container-for-message">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aliquam enim eligendi blanditiis molestias, soluta numquam quibusdam vitae aliquid tenetur, 
                    quo sapiente iste illum eius neque fugit dolores corrupti voluptatum sit!
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nobis amet, impedit consequuntur at repellat repudiandae 
                    cum incidunt iste distinctio placeat ut nesciunt possimus officiis vel perspiciatis, inventore quod in doloribus.
                </div>
            </div>
            
    
        </div>





<?php GenerateFooter(); ?>
</body>