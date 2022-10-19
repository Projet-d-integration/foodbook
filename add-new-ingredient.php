<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }
?>

<head>
    <title>Ajouter Ingredient</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/add-new-ingredient.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'styles/ui-kit.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
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
            else if(!ValidateNameInput($nameIngredient)) {
                echo '
                <script>
                    window.onload = () => { document.getElementById("error_name").style.display = "block"; }
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

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Ajouter Ingredient </div>
        <div class="svg-wrapper">
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
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
        <?php 
            $tabTypeIngredient = TypeIngredientInfo();
            echo '<form><div class="item-wrapper"><div class="return-button">'.GenerateButtonTertiary("Retour", "inventory.php").'</div></form>';
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
