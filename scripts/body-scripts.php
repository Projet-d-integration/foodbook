<?php 
    function GenerateFooter() {
        echo '    
        <div class="footer">
            Une création du Collège Lionel-Groulx, fait par Anthony Lamothe, Gabriel Lesard, Guillaume Légaré et Samy Tétrault<a href="./ui-kit.php" class="hidden-cursor">.</a>
        </div>';
    }

    function RenderFavicon() {
        echo '<link rel="shortcut icon" href="./utilities/recipe.ico" type="image/x-icon"/>';
    }

    function GenerateButtonPrimary(string $content, string $link) {
        echo '<a class="button button-Primary" href="'.$link.'"> '.$content.' </a>';
    }

    function GenerateButtonSecondary(string $content, string $link) {
        echo '<a class="button button-secondary" href="'.$link.'"> '.$content.' </a>';
    }
    
    function GenerateButtonTertiary(string $content, string $link) {
        echo '
        <a class="button button-tertiary" href="'.$link.'"> '.$content.'
            <div class="button-tertiary-arrow"> '.file_get_contents("./utilities/arrow.svg").'</div>
        </a>';
    }

    function GenerateFormEditProfil()
    {
        #rajouter méthode pour aller chercher toutes les infos du users connecté


        echo '<form method="POST" class="edit-profil-form">
        <input type="text" name="name-profil-input" placeholder="New name..." class="text-input-profil">

        <input type="text" name="last-name-profil-input" placeholder="New last name..." class="text-input-profil">

        <input type="email" name="email-profil-input" placeholder="New email..." class="text-input-profil">

        <input type="password" name="pwd-profil-input" placeholder="New password..." class="text-input-profil">

        <input type="password" name="confirm-pwd-profil-input" placeholder="Confirm new password..." class="text-input-profil">

        <input type="submit" value="Modifier" class="button button-primary" name="edit-confirm-profil">
         </form>';
    }
?>