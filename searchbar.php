<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }
    $wordSearched = $_GET['keyword'];
?>

<head>
    <title>Recherche</title>
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    <style>
        <?php require 'styles/searchbar.css'; ?>
        <?php require 'scripts/db.php'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/filter.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

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
    <div class="wrapper-container">
        <div class="wrapper-recipes wrapper-background">
            <div>
                <p class="recipe-title-wrapper">Recettes</p>
            </div>
            <div class="recipes-container">
                <?php 
                    // add le get pour filter les nom
                    $tabRecette = ShowRecipe();
                    $tabInfoRecipe = InfoRecipe();
                    $tabRecette = FilterRecipe($tabRecette,$tabInfoRecipe,$wordSearched);
                    //add les filter
                    foreach($tabRecette as $singleRecette){
                        $infoRecipe = InfoRecipeByID($singleRecette[0]);
                        $srcImage =  $infoRecipe[0][5];
                        echo "
                            <a href='recipe.php?id=$singleRecette[0]' class='recipe-box'>
                                <div class='recipe-overlay'></div>
                                <span class='recipe-title'>$singleRecette[2]</span>
                                <img src='$srcImage' title='$singleRecette[2]' class='recipe-image'>
                            </a>";
                        
                    }
                ?>
            </div>
            <?php
                if(count($tabRecette) == 0){
                    echo "<div class='recipe-empty'>
                            <p>Aucune recette trouvé!</p>
                        </div>";
                }
            ?>
        </div>
        <div class="wrapper-users wrapper-background">
            <div>
                <p class="user-title-wrapper">Utilisateurs</p>
            </div>
            <div class="users-container">
                <?php 
                        // add le get pour filter les nom
                    $tabUser = AllUserInfo();
                    $tabUser = FilterUsers($tabUser,$wordSearched);
                    foreach($tabUser as $singleUser){
                        echo "<a class='link-user button button-Primary' href='others-recipes.php?user=$singleUser[0]'>$singleUser[2] $singleUser[1]</a>";
                    }
                ?>
            </div>
            <?php 
                if(count($tabUser) == 0){
                    echo "<div>
                            <p class='user-empty'>Aucun utilisateur trouvé!</p>
                        </div>";
                }
            ?>
        </div>
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