<?php session_start(); ?>

<head>
    <title>Devs space Foodbook</title>
    <meta charset="utf-8">
    <style>
        <?php require 'styles/index.css'; ?>
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
        <div class="colors-wrapper">
            <div class="color-wrapper">
                <div class="color-label"></div>
                <div class="color color-one"></div>
            </div>
            <div class="color-wrapper">
                <div class="color-label"></div>
                <div class="color color-two"></div>
            </div>
            <div class="color-wrapper">
                <div class="color-label"></div>
                <div class="color color-three"></div>
            </div>
            <div class="color-wrapper">
                <div class="color-label"></div>
                <div class="color color-four"></div>
            </div>
        </div>
    </div>  
</body>

