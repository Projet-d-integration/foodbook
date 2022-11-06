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
    
    <?php RenderFavicon(); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Vos Recettes </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

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
                                AddInfoRecipe($idRecette,$_POST['image-input'],$_POST['video-input']) ;
                                ChangePage("personal-recipes.php");
                            }else{
                                echo AddRecipe($_SESSION['idUser'],$_POST['title-input'],0,0,date('Y-m-d H:i:s'),$_POST['type-input']);
                                $idRecette = LastInsertedRecipe();
                                AddInfoRecipe($idRecette,$_POST['image-input'],$_POST['video-input']) ;
                                ChangePage("personal-recipes.php");
                            }
                        }
                    }
            ?>
        </div>
    </div>

    <div class="wrapper">
        <div class="personal-recipes-wrapper">
            <div class="add-new-recipe" id="add_new_recipe" onclick='ShowFormRecipeCreation()'>Ajouter une recette</div>
            <?php 
                $tabRecette = ShowRecipe($_SESSION['idUser']);
                echo '
                    <form method="post">
                    <div class="space-grid">';
                    foreach($tabRecette as $recette){
                        echo "<a class='space-div' href='recipe.php?recette=$recette[0]' type='submit' name='buttonSpace' value='$recette[0]'> $recette[2] <div class='space-div-arrow'>". file_get_contents("utilities/caret.svg") ."</div> </a>";
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
                        <div class="recipe-form-title">Ajouter Une recette</div>
    
                        <div class="recipe-form-image-input">
                            <label for="image-input">Url de l'image</label>
                            <input type="text" name="image-input" class="text-input">
                        </div>
    
                        <div class="recipe-form-title-input">
                            <label for="title-input">Title</label>
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
                            <label for="video-input">Ajouter un vidéo</label>
                            <input type="text" name="video-input" class="text-input">
                        </div>
                        
                        <div class="recipe-form-private-input">
                            <div>Rendre la recette publique</div>
                            <input type="checkbox" name="isPublic">
                        </div>

                        <input type="submit">
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


<script>
    window.onload = () => {
        <?php
            if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
                echo 'document.getElementById("add_new_recipe").style.display = "block";';

                $tabRecette = ShowRecipe($_SESSION['idUser']);
                
                if(!(count($tabRecette) > 0)) {
                    echo 'document.getElementById("error_no_recipes").style.display = "block";';
                }
            }
        ?>
    }

    function ShowFormRecipeCreation() {
        document.getElementById("personal-recipes-add-recipe-form").style.display = "block";
    }

    function HideFormRecipeCreation() {
        document.getElementById("personal-recipes-add-recipe-form").style.display = "none";
    }

    function ShowFormAddNewIngredient() {
        document.getElementById("personal-recipes-add-ingredient-form").style.display = "block";
    }

    function HideFormAddNewIngredient() {
        document.getElementById("personal-recipes-add-ingredient-form").style.display = "none";
    }

    function ShowFormAddNewStep() {
        document.getElementById("personal-recipes-add-step-form").style.display = "block";
    }

    function HideFormAddNewStep() {
        document.getElementById("personal-recipes-add-step-form").style.display = "none";
    }
</script>