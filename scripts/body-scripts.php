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
?>