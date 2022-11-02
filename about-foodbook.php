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
                
                    <img class="image-about-page" src="data:image/jpeg;base64,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">
                    <div class="message-about-us">
                        <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aliquam enim eligendi blanditiis molestias, soluta numquam quibusdam vitae aliquid tenetur, 
                        quo sapiente iste illum eius neque fugit dolores corrupti voluptatum sit!
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nobis amet, impedit consequuntur at repellat repudiandae 
                        cum incidunt iste distinctio placeat ut nesciunt possimus officiis vel perspiciatis, inventore quod in doloribus.
                        </p>
                        <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis animi veniam sequi magni, praesentium tenetur deleniti iste quae asperiores! 
                        Ipsam rerum quas, totam illum repellat quisquam dolorem soluta non reiciendis!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex est iste expedita quod aliquam suscipit blanditiis, architecto, accusantium eaque debitis doloremque tempore,
                         aliquid consequatur? Dolor quia ipsam deleniti itaque ad.
                        </p>

                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque amet illum excepturi, ipsa sapiente perferendis maxime delectus eligendi fugiat facere laborum, 
                            enim assumenda molestiae voluptatibus, vitae voluptate recusandae ex. Commodi.
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, nihil perspiciatis suscipit qui optio nostrum maiores! Dignissimos nulla perferendis totam
                             quod dolorum ea similique? Beatae, quisquam. Error, eum modi. Aliquam.
                        </p>
                    </div>
                    
            </div>
            
    
        </div>





<?php GenerateFooter(); ?>
</body>