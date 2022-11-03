<?php
 session_start(); 
 if(array_key_exists('buttonDeconnecter', $_POST)) {
     session_destroy();
     header('Location: index.php');
 }
?>

<head>
    <title>À propos de Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/about-page.css'; ?>
        <?php require 'styles/must-have.css'; ?>
        <?php require 'styles/ui-kit.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    <?php RenderFavicon(); ?>
</head>

<body>

        <div class="header-banner">
                <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
                <div class="banner-title">À propos de nous </div>
                <div class="svg-wrapper">
                    <a href="personal-recipes.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/book.svg"); ?> </a>
                    <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
                    <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
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


        <div class="main-wrapper">
            <div class="container-main-display">
                
                    <img class="image-about-page" src="https://img.freepik.com/vecteurs-libre/equipe-commerciale-mettant-ensemble-jigsaw-puzzle-isole-illustration-vectorielle-plane-partenaires-dessin-anime-travaillant-connexion-concept-travail-equipe-partenariat-cooperation_74855-9814.jpg">
                    <div class="message-about-us">
                        <h2>Qui sommes-nous ?</h2>
                        <p>
                        Notre site FoodBook réalisé par quatres jeunes étudiants travaillants et inventifs du Collège Lionel-Groulx est à la fois 
                        un gestionnaire de nourriture et de recettes ainsi qu'un réseau social hors du commun. Il est à seul but de faciliter la vie des
                        gens lorsqu'il est question d'alimentation car nous savons de nos jours que c'est un sujet qui peut être compliqué.
                        </p>
                        <p>
                        Pour ce qui est du réseau social, nous voulons que nos utilisateurs se sentent à l'aisent de publier, commenter et évaluer
                        les recettes des autres utilisateurs dans le plus grand respect. Nous voulons que cela puissent également créer des amitiés entre les utilisateurs
                        afin que notre site soit propice à un environnement agréable pour tous.    
                        </p>

                        <p>
                           Comme mentionné plutôt, nous sommes quatres étudiants du Collège Lionel-Groulx  qui se sont données coprs et âmes à la confection
                           de FoodBook. Étant tous jeunes et passionés d'informatique nous avons fait tout ce qui étaient en notre pouvoir grâce l'aide de nos connaissances et aptitudes, nous voulions simplement facilité et amélioré la vie des gens qui auraient de 
                           la difficulté à gérer leur nourriture ainsi que pouvoir créer des liens d'amitiés sain et affectueux. Nous vous remercions infiniement de faire confiance à notre site web
                           et espèrons que vous allez prendre plaisir à toutes ses fonctionnalités.
                        </p>
                    </div>
                    
            </div>
            
    
        </div>





<?php GenerateFooter(); ?>
</body>