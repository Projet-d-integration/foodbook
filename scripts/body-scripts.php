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
?>