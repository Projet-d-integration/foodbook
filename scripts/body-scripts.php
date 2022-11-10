<?php 
    function GenerateFooter() {
        echo '    
        <div class="footer">
        <div>
        Une création du Collège Lionel-Groulx, fait par Anthony Lamothe, Gabriel Lesard, Guillaume Légaré et Samy Tétrault<a href="./ui-kit.php" class="hidden-cursor">.</a>
        </div>
            <div class="display-icon-about"> <a href="about-foodbook.php" class="svg-button svg-about">'.file_get_contents("./utilities/about-symbol.svg").'</a> </div>
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
        return preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}$/", $input);
    }

    function ChangePage($goTo){
        echo "<script> window.location.href='". $goTo ."'; </script>";
    }
    function AddAnimation(){
        echo '
            <div class="loader" id="loader">
                <div class="loader-inner">
                    <div id="restLoader">
                        <svg version="1.1" id="panMan" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="279.116px" height="33.416px" viewBox="23.116 0 279.116 33.416" enable-background="new 23.116 0 279.116 33.416"
                            xml:space="preserve">
                            <g>
                            <path fill="#000" d="M184.073,4.527L184.073,4.527L25.76,4.526v0.001c0,10.523,3.857,20.391,10.592,28.889h137.129
                                C180.216,24.918,184.073,15.051,184.073,4.527z"/>
                            </g>
                            <path fill="#000" d="M192.324,26.681c0,0.92-0.746,1.666-1.667,1.666l0,0c-0.92,0-1.666-0.746-1.666-1.666V5.264
                            c0-0.921,0.746-1.667,1.666-1.667l0,0c0.921,0,1.667,0.746,1.667,1.667V26.681z"/>
                            <polygon fill="#000" points="264.407,25.64 190.324,21.138 190.324,10.805 264.407,6.305 "/>
                            <rect x="263.407" y="6.305" fill="#000" width="28.333" height="19.334"/>
                            <circle fill="#000" cx="292.602" cy="15.935" r="9.631"/>
                            <path fill="#000" d="M186.5,3.598c0,0.92-0.746,1.667-1.667,1.667H24.782c-0.92,0-1.667-0.746-1.667-1.667l0,0
                            c0-0.92,0.746-1.667,1.667-1.667h160.05C185.754,1.931,186.5,2.677,186.5,3.598L186.5,3.598z"/>
                        </svg>
                        <div id="panShadow">
                        </div>
                        <div id="cook">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>';
        echo "<script>
                $(window).on('load',()=>{
                    setTimeout(function() { 
                        $('.loader').fadeOut('slow');
                    },1000);
                });
        </script>";
    }
?>