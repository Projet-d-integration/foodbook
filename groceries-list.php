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

    $nb_liste = 0;
?>

<head>
    <title>Liste d'épicerie Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/groceries-list.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<body>
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Liste d'épicerie </div>
        <div class="svg-wrapper">
            <a href="groceries-list.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
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

    <div class="wrapper-list">

        <form method="POST">
            <button type="submit" id="add-new-grocery-list" name="btnAddGroceryList" class="button button-primary">Ajouter une liste d'épicerie</button>
        </form>
        
        <div class="box-container">
            <!--<div id="list-box" class="box-list" onclick="ShowListGroceries('testDiv')">test
                <div class="caret-svg"> <?php echo file_get_contents("utilities/caret-right.svg"); ?></div>
            </div>-->
            <?php 

            if(isset($_POST["btnAddGroceryList"]))
            {
                GenerateFormGroceryList();
                $nb_liste++;
            }

            ?>
            <div class="div-test"  id="testDiv">
                <br></br>
                <button onclick="showForm('form-ingred')" class="button button-primary">Ajouter un ingrédient dans ma liste</button>
                <br>
                <input id="i1" type="checkbox" value="Ingrédien1">  <label onclick="addDashed('label-check1')" id="label-check1" for="i1">Ingrédient 1</label>
                <br>
                <input id="i2" type="checkbox" value="Ingrédien2">  <label onclick="addDashed('label-check2')" id="label-check2" for="i2">Ingrédient 2</label>
                <br>
                <input id="i3" type="checkbox" value="Ingrédien3">  <label onclick="addDashed('label-check3')" id="label-check3" for="i3">Ingrédient 3</label>
                <br>
                <input id="i4" type="checkbox" value="Ingrédien4">  <label onclick="addDashed('label-check4')" id="label-check4" for="i4">Ingrédient 4</label>
                <br>
                <input id="i5" type="checkbox" value="Ingrédien5">  <label onclick="addDashed('label-check5')" id="label-check5" for="i5">Ingrédient 5</label>
                <br>
            </div>
        </div>
        <!--<div class="box-list">une liste d'épicerie 
            <div class="caret-svg"> <?php echo file_get_contents("utilities/caret-right.svg"); ?></div>
        </div>
        <div class="box-list">une liste d'épicerie
            <div class="caret-svg"> <?php echo file_get_contents("utilities/caret-right.svg"); ?></div>
        </div>
        <div class="box-list">une liste d'épicerie 
            <div class="caret-svg"> <?php echo file_get_contents("utilities/caret-right.svg"); ?></div>
        </div>
        <div class="box-list">une liste d'épicerie
            <div class="caret-svg"> <?php echo file_get_contents("utilities/caret-right.svg"); ?></div>
        </div>-->

        <div id="message-nb-liste" class="message-liste">
            Vous avez actuellement <?php echo $nb_liste;?> liste d'épicerie.
        </div>
    </div>

    <div id="form-ingred" class="hide-form-ingredient">
        <h1>Ajout d'un nouvel ingrédient</h1>
            <input name="ingredient-name" type="text" class="add-ingredient-input" placeholder="Nom de l'ingrédient...">
            <br>
            <input type="submit" class="button button-primary" value="Ajouter">
    </div> 
 
    </div>
    
    


    <?php GenerateFooter(); ?>
</body>





<script defer> 


    function ShowListGroceries() {
        if(document.getElementById("testDiv").classList.contains("active"))
        {
            document.getElementById("testDiv").classList.remove("active");
        }
        else{
            document.getElementById("testDiv").classList.add('active');
        }
    }

    function addDashed(idname)
    {
        if(document.getElementById(idname).classList.contains("text-barrer"))
        {
            document.getElementById(idname).classList.remove("text-barrer");
        }
        else{
            document.getElementById(idname).classList.add('text-barrer');
        }
    }

    function showForm(idname)
    {
        document.getElementById(idname).classList.remove('hide-form-ingredient');

        document.getElementById(idname).classList.add('form-add-ingredient');
    }
</script>
