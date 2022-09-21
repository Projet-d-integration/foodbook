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

    // Returns true if an email has a valid format
    function ValidateEmailInput($input) { 
        return !filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    // Returns true if name has valid format
    function ValidateNameInput($input){
        return preg_match("/^[a-zA-Z-' ]*$/", $input);
    }

    // Returns true if password has right formatting
    function ValidatePasswordInput($input) {
        return preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$/", $input) == 0;
    }

    function GenerateFormEditProfil()
    {
        


        echo '<form action="POST" class="edit-profil-form">
        <input type="text" name="name-profil-input" class="text-input-profil" placeholder="New name...">

        <input type="text" name="last-name-profil-input" class="text-input-profil" placeholder="New last name...">

        <input type="email" name="email-profil-input" class="text-input-profil" placeholder="New email...">

        <input type="password" name="pwd-profil-input" class="text-input-profil" placeholder="New password...">

        <input type="password" name="confirm-pwd-profil-input" class="text-input-profil" placeholder="Confirm new password...">

        </form>';
    }
?>