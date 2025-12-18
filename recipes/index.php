<?php

chdir("../");
require_once "common.php";
$user = getUser();
$db = new SQLite3("database.db");
$category = "all";

if (isset($_GET["category"])) {
    $category = $_GET["category"];
}

?>

<html>
    <head>
        <title>
            Recipes
        </title>
        <base href="../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("Posts", "View the recipes from our users here") ?>
        <div style="
            display: grid;
            grid-template-columns: 2fr 1fr;"
            id="panelPosts">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    Recipes
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 2rem;">
                    <?php
                        if ($category == "all") {
                            $query = <<<SQL
                                SELECT * FROM `recipes`
                            SQL;

                            $stmt = $db->prepare($query);
                            $recipes = $stmt->execute();
                        } else {
                            $query = <<<SQL
                                SELECT * FROM `recipes` WHERE `category` = :category
                            SQL;

                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":category", $category);
                            $recipes = $stmt->execute();
                        }

                        while ($recipe = $recipes->fetchArray(SQLITE3_ASSOC)) {
                            renderRecipe($recipe);
                        }
                    ?>
                </div>
            </div>
            <div>
                <?php
                    if ($user != false) {
                        echo <<<HTML
                            <div style="
                                padding: 1rem;
                                text-align: center;">
                                <a style="
                                    display: inline-block;"
                                    href="new/">
                                    <button style="
                                        background-color: #a00;
                                        color: #fff;">
                                        New Recipe
                                    </button>
                                </a>
                            </div>
                        HTML;
                    }
                ?>
                <div style="
                    padding: 1rem;">
                    Categories:
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;">
                    <select id="selectCategories">
                        <option value="all">
                            All
                        </option>
                        <option value="pork">
                            Pork
                        </option>
                        <option value="chicken">
                            Chicken
                        </option>
                        <option value="beef">
                            Beef
                        </option>
                        <option value="fish">
                            Fish
                        </option>
                        <option value="vegetable">
                            Vegetable
                        </option>
                        <option value="seafood">
                            Seafood
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <?= renderFooter() ?>
        <script src="script.js"></script>
        <script>
            const selectCategories = document.getElementById("selectCategories");
            const panelPosts = document.getElementById("panelPosts");
            initialize();

            function initialize() {
                const panelPostsOriginalStyles = panelPosts.style.cssText;
                selectCategories.value = <?= json_encode($category) ?>;

                window.onresize = () => {
                    if (window.innerHeight > window.innerWidth) {
                        panelPosts.style.gridTemplateColumns = "1fr";
                    } else {
                        panelPosts.style.cssText = panelPostsOriginalStyles;
                    }
                }

                window.onresize();
            }

            selectCategories.onchange = () => {
                const url = new URL(location.href);
                url.searchParams.set("category", selectCategories.value);
                location.href = url.href;
            };
        </script>
    </body>
</html>