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

$info_user = UserInfo($_SESSION['email']);


if($_SERVER['REQUEST_METHOD'] === 'POST')
{

        if(!isset($_POST["name-profil-input"])) {$name_profil = "";}
        else {$name_profil = $_POST["name-profil-input"];}
        
        if(!isset($_POST["last-name-profil-input"])) {$last_name_profil = "";}
        else {$last_name_profil = $_POST["last-name-profil-input"];}
        
        if(!isset($_POST["email-edit-profil"])) {$email_profil = "";}
        else {$email_profil = $_POST["email-edit-profil"];}

        if(!isset($_POST["pwd-profil-input"])) {$pwd_profil = "";}
        else {$pwd_profil = $_POST["pwd-profil-input"];}

        if(!isset($_POST["confirm-pwd-profil-input"])) {$pwd_confirm_profil = "";}
        else {$pwd_confirm_profil = $_POST["confirm-pwd-profil-input"];}


    if(isset($_POST['edit-confirm-profil']))
    {
        if(empty($name_profil) || empty($last_name_profil))
        {
            echo '<script>window.onload = () => { document.getElementById("error_entries").style.display = "block"; }</script>';
        }
        else if($pwd_profil != $pwd_confirm_profil )
        {
            echo '<script>window.onload = () => { document.getElementById("error_mdp_confirm").style.display = "block"; }</script>';
        }
        else if(!ValidateNameInput($name_profil) || !ValidateNameInput($last_name_profil))
        {
            echo '
                <script>
                    window.onload = () => { document.getElementById("error_name").style.display = "block"; }
                </script>';
        }
        else if($_POST["pwd-profil-input"] != "")
        {
            if(!ValidatePasswordInput($pwd_profil))
            {
            echo '  
                <script>
                    window.onload = () => { document.getElementById("error_mdp").style.display = "block"; }
                </script>';
            }
            else{
                ModifyUser($_POST["name-profil-input"], $_POST["last-name-profil-input"],$info_user[3],hash("sha512",$_POST["pwd-profil-input"]));
                $info_user[1] = $_POST["name-profil-input"];
                $info_user[2] = $_POST["last-name-profil-input"];
                echo'<script>window.onload = () => { document.getElementById("success_modified").style.display = "block"; } </script>';
            }
        }
        else{
            ModifyUser($_POST["name-profil-input"], $_POST["last-name-profil-input"],$info_user[3],$info_user[4]);
            $info_user[1] = $_POST["name-profil-input"];
            $info_user[2] = $_POST["last-name-profil-input"];
            echo'<script>window.onload = () => { document.getElementById("success_modified").style.display = "block"; } </script>';
        }

        if(empty($email_profil))
        {
            echo '<script>window.onload = () => { document.getElementById("error_entries").style.display = "block"; }</script>';
        }


        else if($email_profil != $info_user[3])
        {
            if(UserExist($_POST["email-edit-profil"]))
            {
                echo '<script>window.onload = () => { document.getElementById("error_email_used").style.display = "block"; }</script>';
            }
            
            else if(ValidateEmailInput($email_profil))
            {
                echo '<script>window.onload = () => { document.getElementById("error_email").style.display = "block"; }</script>';
            }
            else{
                ModifyEmail($info_user[0],$_POST["email-edit-profil"]);
                $info_user[3] = $_POST["email-edit-profil"];
                echo '<script>window.onload = () => { document.getElementById("success_modified_email").style.display = "block"; }</script>';
            }
        }
       
        
        
        
    }
}
?>
<body> 
    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Modifier mon profil </div>


        <div class="svg-wrapper">
            <a href="login.php" class="svg-button list-button"> <?php echo file_get_contents("utilities/food.svg"); ?> </a>
            <a href="login.php" class="svg-button inventory-button"> <?php echo file_get_contents("utilities/list.svg"); ?> </a>
        </div>
    </div>



    <form method="POST" class="edit-profil-form">
        <input type="text" name="name-profil-input" placeholder="New name..." class="text-input-profil" value="<?= $info_user[1] ?>">

        <input type="text" name="last-name-profil-input" placeholder="New last name..." class="text-input-profil" value="<?= $info_user[2] ?>">

        <input type="text" name="email-edit-profil" placeholder="New email..." class="text-input-profil" value="<?= $info_user[3] ?>">

        <input type="password" name="pwd-profil-input" placeholder="New password..." class="text-input-profil">

        <input type="password" name="confirm-pwd-profil-input" placeholder="Confirm new password..." class="text-input-profil">

        <div class="error_message" id="error_entries">Veuillez remplir les champs obligatoire.</div>
        <div class="error_message" id="error_mdp_confirm">Les mots de passe ne correspondent pas</div>
        <div class="error_message" id="error_name">Nom ou nom de famille invalide : ne doivent que contenir des lettres</div>
        <div class="error_message" id="error_mdp">Mot de passe invalide, il doit contenir minumum 5 caractères, dont au moins 1 lettre et 1 chiffre</div>
        <div class="success_message" id="success_modified">Vos informations ont été correctement modifiées.</div>
        <div class="success_message" id="success_modified_email">Votre email a été correctement modifié.</div>
        <div class="error_message" id="error_email_used">Courriel déjà utilisé</div>
        <div class="error_message" id="error_email">Courriel invalide</div>
        <div class="error_message" id="error_email_different">Les courriels ne correspondent pas.</div>

        <input type="submit" value="Modifier" class="button button-primary" name="edit-confirm-profil">
    </form>


</body>