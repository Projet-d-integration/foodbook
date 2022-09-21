<?php 
    session_start(); 
    if(empty($_SESSION['idUser'])){
        echo "<script>window.location.href='index.php'</script>";
    }
?>

<head>
    <title>Modifier Profil Foodbook</title>
    
    <meta charset="utf-8">
    
    <style>
        <?php require 'styles/edit-profil.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
        <?php require 'scripts/db.php'; ?>
    </style>
    
    <?php RenderFavicon(); ?>
</head>

<?php

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['edit-confirm-profil']))
    {
        if(empty($_POST["name-profil-input"]) || empty($_POST["last-name-profil-input"]) || empty($_POST["email-profil-input"]))
        {
            echo '<script>window.onload = () => { document.getElementById("error_entries").style.display = "block"; }</script>';
        }
        
        else if($_POST["pwd-profil-input"] != $_POST["confirm-pwd-profil-input"])
        {
            echo '<script>window.onload = () => { document.getElementById("error_mdp_confirm").style.display = "block"; }</script>';
        }
        else if(!ValidateNameInput($_POST["name-profil-input"]) || !ValidateNameInput($_POST["last-name-profil-input"]))
        {
            echo '
                <script>
                    window.onload = () => { document.getElementById("error_name").style.display = "block"; }
                </script>';
        }
        else if($_POST["pwd-profil-input"] != "")
        {
            if(!ValidatePasswordInput($_POST["pwd-profil-input"]))
            {
            echo '  
                <script>
                    window.onload = () => { document.getElementById("error_mdp").style.display = "block"; }
                </script>';
            }
        }
        
        else{
            ModifyUser($_POST["name-profil-input"], $_POST["last-name-profil-input"],$info_user[3],$_POST["pwd-profil-input"]);
        }
    }
}
?>






<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Modifier mon profil </div>
    </div>

    <?php $info_user = UserInfo($_SESSION['email']) ?>

    <form method="POST" class="edit-profil-form">
        <input type="text" name="name-profil-input" placeholder="New name..." class="text-input-profil" value="<?= $info_user[1] ?>">

        <input type="text" name="last-name-profil-input" placeholder="New last name..." class="text-input-profil" value="<?= $info_user[2] ?>">

        <input type="hidden" name="email-profil-input" placeholder="New email..." class="text-input-profil" value="<?= $info_user[3] ?>">

        <input type="password" name="pwd-profil-input" placeholder="New password..." class="text-input-profil">

        <input type="password" name="confirm-pwd-profil-input" placeholder="Confirm new password..." class="text-input-profil">

        <div class="error_message" id="error_entries">Veuillez remplir les champs obligatoire.</div>
        <div class="error_message" id="error_mdp_confirm">Les mots de passe ne correspondent pas</div>
        <div class="error_message" id="error_name">Nom ou nom de famille invalide : ne doivent que contenir des lettres</div>
        <div class="error_message" id="error_mdp">Mot de passe invalide, il doit contenir minumum 5 caractères, dont au moins 1 lettre et 1 chiffre</div>


        <input type="submit" value="Modifier" class="button button-primary" name="edit-confirm-profil">

        <a class="button button-primary" href="edit-email-profil.php">Modifier votre courriel?</a>
    </form>


</body>