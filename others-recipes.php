<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }

    $idUser = $_GET['user']; 
?>
<head>
    <title>Recettes des autres usagers</title>
    
    <meta charset="utf-8" name="viewport" content="width=device-width" >
    
    <style>
        <?php require 'styles/recipes-list.css'; ?>
        <?php require 'styles/others-recipes.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
        <?php require 'scripts/filter.php'; ?>
    </style>
    
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
    <div class="wrapper">
        <form class="form-filter hide-table" method="POST">
            <input type="hidden" name="user" value="<?=$_GET['user']?>">
            <input name="recipe-name" type="text" placeholder="Nom de la recette" value="<?=$_POST['recipe-name']?>"/>
            <label name="recipe-portion">Nombre de portions : </label>
            <select name="recipe-portion" style="width:6rem;">
                <option value="0">Tout</option>
                <option value="1">1 portion</option>
                <option value="2">2 portions</option>
                <option value="3">3 portions</option>
                <option value="4">4 portions</option>
                <option value="5">5 portions</option>
                <option value="6">6 portions</option>
                <option value="7">7 portions</option>
                <option value="8">8 portions</option>
            </select>
            <label name="recipe-time">Temps de préparation : </label>
            <select name="recipe-time" style="width:6rem;">
            <option value="0">Tout</option>
                <option value="15">15 min</option>
                <option value="30">30 min</option>
                <option value="45">45 min</option>
                <option value="60">60 min</option>
                <option value="75">75 min</option>
                <option value="90">90 min</option>
                <option value="105">105 min</option>
                <option value="120">120 min</option>
            </select>
            <button class='filter-button' type='submit' name="filter-button" value="1"><?php echo file_get_contents('utilities/search.svg'); ?></button>
        </form>
        <form class="form-filter-wrapper" method="POST">
            <div class="filters-menu-button hide-tablet-up" onclick="ShowFilters()">Filtres<?php echo file_get_contents("utilities/caret.svg"); ?></div>
            <div class="mobile-filters-menu hide-tablet-up" id="mobile-filters-menu">
                <div class="form-filter hide-tablet-up">
                    <div class="form-exit" onclick='HideFilters()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                    <input type="hidden" name="type" value="<?=$_GET['type']?>">
                    <input name="recipe-name" class="recipe-name" type="text" placeholder="Nom de la recette" value="<?=$_POST['recipe-name']?>"/>
                    <div class="recipe-portion-select">
                        <label name="recipe-portion">Nombre de portions : </label>
                        <select name="recipe-portion" style="width:6rem;">
                            <option value="0">Tout</option>
                            <option value="1">1 portion</option>
                            <option value="2">2 portions</option>
                            <option value="3">3 portions</option>
                            <option value="4">4 portions</option>
                            <option value="5">5 portions</option>
                            <option value="6">6 portions</option>
                            <option value="7">7 portions</option>
                            <option value="8">8 portions</option>
                        </select>
                    </div>
                    <div class="recipe-time-select">
                        <label name="recipe-time">Temps de préparation : </label>
                        <select name="recipe-time" style="width:6rem;">
                        <option value="0">Tout</option>
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">60 min</option>
                            <option value="75">75 min</option>
                            <option value="90">90 min</option>
                            <option value="105">105 min</option>
                            <option value="120">120 min</option>
                        </select>
                    </div>
                    <div class="send-it-button">    
                        <button class='filter-button' type='submit' name="filter-button" value="1">
                            <span class="filter-text">Lancer le filtre</span>
                            <?php echo file_get_contents('utilities/search.svg'); ?> 
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="recipes-container">
            <?php 
                $tabRecette = ShowRecipe($idUser);
                $tabInfoRecipe = InfoRecipe();
                $tabRecette = FilterRecipe($tabRecette,$tabInfoRecipe,$_POST['recipe-name'],'',intval($_POST['recipe-time']),intval($_POST['recipe-portion']));
                foreach($tabRecette as $singleRecette){
                    if($singleRecette[1] == $idUser && $singleRecette[3] == 1){
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
            ?>
        </div>
    </div>
    <?php GenerateFooter(); ?>
</body>


<script>
    window.onload = () => {
        <?php
            if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
                $tabRecette = ShowSingleRecipe($_SESSION['idUser']); //Appeler la méthode ShowSingleRecipe($id_user) pour afficher les recette de cet usager.
                
                if(count($tabRecette) == 0)
                {
                    echo 'document.getElementById("error_user_no_recipes").style.display = "block";';
                }
            }
        ?>
    }
    function ShowMenu() {
        document.getElementById("mobile-popup-menu").style.display = "flex";
    }

    function HideMenu() {
        document.getElementById("mobile-popup-menu").style.display = "none";
    }

    function ShowFilters() {
        document.getElementById("mobile-filters-menu").style.display = "flex";
    }

    function HideFilters() {
        document.getElementById("mobile-filters-menu").style.display = "none";
    }
    
</script>