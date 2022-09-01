<?php session_start(); ?>

<head>
    <title>Devs space Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/ui-kit.css'; ?>
    </style>
</head>

<?php 
    // close session
    // if(!empty($_SESSION))
    // {
    //     session_unset();
    //     session_destroy();
    // } 
?>

<style>
    .color {
        --color-primary: #333333;
        --color-secondary: #FFFFFF;
        --color-tertiary: #E1F4F3;
        --color-accent: #706C61;
    }
</style>

<body> 
    <div class="wrapper">

        <div class="header-banner">
            <div class="banner-title">
                <img src="utilities/foodbook-logo.svg" class="foodbook-logo"></img>
                FoodBook - Ui -Kit
            </div>
        </div>

        <div class="colors-wrapper">
            <div class="color-wrapper">
                <div class="color-label">
                    --color-primary
                </div>
                <div class="color color-one">
                    #333333
                </div>
            </div>
            <div class="color-wrapper">
                <div class="color-label">
                    --color-secondary
                </div>
                <div class="color color-two">
                    #FFFFFF
                </div>
            </div>
            <div class="color-wrapper">
                <div class="color-label">
                    --color-tertiary
                </div>
                <div class="color color-three">
                    #E1F4F3
                </div>
            </div>
            <div class="color-wrapper">
                <div class="color-label">
                    --color-accent
                </div>
                <div class="color color-four">
                    #706C61
                </div>
            </div>
        </div>
    </div>  
</body>

