<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }
?>

<head>
    <title>Recherche</title>
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    <style>
        <?php require 'styles/recipes-list.css'; ?>
        <?php require 'scripts/db.php'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/filter.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

<body>
    <?php 
        AddAnimation();
    ?>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title">Recherche</div>
        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>
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
    <div class="wrapper">
        <div class="recipes-container">
            <?php 
                if(true/*!($_SERVER['REQUEST_METHOD'] === 'POST')*/){
                    // add le get pour filter les nom
                    $tabRecette = ShowRecipe();
                    //add les filter
                    foreach($tabRecette as $singleRecette){
                        if(true/*$singleRecette[6] == $typeRecette && $singleRecette[3] == 1 */){
                            $infoRecipe = InfoRecipeByID($singleRecette[0]);
                            $srcImage =  $infoRecipe[0][5];
                            echo "
                            <a href='recipe.php?id=$singleRecette[0]' class='recipe-box'>
                                <div class='recipe-overlay'></div>
                                <span class='recipe-title'>$singleRecette[2]</span>
                                <img src='$srcImage' title='$singleRecette[2]' class='recipe-image'>
                            </a>";
                        }
                    }
                }
                else{

                }
            ?>
        </div>
        <div class="users-containers">
            <?php 
                if(!($_SERVER['REQUEST_METHOD'] === 'POST')){
                    // add le get pour filter les nom
                    
                }
                else{

                }
            ?>
        </div>
    </div>
    <?php GenerateFooter(); ?>
</body>