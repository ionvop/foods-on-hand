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
            data-mobile="
            grid-template-columns: 1fr;">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    Recipes
                </div>
                <div style="
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    padding: 1rem;
                    padding-top: 2rem;">
                    <?php
                        if ($category == "all") {
                            if (isset($_GET["q"])) {
                                $query = <<<SQL
                                    SELECT * FROM `recipes` WHERE `title` LIKE :q
                                SQL;

                                $stmt = $db->prepare($query);
                                $stmt->bindValue(":q", "%" . $_GET["q"] . "%");
                                $recipes = $stmt->execute();
                            } else {
                                $query = <<<SQL
                                    SELECT * FROM `recipes`
                                SQL;

                                $stmt = $db->prepare($query);
                                $recipes = $stmt->execute();
                            }
                        } else {
                            if (isset($_GET["q"])) {
                                $query = <<<SQL
                                    SELECT * FROM `recipes` WHERE `title` LIKE :q AND `category` = :category
                                SQL;

                                $stmt = $db->prepare($query);
                                $stmt->bindValue(":q", "%" . $_GET["q"] . "%");
                                $stmt->bindValue(":category", $category);
                                $recipes = $stmt->execute();
                            } else {
                                $query = <<<SQL
                                    SELECT * FROM `recipes` WHERE `category` = :category
                                SQL;

                                $stmt = $db->prepare($query);
                                $stmt->bindValue(":category", $category);
                                $recipes = $stmt->execute();
                            }
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
                <form style="
                    padding: 1rem;">
                    <div style="
                        display: grid;
                        grid-template-columns: max-content 1fr;
                        border-bottom: 3px solid #a00;">
                        <div style="
                            display: flex;
                            align-items: center;
                            padding: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M380-320q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l224 224q11 11 11 28t-11 28q-11 11-28 11t-28-11L532-372q-30 24-69 38t-83 14Zm0-80q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                        </div>
                        <div style="
                            display: flex;
                            align-items: center;">
                            <input style="
                                border: none;"
                                type="text"
                                name="q"
                                placeholder="Search..."
                                id="inputSearch">
                        </div>
                    </div>
                </form>
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
            const inputSearch = document.getElementById("inputSearch");
            initialize();

            function initialize() {
                inputSearch.value = <?= json_encode(isset($_GET["q"]) ? $_GET["q"] : "") ?>;
                selectCategories.value = <?= json_encode($category) ?>;
            }

            selectCategories.onchange = () => {
                const url = new URL(location.href);
                url.searchParams.set("category", selectCategories.value);
                location.href = url.href;
            };
        </script>
    </body>
</html>