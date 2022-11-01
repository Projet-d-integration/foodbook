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
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/personal-recipes.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
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
                if(!empty($_SESSION['idUser'])) {
                    echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                    echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                }
                else {
                    echo '<a href="login.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                }
            ?>
        </div>
    </div>

    <div class="wrapper">
        <div class="personal-recipes-wrapper">
            <div class="add-new-recipe" id="add_new_recipe" onclick='ShowFormRecipeCreation()'>Ajouter un emplacement</div>
            <div class="neutral_message" id="error_no_recipes">Vous n'avez pas de recettes pour l'instant.</div>

            <div class="recipe-creation-form"></div>
        </div>

        <div class="personal-recipes-add-recipe-form" id="personal-recipes-add-recipe-form">
            <div class="transparent-background">
                <form method="post" class="form-content">
                    <div class="form-content-wrapper">
                        <div class="form-exit" onclick='HideFormRecipeCreation()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                        <div class="recipe-form-info"></div>
    
                        <div class="recipe-form-image-input">
                            <label for="image-input">Url de l'image</label>
                            <input type="text" name="image-input" class="text-input">
                        </div>
    
                        <div class="recipe-form-title-input">
                            <label for="title-input">Title</label>
                            <input type="text" name="title-input" class="text-input">
                        </div>

                        <div class="recipe-form-ingredients">
                            <?php
                                // $nbIngredients <= 0
                                if (true) {
                                    echo '<div class="neutral_message" style="display: flex"> Vous n\'avez présentement aucun ingrédient dans votre recette </div>';
                                }
                                
                                // add all ingredients already added here
                            ?>
                            <div class="button button-primary add-new-ingredient" id="add_new_ingredient" onclick='ShowFormAddNewIngredient()'>Ajouter un ingrédient</div>
                        </div>
                        
                        <div class="recipe-form-steps">
                            <?php 
                                // $nbSteps <= 0
                                if (true) {
                                    echo '<div class="neutral_message" style="display: flex"> Vous n\'avez présentement aucun ingrédient dans votre recette </div>';
                                }
                                
                                // add all ingredients already added here
                            ?>
                            <div class="button button-primary add-new-step" id="add_new_step" onclick='ShowFormAddNewStep()'>Ajouter une étape</div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

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
            
    </div>
    <?php GenerateFooter(); ?>
</body>


<script>
window.onload = () => {
    <?php
        if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
            echo 'document.getElementById("add_new_recipe").style.display = "block";';

            // Vérfie la quantité de recettes de l'utilisateur, et affiche un message
            // $tabRecettes = ShowRecipe($_SESSION['idUser']);
            // $numTabRecette = count($tabRecettes);
            // $tabRecettes <= 0
            if(true) {
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