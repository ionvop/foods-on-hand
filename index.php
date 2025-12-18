<?php

require_once "common.php";

?>

<html>
    <head>
        <title>
            FoodsOnHand
        </title>
        <base href="./">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="manifest" href="manifest.json">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("Recipe\nBook", "With flavors unique, become critique and clique") ?>
        <div style="
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            padding: 5rem;"
            id="panelAbout">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    ABOUT OUR WEBSITE
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;
                    font-weight: bold;
                    font-size: 1.5rem;">
                    Recipe Sharing Website
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;
                    padding-right: 3rem;
                    font-size: 1.3rem;">
                    FoodsOnHand is your ultimate digital cookbook and culinary community, designed to inspire your cooking creativity and connect you with fellow food lovers. Explore a vast collection of recipes tailored to the ingredients you have on hand, rate your favorites, share your own culinary creations, and join an active community where everyone can be both a critique and part of the clique. FoodInHand makes cooking fun, social, and deliciously accessible.
                </div>
            </div>
            <div>
                <img style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;"
                    src="assets/home.jpg">
            </div>
        </div>
        <div style="
            padding: 1rem;
            padding-top: 5rem;
            padding-bottom: 1rem;
            text-align: center;
            font-size: 3rem;
            font-weight: bold;">
            CATEGORIES
        </div>
        <div style="
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            padding-bottom: 5rem;"
            id="panelCategories">
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=pork">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-1.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Pork
                </div>
            </a>
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=chicken">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-2.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Chicken
                </div>
            </a>
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=beef">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-3.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Beef
                </div>
            </a>
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=fish">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-4.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Fish
                </div>
            </a>
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=vegetable">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-5.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Vegetable
                </div>
            </a>
            <a style="
                display: block;
                padding: 1rem;"
                class="category"
                href="recipes/?category=seafood">
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;
                        height: 10rem;
                        object-fit: contain;"
                        src="assets/categ-6.png">
                </div>
                <div style="
                    padding: 1rem;
                    background-color: #555;
                    color: #fff;
                    text-align: center;
                    font-weight: bold;
                    font-size: 2rem;"
                    class="name">
                    Seafood
                </div>
            </a>
        </div>
        <?= renderFooter() ?>
        <script src="script.js"></script>
        <script>
            if ("serviceWorker" in navigator) navigator.serviceWorker.register("service-worker.js");
            const panelAbout = document.getElementById("panelAbout");
            const panelCategories = document.getElementById("panelCategories");
            initialize();

            function initialize() {
                const panelAboutOriginalStyles = panelAbout.style.cssText;
                const panelCategoriesOriginalStyles = panelCategories.style.cssText;

                window.onresize = () => {
                    if (window.innerHeight > window.innerWidth) {
                        panelAbout.style.gridTemplateColumns = "1fr";
                        panelAbout.style.padding = "1rem";
                        panelCategories.style.gridTemplateColumns = "1fr";
                    } else {
                        panelAbout.style.cssText = panelAboutOriginalStyles;
                        panelCategories.style.cssText = panelCategoriesOriginalStyles;
                    }
                }

                window.onresize();
            }
        </script>
    </body>
</html>