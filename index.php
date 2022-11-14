<?php 
    session_start(); 
    if(array_key_exists('buttonDeconnecter', $_POST)) {
        session_destroy();
        header('Location: index.php');
    }
?>
 
<head>
    <title>Accueil Foodbook</title>
    
    <meta charset="utf-8" name="viewport" content="width=device-width" />
    
    <style>
        <?php require 'styles/index.css'; ?>
        <?php require 'scripts/db.php'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php RenderFavicon(); ?>
</head>

<body> 
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            AddAnimation();
    ?>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Accueil </div>
 
        <?php AddSearchBar(); ?>

        <div class="svg-wrapper">
            <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
            <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
            <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <?php 
                if(!empty($_SESSION['idUser'])){
                    echo '<a href="edit-profil.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                    // echo '<span style="color:white;padding-top:2%">'.User($_SESSION['idUser'])[2].'</span>'; //Afficher le nom
                    echo '<form method="post"><button type="submit" name="buttonDeconnecter" class="svg-button login-button" value="buttonDeconnecter" />'.file_get_contents("utilities/logout.svg").'</form>';
                }
                else{
                    echo '<a href="login.php" class="svg-button login-button"> '.file_get_contents("utilities/account.svg").'</a>';
                }
            ?>
        </div>
    </div>
</div>

<body> 
    <div class="separators">
        <?php 
            $tabTypeRecette = InfoTypeRecipe();
            foreach($tabTypeRecette as $typeIngredient){
                echo "
                    <a href='recipes-list.php?type=$typeIngredient[0]' class='separator'>
                        <img src='./utilities/$typeIngredient[2]' class='index-image'></img>
                        <div class='image-overlay'></div>
                        <div class='separator-overlay'>
                            <div class='separator-text'>$typeIngredient[1]</div>
                            <div class='separator-arrow'>". file_get_contents("utilities/caret.svg") ."</div>
                        </div>
                    </a>
                ";
            }
        ?>
    </div> 

    <?php GenerateFooter(); ?>
</body>