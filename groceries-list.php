<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }

    if(empty($_SESSION['idUser']))
    {
        echo '<script>window.location.href = "login.php";</script>';
    }
?>

<head>
    <title>Login Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/groceries-list.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Liste d'épicerie </div>
        <div class="svg-wrapper">
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
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

    <div class="wrapper-list">

        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
        <div class="box-ingredient">un ingrédient</div>
    </div>



    <?php GenerateFooter(); ?>
</body>