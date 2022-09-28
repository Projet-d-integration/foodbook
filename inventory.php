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
    <title>Inventaire</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/inventory.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'styles/ui-kit.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    <?php RenderFavicon(); ?>
</head>

<?php
    // Message d'erreur pour le form
    if (1 == 2){
        echo '
        <script>
            window.onload = () => { document.getElementById("error_no_location").style.display = "block"; }
        </script>';
    }
?>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Inventaire </div>
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
        <div class="inventory-wrapper">
            <?php 
                if(!($_SERVER['REQUEST_METHOD'] === 'POST')){
                    $tabInfoSpace = InfoPlace();
                    echo '<form method="post">';
                    echo '<div class="space-grid">';
                    foreach($tabInfoSpace as $space){
                        echo "<button class='space-div' type='submit' name='buttonSpace' value='$space[0]'>$space[1]</button>";
                    }
                    echo '</div>';
                    echo '</form>';
                }else if(!empty($_POST['ingredient-input'])){
                    AddIngredientInventory(intval($_SESSION['idUser']),intval($_POST['ingredient-input']),intval($_POST['number-input']),intval($_POST['place-input']));
                    echo "<script>window.location.href = window.location.href;</script>";
                }else if(!empty($_POST['buttonSpace'])){
                    echo '<form><div class="item-wrapper dark-background"><a href="inventory.php" class="button button-secondary">Retour</a></div></form>';
                    $spaceChosen = $_POST['buttonSpace'];
                    echo "<div class='button button-primary' onclick='ShowForm()'>Ajouter un ingredient</div>";
                    $tabInventaire = UserInventoryInfo($_SESSION['idUser']);
                    echo '<ul>';
                    foreach($tabInventaire as $ingredientInventaire){
                        $ingredientInfo = SingleIngredientInfo($ingredientInventaire[2]);
                        if($ingredientInventaire[3] == $_POST['buttonSpace']){
                            echo "<li>$ingredientInfo[1]</li>";
                        }
                    }
                    echo '</ul>';
                }
            ?>
            <div class="neutral_message" id="error_no_location">Pour visionner et classer vos items, veuillez créer un emplacement.</div>
            <div class="inventory-location-form" id="inventory-location-form">
                <div class="transparent-background">
                    <div class="location-form-content">
                        <?php
                            $tabIngredient = AllIngredientInfo(); // [1] == nom
                            $idEmplacement = $_POST['buttonSpace'];
                            echo '<div class="space-grid">';
                            foreach($tabIngredient as $singleIngredient){
                                echo "<div class='space-div'>";
                                echo '<form method="post">';
                                echo "<button type='submit' name='ingredient-input' value=$singleIngredient[0]>$singleIngredient[1]</button><br>";
                                echo '<input type="number" name="number-input" min="1" max="100" placeholder="Cb" value = 0>';
                                echo "<input type='hidden' name='place-input' value=$idEmplacement>";
                                echo '</form>';
                                echo '</div>';
                            }
                            echo '</div>';
                        ?>
                        <form>
                            <div class="item-wrapper dark-background">
                                <a href="add-new-ingredient.php" class='button button-secondary'>Ajouter un nouveau ingredient</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php GenerateFooter(); ?>
</body>

<script>
    function ShowForm(){
        document.getElementById("inventory-location-form").style.display = "block";
    }
</script>