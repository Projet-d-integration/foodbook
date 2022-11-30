<?php
session_start();
if (array_key_exists('buttonDeconnecter', $_POST)) {
    session_destroy();
    header('Location: index.php');
}

?>

<head>
    <title> Recette Foodbook</title>
    
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        <?php require 'styles/recipe.css'; ?>
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            AddAnimation();
            if(!empty($_POST['ingredient-input'])){
                AddItemToRecipe($_POST['number-input'],$_POST['id'],$_POST['ingredient-input'],$_POST['metrique-input']);
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
            }else if(!empty($_POST['title-input'])){
                ModifyNameRecipe($_POST['id'],$_POST['title-input']);
            }else if(!empty($_POST['image-input'])){
                ModifyImageInfoRecipe($_POST['id'],$_POST['image-input']);
            }else if(!empty($_POST['description-input'])){
                ModifyDescriptionInfoRecipe($_POST['id'],$_POST['description-input']);
            }else if(!empty($_POST['idCompte-comment-remove'])){
                DeleteCommentaryEvaluation($_POST['id'],$_POST['idCompte-comment-remove']);
            }else if(!empty($_POST['modify-comment'])){
                echo ModifyCommentaryEvaluation($_POST['modify-eval'],$_POST['modify-comment'],$_POST['id'],$_SESSION['idUser']);
            }
            $recette = ShowSingleRecipe($_POST['id'])[0];
            $infoRecette = InfoRecipeByID($recette[0])[0];
        }
        else{
            $recette =  ShowSingleRecipe($_GET['id'])[0];
            $infoRecette = InfoRecipeByID($recette[0])[0];
        }
    ?>
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

    <div class="recipe-container wrapper">
        <div class="recipe-header">
            <div class="recipe-image">
                <img src='<?= $infoRecette[5] ?>' title='<?= $recette[2] ?>'>
            </div>

            <div class="recipe-title">
                <?php
                    if($_SESSION['idUser'] == $recette[1]){
                        echo "<form method='post' class='title-wrapper'>
                                <input type='text' name='title-input' class='title-input' value='$recette[2]'/>
                                <input type='hidden' name='id' value='$recette[0]'>
                                <button type='submit' class='modify-button'>" . file_get_contents('utilities/notebook.svg') . "</button>
                            </form>";
                    }else{
                        echo "$recette[2]";
                    }
                    
                    echo" 
                    <div class='lower-title'>
                        <div> " . $infoRecette[1] . file_get_contents('utilities/people.svg') . "</div>
                        <div> " . $infoRecette[0] . file_get_contents('utilities/time.svg') . "</div>
                    </div>";
                ?>
            </div>
        </div>
        <div>
            <?php
                $userRecette = User($recette[1]);
                echo "<a href='others-recipes.php?user=$recette[1]'>Autres recettes de $userRecette[2] $userRecette[1]</a>";
            ?>
        </div>
        <?php
            if($_SESSION['idUser'] == $recette[1]){
                echo "<form method='post' class='image-form'>
                        <label name='image-input'>Url de l'image :</label><input type='text' name='image-input' class='image-input' value='$infoRecette[5]'/>
                        <input type='hidden' name='id' value='$recette[0]'>
                        <button type='submit' class='modify-button'>" . file_get_contents('utilities/notebook.svg') . "</button>
                    </form>";
            }
        ?>
        <div class="publisher-info">
                <?php 
                    if($infoRecette[2] == "")
                        $desc = "Aucune description à été rajouté pour le moment!";
                    else
                        $desc = $infoRecette[2];
                    if($_SESSION['idUser'] == $recette[1]){
                        echo "<form method='post' class='description-form'>
                                <input type='text' class='description-recette' name='description-input' value='$desc'/>
                                <input type='hidden' name='id' value='$recette[0]'>
                                <button type='submit' class='modify-button'>" . file_get_contents('utilities/notebook.svg') . "</button>
                            </form>";
                    }else{
                        echo "$desc";
                    }
                ?>
        </div>
        <div class="recipe-ingredients-wrapper">
            <div class="checkboxes-container">
                <?php
                    $tabIngredientRecipe = InfoItemRecipe($recette[0]);
                    
                    if (count($tabIngredientRecipe) == 0) {
                        echo '<div class="neutral-message" style="display: flex"> Il n\'y a aucun ingrédient dans cette recette présentement</div>';
                    } 
                    echo"<table class='form-ingredient-wrapper'>";
                    foreach ($tabIngredientRecipe as $ingredient) {
                        echo "<tr id='table-row$ingredient[2]'>";
                        $infoIngredient = SingleIngredientInfo($ingredient[2]);
                        echo "<div class='recipe-ingredient'>";
                        if (!($_SESSION['idUser'] == $recette[1])) {
                            echo "<td class='table-checkbox'><input onChange=' AddStyleWhenChecked($ingredient[2])' type='checkbox' id='checkbox-ingred-recipe$ingredient[2]' class='recipe-ingredient-content'/></td>";
                        }
                        echo "<td id='name-ingred-table$ingredient[2]' class='table-name'table-name>$infoIngredient[1]</td>";
                        if ($_SESSION['idUser'] == $recette[1])
                            echo "<td class='table-number'><form method='post'><input style='width:10%' type='number' name='qteChosen' min='1' value='$ingredient[0]' class='recipe-ingredient-content'></td>";
                        else
                            echo "<td id='num-ingred-table$ingredient[2]' class='table-number'><input style='width:10%' type='number' name='qteChosen' min='1' value='$ingredient[0]' class='recipe-ingredient-content' readonly></td>";
                        echo "<td>$ingredient[3]</td>";
                        if ($_SESSION['idUser'] == $recette[1]){ 
                            echo "<input type='hidden' name='id' value='$recette[0]'>";
                            echo "<input type='hidden' name='ingredient' value='$infoIngredient[0]'>";
                            echo " <td class='table-modify'><button type='submit' name='edit-ingredient' value='true' class='recipe-ingredient-content modify-button'>".file_get_contents("utilities/notebook.svg")."</button></form></td>";
                            echo "<form method='post'>
                            <td class='table-remove'> <button type='submit' name='del-ingredient' value='1' class='x-button'>".file_get_contents("utilities/x-symbol.svg")."</button></td>
                                <input type='hidden' name='id' value='$recette[0]'>
                                <input type='hidden' name='ingredient' value='$infoIngredient[0]'>
                            </form>";
                        }
                        echo "</tr>";
                        echo "</div>";
                        
                    }
                    echo"</table>";
                    if ($_SESSION['idUser'] == $recette[1]) // || $_SESSION['idUser'] == table Admin
                    {
                        echo "<div class='button button-primary add-new-ingredient' id='add_new_ingredient' onclick='ShowFormItems()'>Ajouter un ingrédient</div>";
                    }
                ?>
            </div>
        </div>

        <div>
            <div class="checkboxes-container">
                <?php
                $tabInstruction = InfoInstruction($recette[0]);
                // $nbSteps <= 0
                if (count($tabInstruction) == 0) {
                    echo '<div class="neutral-message" style="display: flex"> Il n\'y a aucune étape dans cette recette présentement </div>';
                } 
                $cptInstruction = 1;
                echo "<table class='form-steps-wrapper'>";
                foreach ($tabInstruction as $instruction) {
                    echo"<tr id='table-steps-row$instruction[0]'>";
                    echo "<div class='recipe-ingredient-wrapper'>";
                    if ($_SESSION['idUser'] == $recette[1]){ // || $_SESSION['idUser'] == table Admin
                        echo "<td class='table-count-instruction'><form method='post'><span>$cptInstruction - </span></td>";
                        echo "<td class='table-steps'><textarea max='350' class='textearea-step' name='edit-instruction' value='$instruction[2]'>$instruction[2]</textarea></td>";
                        echo "<input type='hidden' name='id' value='$recette[0]'>";
                        echo "<input type='hidden' name='id-instruction' value='$instruction[0]'>";
                        echo "<td class='table-modify'><button type='submit' class='recipe-ingredient-content modify-button'>".file_get_contents("utilities/notebook.svg")."</button></form></td>";
                        echo "<form method='post'>
                            <td class='table-remove'><button type='submit' name='del-instruction' class='x-button' value='1'>".file_get_contents("utilities/x-symbol.svg")."</button></td>
                            <input type='hidden' name='id' value='$recette[0]'>
                            <input type='hidden' name='instruction' value='$instruction[0]'>
                        </form>";
                    }else{
                        echo "<td class='table-checkbox'><input type='checkbox' onChange='AddStyleWhenCheckedStep($instruction[0])' id='checkbox-steps$instruction[0]' class='recipe-ingredient-content'/> <span>$cptInstruction - </span></td>";
                        echo "<td id='step-value$instruction[0]' colspan=3 class='table-steps'><span>$instruction[2]</span></td>";
                    }
                    echo "</div>";
                    $cptInstruction++;
                    echo "</tr>";
                }
                echo "</table>";
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
        <?php 
            if(!($infoRecette[6] == "")){
                echo "<a href='$infoRecette[6]' class='recipe-video'>
                        Vidéo tutoriel
                    </a>";
            }
        ?>
        <?php 
            /* Ajouter verif si commentaire existe deja et si c lauteur*/
            if(empty($_SESSION['idUser'])){
                echo "<div class='add-comment-button' onclick='GoToLogin()'>Ajouter un commentaire" . file_get_contents('utilities/plus.svg') . "</div>";
            }else if($_SESSION['idUser'] != $recette[1] && !FindCommentaryEvaluation(intval($recette[0]),intval($_SESSION['idUser']))){
                echo "<div class='add-comment-button' onclick='ShowFormAddComments()'>Ajouter un commentaire" . file_get_contents('utilities/plus.svg') . "</div>";
            }
        ?>
        <div class="recipe-comments">

            <?php
                $tabCommentaire = ShowCommentaryEvaluation($recette[0]); //Faire méthode pour aller chercher tous les commentaire d'une recette en fonction de l'id de la recette
            
                foreach($tabCommentaire as $commentaire)
                {
                    //Faire afficher le nom de la personne qui a mis le commentaire et le commentaire lui-même
                    $userInfo = User($commentaire[3]);
                    if($_SESSION["idUser"] == $commentaire[3]) //Si le idCompte du commentaire est le même que le idUser
                    {
                        echo "<div>Votre commentaire :</div>";
                        echo "<form method='POST'><input type='hidden' name='id' value='$recette[0]'>";
                        echo "<input type='number' name='modify-eval' max='5' min='0' value='$commentaire[0]'></input>";
                        echo '<input type="text" name="modify-comment" value="'.$commentaire[1].'"></input>';
                        echo "<button name='modify-comment-button' value='1' type='submit' class='recipe-ingredient-content modify-button'>".file_get_contents("utilities/notebook.svg")."</button></form>";
                    }
                    else{
                        echo "<div>$userInfo[2] $userInfo[1] $commentaire[0]/5</div>";
                        echo "<div>$commentaire[1]</div>";
                    }
                    if($_SESSION["idUser"] == $commentaire[3] || $_SESSION["idUser"] == $recette[1]){
                        echo "<form method='post'>
                                <button type='submit' name='del-comment' class='x-button' value='1'>".file_get_contents("utilities/x-symbol.svg")."</button>
                                <input type='hidden' name='idCompte-comment-remove' value='$commentaire[3]'>
                                <input type='hidden' name='id' value='$recette[0]'>
                            </form>";
                    }
                }        
            ?>
            <!--Rajouter un if pour vérifier que le user a ajouter un seul commentaire si oui ne pas afficher le bouton ci-dessous, sinon l'afficher-->
        </div>

        <div class="comments-form" id="comments-form">
            <div class="transparent-background">
                <form method="post" class="form-content">
                    <div class="comments-form-title">Ajouter un commentaire</div>
                    <div class="form-exit" onclick='HideFormAddComments()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                    <?php 
                            echo  "<form method='POST'><input type='hidden' name='id' value='$recette[0]'>";
                            echo '
                                <input type="text" class="searchbar-input" name="comment-value" placeholder="Votre commentaire..." maxlength="100">
                                Évaluation: 
                                <div class="rating-wrapper">
                                    <input class="rating-input" type="radio" name="rating" id="r1" value="5">
                                    <label for="r1"></label>
                                    
                                    <input class="rating-input" type="radio" name="rating" id="r2" value="4">
                                    <label for="r2"></label>
                                    <input class="rating-input" type="radio" name="rating" id="r3" value="3">
                                    <label for="r3"></label>
                                    <input class="rating-input" type="radio" name="rating" id="r4"value="2">
                                    <label for="r4"></label>
                                    <input class="rating-input" type="radio" name="rating" id="r5" value="1">
                                    <label for="r5"></label>
                                </div>        
                                <input type="submit" class="button button-primary" name="addComments" value="Ajouter">
                                </form>
                            ';
                    ?>
                    <div class="error_message" id="comment-field-empty">Vous devez remplir le/les champs obligatoires.</div>
                </form>
            </div>
            <?php
                if(isset($_POST["addComments"]))
                {
                    if(empty($_POST["rating"]) && !empty($_POST["comment-value"]))
                    {
                        AddCommentaryEvaluation(0,$_POST['comment-value'],$recette[0],$_SESSION['idUser']);
                        echo "<script>window.location.href = 'recipe.php?id=$recette[0]';</script>";
                    }
                    else if(!empty($_POST["rating"]) && !empty($_POST["comment-value"]))
                    {
                        AddCommentaryEvaluation($_POST['rating'],$_POST['comment-value'],$recette[0],$_SESSION['idUser']);
                        echo "<script>window.location.href = 'recipe.php?id=$recette[0]';</script>";
                    }
                    else {
                        echo '<script>window.onload = () => { 
                            ShowFormAddComments();
                            document.getElementById("comment-field-empty").style.display = "block";
                            }</script>';
                    }
                }
            ?>
        </div>
    </div>
    
    <div class="inventory-form" id="inventory-items-form">
        <div class="transparent-background">
            <div class="items-form-content">
                <div class="form-exit-add-new-item" onclick='HideFormItems()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                <?php
                    // Formulaire de tri
                    if ($_POST['filter'])
                    echo '<script>document.getElementById("inventory-items-form").style.display = "block";</script>';
                    $idEmplacement = $_POST['buttonSpace'];
                    $tabTypeIngredient = TypeIngredientInfo();
                    $nameSearched = $_POST['name-input'];
                    echo "<form class='display-filter-section' method='post'>";
                    echo "<input class='inventory-text-input' name='name-input' type='text' placeholder='Nom ingredient' value=$nameSearched >";
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
                    echo '<div class="buttons-wrapper"><input class="button button-primary" type="submit" value="Chercher"></div>';
                    echo "</form>";
                ?>
                <div class="items-form">
                    <?php
                    // Trier les informations
                    $tabIngredient = AllIngredientInfo($_POST['order-input']); // [1] == nom
                    $tabIngredient = FilterIngredient($tabIngredient, $_POST['name-input'], $_POST['type-input']);
                    //Aficher les informations
                    foreach ($tabIngredient as $singleIngredient) {
                        // Empeche un ingredient d'apparaitre dans la liste de choix si il existe deja dans la liste
                        $isAlreadyInList = false;
                        foreach ($tabInventaire as $infoInventaire) {
                            if ($infoInventaire[3] == $singleIngredient[0]) {
                                $isAlreadyInList = true;
                            }
                        }
                        if (!$isAlreadyInList) {
                            echo "
                                <div class='inventory-item' onclick='ShowFormItemQuantity($singleIngredient[0])'> <span>$singleIngredient[1]</span> </div>
                                <form method='post' class='inventory-item-form' id='inventory-item-form-$singleIngredient[0]'>
                                    <div class='items-form-overlay'>
                                        <div class='form-exit-item' onclick='HideFormItemQuantity($singleIngredient[0])'>";
                            echo file_get_contents('utilities/x-symbol.svg');
                            echo " </div>
                                        <span class='inventory-items-form-title'>Combien voulez vous ajouter de cet item : $singleIngredient[1] </span>
                                        <input type='number' name='number-input' min='1' max='100' placeholder='Cb' value = 0> <br>
                                        <input type='hidden' name='place-input' value='$idEmplacement'>
                                        <input type='hidden' value='$spaceChosen' name='buttonSpace'>
                                        <button type='submit' class='button button-secondary' name='ingredient-input' value='$singleIngredient[0]'>Ajouter</button><br>
                                    </div>
                                </form>";
                        }
                    }
                    ?>
                </div>
                <div class="items-form-submit">
                    <form> <?php GenerateButtonPrimary("Ajouter un nouvel ingredient inexistant", "add-new-ingredient.php") ?></form>
                </div>
            </div>
        </div>
    </div>
    <div class="inventory-form" id="instruction-form">
        <div class="transparent-background">
            <div class="items-form-content">
                <div class="form-exit" onclick='HideFormInstruction()'> <?php echo file_get_contents("utilities/x-symbol.svg"); ?> </div>
                    <form method="POST" class="instruction-form">
                        <span class='inventory-items-form-title'>Ajout d'une nouvelle étape </span>
                        <br>
                        <input class="input-add-step" type="text"  placeholder="Inscrire la nouvelle instruction.." name="instruction-input" maxlength="350">
                        <?php echo "<input type='hidden' name='id' value='$recette[0]' />"; ?>
                        <br>
                        <div class="items-form-submit">
                            <button type='submit' class='button button-primary' name='add-instruction'>Ajouter</button>
                            <br>
                        </div>
                        <div id="empty-step-input" class="error_message">Veuillez remplir le champ obligatoire.</div>
                    </form> 
            </div>
        </div>
    </div>
    
    <?php GenerateFooter(); ?>
</body>

<script defer>
    function GoToLogin(){
        window.location.href = "login.php";
    }
    function ShowFormAddComments()
    {
        document.getElementById("comments-form").style.display = "block";
    }

    function HideFormAddComments()
    {
        document.getElementById("comments-form").style.display = "none";
    }

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
    
        function AddStyleWhenChecked(idIngred)
        {
            var checkBox = document.getElementById("checkbox-ingred-recipe" + idIngred);
            
            if(checkBox.checked)
            {
                document.getElementById("name-ingred-table" + idIngred).style.textDecoration = "line-through";
                document.getElementById("num-ingred-table" + idIngred).style.textDecoration = "line-through";
                document.getElementById("table-row" + idIngred).classList.add("table-changed-background");
            }
            else{
                document.getElementById("name-ingred-table" + idIngred).style.textDecoration = "none";
                document.getElementById("num-ingred-table" + idIngred).style.textDecoration = "none";
                document.getElementById("table-row" + idIngred).classList.remove("table-changed-background");
            }
        }
    function AddStyleWhenCheckedStep(idStep)
    {
        var checkbox = document.getElementById("checkbox-steps" + idStep);

        if(checkbox.checked)
        {
            document.getElementById("step-value" + idStep).style.textDecoration = "line-through";
            document.getElementById("table-steps-row" + idStep).classList.add("table-changed-background-steps");
        }
        else{
            document.getElementById("step-value" + idStep).style.textDecoration = "none";
            document.getElementById("table-steps-row" + idStep).classList.remove("table-changed-background-steps");
        }
    }

    <?php
        if($_POST['instruction-input'] == "" && isset($_POST["add-instruction"]))
        {
            echo 'ShowFormInstruction();';
            echo 'document.getElementById("empty-step-input").style.display = "block";';
        }
    ?>

    function ShowMenu() {
        document.getElementById("mobile-popup-menu").style.display = "flex";
    }

    function HideMenu() {
        document.getElementById("mobile-popup-menu").style.display = "none";
    }

</script>