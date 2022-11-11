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
        <?php require 'styles/search-list.css'; ?>
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
        </div>
    </div>
    <?php GenerateFooter(); ?>
</body>