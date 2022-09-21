<?php 
    session_start(); 
    if(empty($_SESSION['idUser'])){
        echo "<script>window.location.href='index.php'</script>";
    }
?>

<head>
    <title>Modifier votre Email Foodbook</title>
    
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
$email = $_POST["email-edit-profil"];



if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['edit-email-but-profil']))
    {
        if(empty($_POST["email-edit-profil"]) || empty($_POST["confirm-email-edit-profil"]))
        {
            echo '<script>window.onload = () => { document.getElementById("error_entries").style.display = "block"; }</script>';
        }
        else if(UserExist($_POST["email-edit-profil"]))
        {
            echo '<script>window.onload = () => { document.getElementById("error_email_used").style.display = "block"; }</script>';
        }
        else if(ValidateEmailInput($_POST["email-edit-profil"]) || ValidateEmailInput($_POST["confirm-email-edit-profil"]))
        {
            echo '<script>window.onload = () => { document.getElementById("error_email").style.display = "block"; }</script>';
        }
        else if($_POST["confirm-email-edit-profil"] != $_POST["email-edit-profil"])
        {
            echo '<script>window.onload = () => { document.getElementById("error_email_different").style.display = "block"; }</script>';
        }
        else{
            ModifyEmail($info_user[0],$_POST["confirm-email-edit-profil"]);
            $info_user[3] = $_POST["confirm-email-edit-profil"];
            echo '<script>window.onload = () => { document.getElementById("success_modified_email").style.display = "block"; }</script>';
        }          
    }
}
?>
<body>
<div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Modifier mon courriel</div>
    </div>

    <form method="POST" class="edit-profil-form">
        <input type="text" name="email-edit-profil" class="text-input-profil" placeholder="New email..." value="<?= $info_user[3] ?>">
        <input type="text" name ="confirm-email-edit-profil"class="text-input-profil" placeholder="Confirm new email...">
        <input name="edit-email-but-profil" type="submit" value="Modifier" class="button button-primary">
    </form>

    <div class="error_message" id="error_entries">Veuillez remplir le champs obligatoire.</div>
    <div class="success_message" id="success_modified_email">Vos informations ont été correctement modifiées.</div>
    <div class="error_message" id="error_email_used">Courriel déjà utilisé</div>
    <div class="error_message" id="error_email">Courriel invalide</div>
    <div class="error_message" id="error_email_different">Les courriels ne correspondent pas.</div>

</body>