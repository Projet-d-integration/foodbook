<?php
session_start();
if (array_key_exists('buttonDeconnecter', $_POST)) {
    session_destroy();
    header('Location: index.php');
}

?>

<head>
    <title> Recette Foodbook</title>

    <meta charset="utf-8">

    <style>
        <?php require 'styles/recipe.css'; ?>
        <?php require 'scripts/db.php'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/filter.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php
    if (!($_SERVER['REQUEST_METHOD'] === 'POST')) {
        $recette =  ShowSingleRecipe($_GET['id'])[0];
        $infoRecette = InfoRecipeByID($recette[0])[0];
    } else {
        $recette = ShowSingleRecipe($_POST['id'])[0];
        $infoRecette = InfoRecipeByID($recette[0])[0];
    }
    ?>
    <?php RenderFavicon(); ?>
</head>

<body>
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            AddAnimation();
            if(!empty($_POST['ingredient-input'])){
                AddItemToRecipe($_POST['number-input'],$_POST['id'],$_POST['ingredient-input']);
            }
            else if(!empty($_POST['edit-ingredient'])){
                ModifyItemsRecipe($_POST['qteChosen'],$_POST['id'],$_POST['ingredient']);
            }
            else if(!empty($_POST['del-ingredient'])){
                DeleteItemFromRecipe($_POST['id'],$_POST['ingredient']);
            }
            else if(!empty($_POST['instruction-input'])){
                AddInstruction($_POST['id'],$_POST['instruction-input']);
            }
            else if(!empty($_POST['edit-instruction'])){
                ModifyInstruction($_POST['edit-instruction'],$_POST['id-instruction']);
            }
            else if(!empty($_POST['del-instruction'])){
                DeleteInstruction($_POST['instruction']);
            }
        }
    ?>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> <?php echo $recette[2]; ?> </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <div class="svg-wrapper">
            <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
            <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php
            if (!empty($_SESSION['idUser'])) {
                echo '<a href="edit-profil.php" class="svg-button login-button"> ' . file_get_contents("utilities/account.svg") . '</a>';
                echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />' . file_get_contents("utilities/logout.svg") . '</form>';
            } else {
                echo '<a href="login.php" class="svg-button login-button"> ' . file_get_contents("utilities/account.svg") . '</a>';
            }
            ?>
        </div>
    </div>

    <div class="recipe-container">
        <div class="recipe-header">
            <div class="recipe-image">
                <img src='<?= $infoRecette[5] ?>' title='<?= $recette[2] ?>'>
            </div>

            <div class="recipe-title">
                <?= $recette[2] ?>
            </div>
        </div>
        <div class="recipe-ingredients">
            <div class="checkboxes-container">
                <?php
                
                    $tabIngredientRecipe = InfoItemRecipe($recette[0]);
                    
                    if (count($tabIngredientRecipe) == 0) {
                        echo '<div class="neutral-message" style="display: flex"> Il n\'y a aucun ingrédient dans cette recette présentement</div>';
                    } 
                    foreach ($tabIngredientRecipe as $ingredient) {
                        $infoIngredient = SingleIngredientInfo($ingredient[2]);
                        echo "<div class='recipe-ingredient'>";
                        if (!($_SESSION['idUser'] == $recette[1])) {
                            echo "<input type='checkbox' class='recipe-ingredient-content'/>";
                        }
                        if ($_SESSION['idUser'] == $recette[1])
                            echo "<Form method='post'><input style='width:10%' type='number' name='qteChosen' min='1' value='$ingredient[0]' class='recipe-ingredient-content'>";
                        else
                            echo "<input style='width:5%' type='number' name='qteChosen' min='1' value='$ingredient[0]' class='recipe-ingredient-content' readonly>";
                        echo "<div class='recipe-ingredient-content'>$infoIngredient[1]</div>";
                        if ($_SESSION['idUser'] == $recette[1]){ 
                            echo "<input type='hidden' name='id' value='$recette[0]'>";
                            echo "<input type='hidden' name='ingredient' value='$infoIngredient[0]'>";
                            echo "<button type='submit' name='edit-ingredient' value='true' class='recipe-ingredient-content'>Modifier</button></form>";
                            echo "<form method='post'>
                                <button type='submit' name='del-ingredient' value='1'>Supprimer</button>
                                <input type='hidden' name='id' value='$recette[0]'>
                                <input type='hidden' name='ingredient' value='$infoIngredient[0]'>
                            </form>";
                        }
                        echo "</div>";
                    }
                    if ($_SESSION['idUser'] == $recette[1]) // || $_SESSION['idUser'] == table Admin
                    {
                        echo "<div class='button button-primary add-new-ingredient' id='add_new_ingredient' onclick='ShowFormItems()'>Ajouter un ingrédient</div>";
                    }
                    
                ?>
            </div>
        </div>

        <div class="recipe-steps">
            <div class="checkboxes-container">
                <?php
                $tabInstruction = InfoInstruction($recette[0]);
                // $nbSteps <= 0
                if (count($tabInstruction) == 0) {
                    echo '<div class="neutral-message" style="display: flex"> Il n\'y a aucune étape dans cette recette présentement </div>';
                } 
                $cptInstruction = 1;
                foreach ($tabInstruction as $instruction) {
                    echo "<div class='recipe-ingredient'>";
                    if ($_SESSION['idUser'] == $recette[1]){ // || $_SESSION['idUser'] == table Admin
                        echo "<form method='post'><span>$cptInstruction - </span>";
                        echo "<input type='text' name='edit-instruction' value='$instruction[2]'>";
                        echo "<input type='hidden' name='id' value='$recette[0]'>";
                        echo "<input type='hidden' name='id-instruction' value='$instruction[0]'>";
                        echo "<button type='submit' class='recipe-ingredient-content'>Modifier</button></form>";
                        echo "<form method='post'>
                            <button type='submit' name='del-instruction' value='1'>Supprimer</button>
                            <input type='hidden' name='id' value='$recette[0]'>
                            <input type='hidden' name='instruction' value='$instruction[0]'>
                        </form>";
                    }else{
                        echo "<input type='checkbox' class='recipe-ingredient-content'/>";
                        echo "<span>$cptInstruction - </span>";
                        echo "<span>$instruction[2]</span>";
                    }
                    echo "</div>";
                    $cptInstruction++;
                }
                foreach ($tabEtape as $etape) {
                    echo "<div class='recipe-step'>";
                    if ($_SESSION['idUser'] == $recette[1]) {
                        echo "<div class='recipe-remove-item'>" . file_get_contents('utilities/x-symbol.svg') . "</div>";
                    }
                    echo "<div>Étape</div></div>";
                }
                
                if ($_SESSION['idUser'] == $recette[1])
                    echo "<div class='button button-primary add-new-step' id='add_new_step' onclick='ShowFormInstruction()'>Ajouter une étape</div>";
                ?>
            </div>
        </div>

        <a href="<?= $infoRecette[6] ?>" class="recipe-video">
            Vidéo tutoriel
        </a>
        <div class="recipe-save-like">
            <div class="interractible-svg">
                <?php echo file_get_contents("utilities/like.svg"); ?>
            </div>

            <div class="interractible-svg">
                <?php echo file_get_contents("utilities/floppy-disk.svg"); ?>
            </div>
        </div>

        <div class="publisher-info">
            <?= $infoRecette[2] ?>
        </div>

        <div class="recipe-comments">
            Section commentaires
        </div>
    </div>
    
    <div class="inventory-form" id="inventory-items-form">
        <div class="transparent-background">
            <div class="items-form-content">
                <div class="form-exit" onclick='HideFormItems()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                <div class="items-form">
                    <?php
                    // Formulaire de tri
                    if ($_POST['filter'])
                        echo '<script>document.getElementById("inventory-items-form").style.display = "block";</script>';
                    $idEmplacement = $recette[0];
                    $tabTypeIngredient = TypeIngredientInfo();
                    $nameSearched = $_POST['name-input'];
                    echo "<form class='display-filter-section' method='post'>";
                    echo "<input class='text-input' name='name-input' type='text' placeholder='Nom ingredient' value=$nameSearched >";
                    echo '<select name="type-input">';
                    echo '<option value="">Tout les types</option>';
                    foreach ($tabTypeIngredient as $typeIngredient)
                        echo "<option value=$typeIngredient[0]>$typeIngredient[1]</option>";
                    echo '</select>';
                    echo '<select name="order-input">';
                    echo '<option value="ASC">Croissant</option>';
                    echo '<option value="DESC">Décroissant</option>';
                    echo '</select>';
                    echo '<input type="hidden" value="set" name="filter">';
                    echo "<input type='hidden' value='$idEmplacement' name='buttonSpace'>";
                    echo "<input type='hidden' name='id' value='$recette[0]'>";
                    echo '<div class="buttons-wrapper"><input class="button button-primary" type="submit" value="Chercher"></div>';
                    echo "</form>";
                    // Trier les informations
                    
                    $tabIngredient = AllIngredientInfo($_POST['order-input']); // [1] == nom
                    $tabIngredient = FilterIngredient($tabIngredient, $_POST['name-input'], $_POST['type-input']);
                    //Aficher les informations
                    foreach ($tabIngredient as $singleIngredient) {
                        // Empeche un ingredient d'apparaitre dans la liste de choix si il existe deja dans la liste
                        $isAlreadyInList = false;
                        foreach ($tabIngredientRecipe as $infoInventaire) {
                            if ($infoInventaire[2] == $singleIngredient[0]) {
                                $isAlreadyInList = true;
                            }
                        }
                        if (!$isAlreadyInList) {
                            echo "
                                    <div class='inventory-item' onclick='ShowFormItemQuantity($singleIngredient[0])'> $singleIngredient[1] </div>
                                    <form method='post' class='inventory-item-form' id='inventory-item-form-$singleIngredient[0]'>
                                        <div class='items-form-overlay'>
                                            <div class='form-exit-item' onclick='HideFormItemQuantity($singleIngredient[0])'>";
                            echo file_get_contents('utilities/x-symbol.svg');
                            echo " </div>
                                            <span class='inventory-items-form-title'>Combien voulez vous ajouter de cet item : $singleIngredient[1] </span>
                                            <input type='number' name='number-input' min='1' max='100' placeholder='Cb' value = 0> <br>
                                            <input type='hidden' name='place-input' value='$idEmplacement'>
                                            <input type='hidden' name='id' value='$recette[0]'>
                                            <input type='hidden' value='$spaceChosen' name='buttonSpace'>
                                            <button type='submit' class='button button-secondary' name='ingredient-input' value='$singleIngredient[0]'>Ajouter</button><br>
                                        </div>
                                    </form>";
                        }
                    }
                    ?>
                    <div class="items-form-submit">
                        <form> <?php GenerateButtonPrimary("Ajouter un nouvel ingredient inexistant", "add-new-ingredient.php") ?></form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="inventory-form" id="instruction-form">
            <div class="transparent-background">
                <div class="items-form-content">
                    <div class="form-exit" onclick='HideFormInstruction()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                    <div class="instruction-form">
                        <form method="POST">
                            <span class='inventory-items-form-title'>Nouvelle Instruction </span>
                            <br>
                            <input type="text" placeholder="Inscrire la nouvelle instruction.." name="instruction-input" max="350" />
                            <?php echo "<input type='hidden' name='id' value='$recette[0]' />"; ?>
                            <br>
                            <div class="items-form-submit">
                                <button type='submit' class='button button-secondary' name='add-instruction'>Ajouter</button>
                                <br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
    <?php GenerateFooter(); ?>
</body>

<script>
    function ShowFormInstruction() {
        document.getElementById("instruction-form").style.display = "block";
    }

    function HideFormInstruction() {
        document.getElementById("instruction-form").style.display = "none";
    }

    function ShowFormItems() {
        document.getElementById("inventory-items-form").style.display = "block";
    }

    function HideFormItems() {
        document.getElementById("inventory-items-form").style.display = "none";
    }

    function ShowFormItemQuantity(id) {
        document.getElementById("inventory-item-form-" + id).style.display = "flex";
    }

    function HideFormItemQuantity(id) {
        document.getElementById("inventory-item-form-" + id).style.display = "none";
    }
</script>