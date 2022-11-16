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
    <title>Accueil Foodbook</title>
    
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    
    <style>
        <?php require 'styles/personal-recipes.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
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
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php 
                    echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                    echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        if(!empty($_POST['title-input'])){
                            if(isset($_POST['isPublic'])){
                                echo AddRecipe($_SESSION['idUser'],$_POST['title-input'],1,0,date('Y-m-d H:i:s'),$_POST['type-input']);
                                $idRecette = LastInsertedRecipe();
                                AddInfoRecipe($idRecette,$_POST['image-input'],$_POST['video-input'],$_POST['recipe-portion'],$_POST['recipe-time']) ;
                                ChangePage("personal-recipes.php");
                            }else{
                                echo AddRecipe($_SESSION['idUser'],$_POST['title-input'],0,0,date('Y-m-d H:i:s'),$_POST['type-input']);
                                $idRecette = LastInsertedRecipe();
                                AddInfoRecipe($idRecette,$_POST['image-input'],$_POST['video-input'],$_POST['recipe-portion'],$_POST['recipe-time']) ;
                                ChangePage("personal-recipes.php");
                            }
                        }
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
        <div class="banner-title"><span>Vos recettes</span></div>
        <a class="button button-tertiary" id="add_new_recipe" onclick='ShowFormRecipeCreation()'> 
            <span> Ajouter une recette</span>
            <div class="button-tertiary-arrow"> <?php echo file_get_contents("./utilities/arrow.svg") ?></div>
        </a>
        <div class="recipes-container">
            <?php 
                $tabRecette = ShowRecipe($_SESSION['idUser']);
                echo '  
                    <form method="post" class="personal-recipes-form">
                    <div class="space-grid">';
                    foreach($tabRecette as $recette){
                        $infoRecipe = InfoRecipeByID($recette[0]);
                        $srcImage =  $infoRecipe[0][5];
                        // <a type='submit' name='buttonSpace' value='$recette[0]'> $recette[2] <div class='space-div-arrow'>". file_get_contents("utilities/caret.svg") ."</div> </a>
                        echo "
                            <a type='submit' name='buttonSpace' value='$recette[0]' href='recipe.php?id=$recette[0]' class='recipe-box'>
                            <div class='recipe-overlay'></div>
                            <span class='recipe-title'>$recette[2]</span>
                            <img src='$srcImage' title='$recette[2]' class='recipe-image'>
                        </a>";
                    }
                echo '</div>
            </form>';
            
            ?>
            <div class="neutral_message" id="error_no_recipes">Vous n'avez pas de recettes pour l'instant.</div>
            <div class="recipe-creation-form"></div>
        </div>

        <div class="personal-recipes-add-recipe-form" id="personal-recipes-add-recipe-form">
            <div class="transparent-background">
                <form method="post" class="form-content">
                    <div class="form-content-wrapper">
                        <div class="form-exit" onclick='HideFormRecipeCreation()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                        <div class="recipe-form-title">Ajout une recette</div>
                        <div class="recipe-form-image-input">
                            <label for="image-input">Url de l'image</label>
                            <input type="text" name="image-input" class="text-input">
                        </div>
    
                        <div class="recipe-form-title-input">
                            <label for="title-input">Titre</label>
                            <input type="text" name="title-input" class="text-input">
                        </div>

                        <div>Veuillez choisir un type pour votre recette</div>
                        <select name="type-input">
                            <?php  
                                $tabTypeRecette = InfoTypeRecipe();
                                foreach($tabTypeRecette as $typeIngredient){
                                    echo "<option value=$typeIngredient[0]>$typeIngredient[1]</option>";
                                }
                            ?>
                        </select>

                        <div class="recipe-form-video-input">
                            <label for="video-input">Lien pour la vidéo</label>
                            <input type="text" name="video-input" class="text-input">
                        </div>
                        
                        <label name="recipe-portion">Nombre de portions : </label>
                    <select name="recipe-portion" style="width:6rem;">
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
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                        <option value="75">75 min</option>
                        <option value="90">90 min</option>
                        <option value="105">105 min</option>
                        <option value="120">120 min</option>
                    </select>
                        
                        <div class="recipe-form-private-input">
                            <div>Rendre la recette publique</div>
                            <input type="checkbox" name="isPublic">
                        </div>

                        <input name="add-new-recipe" value="Ajouter" class="button button-primary" type="submit">
                        <div id="empty-field-form-add-recipe" class="error_message">Veuillez remplir les champs obligatoires.</div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Form pour ajouter un ingrédient à la recette -->
        <div class="personal-recipes-add-recipe-form" id="personal-recipes-add-ingredient-form">
            <div class="transparent-background">
                <form method="post" class="items-form-content">
                    <div class="form-content-wrapper">
                        <div class="form-exit" onclick='HideFormAddNewIngredient()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                        <!-- add items form here -->
                    </div>
                </form>
            </div>
        </div>

        <!-- Form pour ajouter une étape à la recette -->
        <div class="personal-recipes-add-recipe-form" id="personal-recipes-add-step-form">
            <div class="transparent-background">
                <form method="post" class="items-form-content">
                    <div class="form-content-wrapper">
                        <div class="form-exit" onclick='HideFormAddNewStep()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                        <!-- add items form here -->

                        <div class="recipe-form-step-input">
                            <label for="image-input">Ajouter une étape</label>
                            <input type="text" name="step-input" class="text-input step-input">
                            <input type="submit" class="button button-primary add-new-step" id="add_new_step" value="Ajouter l'étape">
                        </div>
                    </div>
                </form>
            </div>
        </div>
            
    </div>
    <?php GenerateFooter(); ?>
</body>





<script defer>
    window.onload = () => {
        <?php
            if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
                echo 'document.getElementById("add_new_recipe").style.display = "flex";';

                $tabRecette = ShowRecipe($_SESSION['idUser']);
                
                if(!(count($tabRecette) > 0)) {
                    echo 'document.getElementById("error_no_recipes").style.display = "flex";';
                }
            }
        ?>
    }

    function ShowFormRecipeCreation() {
        document.getElementById("personal-recipes-add-recipe-form").style.display = "flex";
    }

    function HideFormRecipeCreation() {
        document.getElementById("personal-recipes-add-recipe-form").style.display = "none";
    }

    function ShowFormAddNewIngredient() {
        document.getElementById("personal-recipes-add-ingredient-form").style.display = "flex";
    }

    function HideFormAddNewIngredient() {
        document.getElementById("personal-recipes-add-ingredient-form").style.display = "none";
    }

    function ShowFormAddNewStep() {
        document.getElementById("personal-recipes-add-step-form").style.display = "flex";
    }

    function HideFormAddNewStep() {
        document.getElementById("personal-recipes-add-step-form").style.display = "none";
    }

    function ShowMenu() {
        document.getElementById("mobile-popup-menu").style.display = "flex";
    }

    function HideMenu() {
        document.getElementById("mobile-popup-menu").style.display = "none";
    }

    <?php 
    if(isset($_POST["add-new-recipe"]))
    {
        if(empty($_POST['title-input']) || empty($_POST["image-input"])){
            echo 'ShowFormRecipeCreation();';
            echo 'document.getElementById("empty-field-form-add-recipe").style.display = "block";';
        }
    }
    ?>
</script>