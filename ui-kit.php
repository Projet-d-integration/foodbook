<?php session_start(); ?>

<head>
    <title>Ui-kit Foodbook</title>

    <meta charset="utf-8" name="viewport" content="width=device-width" />   

    <style>
        <?php require 'styles/ui-kit.css'; ?>

        <?php require 'styles/must-have.css'; ?>
        <?php require 'scripts/body-scripts.php'; ?>
    </style>

    <?php RenderFavicon(); ?>
</head>

<body> 

    <div class="header-banner">
        <a href="index.php"><?php echo file_get_contents("utilities/foodbook-logo.svg"); ?></a>
        <div class="banner-title"> Ui-Kit </div>
    </div>

    <div class="wrapper">
        <div class="title"> Colors : </div>
        <div class="content-wrapper">
            <div class="item-wrapper">
                <div class="label"> --color-primary </div>
                <div class="color color-primary"> #333333 </div>
            </div>

            <div class="item-wrapper">
                <div class="label"> --color-secondary </div>
                <div class="color color-secondary"> #FFFFFF </div>
            </div>

            <div class="item-wrapper">
                <div class="label"> --color-tertiary </div>
                <div class="color color-tertiary"> #c7eae9 </div>
            </div>

            <div class="item-wrapper">
                <div class="label"> --color-tertiary-hover </div>
                <div class="color color-tertiary-hover"> #E1F4F3 </div>
            </div>

            <div class="item-wrapper">
                <div class="label"> --color-accent </div>
                <div class="color color-four"> #706C61 </div>
            </div>

            <div class="item-wrapper">
                <div class="label"> --color-accent-hover </div>
                <div class="color color-five"> #a09c92 </div>
            </div>
        </div>

        <div class="title"> Buttons : </div>

        <div class="content-wrapper">
            <div class="item-wrapper">
                <div class="label"> button-primary </div>
                <?php GenerateButtonPrimary("Lorem Ipsum", ""); ?>
            </div>

            <div class="item-wrapper dark-background">
                <div class="label"> button-secondary </div>
                <?php GenerateButtonSecondary("Lorem Ipsum", ""); ?>
            </div>

            <div class="item-wrapper">
                <div class="label"> button-tertiary </div>
                <?php GenerateButtonTertiary("Lorem Ipsum", ""); ?>
            </div>
        </div>
    </div>  

    <?php GenerateFooter(); ?>
</body>

