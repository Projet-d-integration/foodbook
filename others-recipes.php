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
    <title>Recettes des autres usagers</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/others-recipes.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title">Recettes de 


            <?php //Nom du user à qui appartient les recettes 
                $tabRecette = ShowRecipe($_SESSION['idUser']);
                echo'<form method="post">
                        <div class="space-grid">';
                            foreach($tabRecette as $recette){
                                echo "<a class='space-div' href='recipe.php?id=$recette[0]' type='submit' name='buttonSpace' value='$recette[0]'> $recette[2] <div class='space-div-arrow'>". file_get_contents("utilities/caret.svg") ."</div> </a>"; 
                            }
                   echo'</div>
                    </form>';
            //Import le style de REcipies LIst(copier le div qui fait laffichage), implementer la logique de personal recipes (le for each)
            ?> 
        </div>

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
                    /*if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                    }*/
            ?>
        </div>
    </div>

    <div class="wrapper">
        <div class="others-recipes-wrapper">
            <?php 
                $tabRecette = []; //Faire une méthode ShowRecipeOtherUser($id_user) pour afficher les recette de cet usager.
                    echo '
                    <form method="post">
                    <div class="space-grid">';
                    foreach($tabRecette as $recette){
                        echo "<a class='space-div' href='recipe.php?recette=$recette[0]' type='submit' name='buttonSpace' value='$recette[0]'> $recette[2] <div class='space-div-arrow'>". file_get_contents("utilities/caret.svg") ."</div> </a>";
                        //S'assurer que dans le echo la variable $recette[n] affiche les bonnes informations au bon endroit en fonction du chiffre dans la parenthèse
                    }
                echo'</div>
            </form>';
                
            
            ?>
            <div class="neutral_message" id="error_user_no_recipes">Cet usager n'a aucune recette pour le moment.</div>
        </div>

    </div>   
    <?php GenerateFooter(); ?>
</body>


<script>
    window.onload = () => {
        <?php
            if(!($_SERVER['REQUEST_METHOD'] === 'POST')) {
                $tabRecette = []; //Appeler la méthode ShowRecipeOtherUser($id_user) pour afficher les recette de cet usager.
                
                if(count($tabRecette) == 0)
                {
                    echo 'document.getElementById("error_user_no_recipes").style.display = "block";';
                }
            }
        ?>
    }
</script>