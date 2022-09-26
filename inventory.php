<?php session_start(); ?>

<head>
    <title>Accueil Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/inventory.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<?php

    // If qte-emplacements == 0
    if (true){
        echo '
        <script>
            window.onload = () => { document.getElementById("error_no_location").style.display = "block"; }
        </script>';
    }
    ?>

<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Accueil </div>

        <div class="searchbar">
            <input type="text" class="searchbar-input" placeholder="type something"></input>
            <div class="search-icon"><?php echo file_get_contents("utilities/search.svg"); ?></div>
        </div>

        <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
        <a href="inventory.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
        <a href="login.php" class="svg-button account-button"> <?php echo file_get_contents("utilities/account.svg"); ?> </a>
    </div>

    <div class="wrapper">
        <div class="inventory-wrapper">
            <div class="neutral_message" id="error_no_location">Pour visionner et classer vos items, veuillez créer un emplacement.</div>

            <form class="inventory-location-form" id="inventory-location-form">
                <div class="transparent-background"></div>
                <div class="location-form-content">
                    test
                </div>
            </form>

            <div class="button button-primary" onclick="ShowForm()">Créer nouvel emplacement</div>
        </div>
    </div>

    <?php GenerateFooter(); ?>
</body>

<script>
    function ShowForm(){
        document.getElementById("inventory-location-form").style.display = "block";
    }
</script>