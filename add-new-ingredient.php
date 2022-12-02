<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }
?>

<head>
    <title>Ajouter Ingredient</title>
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    <style>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'styles/add-new-ingredient.css'; ?>
        <?php require 'styles/ui-kit.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if(!isset($_POST['name-input'])){ $nameIngredient = ""; } 
        else { $nameIngredient = $_POST['name-input']; } 
        
        if(!isset($_POST['description-input'])){ $description = ""; } 
        else { $description = $_POST['description-input']; } 

        if(!isset($_POST['type-input'])){ $type = ""; } 
        else { $type = $_POST['type-input']; } 

        // Vérifie si tous les champs ont été entrés.
        if(!empty($nameIngredient)
        && !empty($description)
        && !empty($type))
        {
            // Affiche un message d'erreur si le courriel est déjà utilisé
            if (IngredientExist($_POST['name-input'])){
                echo '
                <script>
                    window.onload = () => { document.getElementById("error_name_used").style.display = "block"; }
                </script>';
            }
            else {
                AddIngredient($_POST['name-input'],$_POST['description-input'],$_POST['type-input']);
            }
        }
        else {
            echo '
            <script>
                window.onload = () => { document.getElementById("error_entries").style.display = "block"; }
            </script>';
        }
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

<body> 
    <div class="wrapper">
        <div class="banner-title"><span>Ajouter un ingrédient</span></div>
        <?php 
            $tabTypeIngredient = TypeIngredientInfo();
        ?>
        <form method="post" class="create-ingredient-form">
            <input class="text-input" name="name-input" type="text" placeholder="Nom ingredient" value="<?= $name ?>">
            <input class="text-input" name="description-input" type="text" placeholder="Description" value="<?= $last_name?>">
            <select name="type-input">
                <?php 
                    foreach($tabTypeIngredient as $typeIngredient){
                        echo "<option value=$typeIngredient[0]>$typeIngredient[1]</option>";
                    }
                ?>
            </select>

            <div class="error_message" id="error_entries">Tous les champs sont obligatoires</div>
            <div class="error_message" id="error_name">Nom invalide : ne doivent que contenir des lettres</div>
            <div class="error_message" id="error_name_used">Nom invalide : cet ingredient existe déjà</div>

            <div class="buttons-wrapper">
                <input class="button button-primary" type="submit" value="Ajouter">
            </div>
        </form>
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