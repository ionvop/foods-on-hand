<?php

chdir("../");
require_once "common.php";
$db = new SQLite3("database.db");
$user = getUser();

if (isset($_GET["id"]) == false) {
    alert("Invalid profile");
}

$query = <<<SQL
    SELECT * FROM `users` WHERE `id` = :id
SQL;

$stmt = $db->prepare($query);
$stmt->bindValue(":id", $_GET["id"]);
$target = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($target == false) {
    alert("Invalid profile");
}

?>

<html>
    <head>
        <title>
            Profile
        </title>
        <base href="../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("Profile", "More details about this user") ?>
        <div style="
            display: grid;
            grid-template-columns: 2fr 1fr;"
            data-mobile="
            grid-template-columns: minmax(0, 1fr);">
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
                    padding-top: 0rem;"
                    data-mobile="
                    grid-template-columns: 1fr;">
                    <?php
                        $query = <<<SQL
                            SELECT * FROM `recipes` WHERE `user_id` = :user_id
                        SQL;

                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":user_id", $target["id"]);
                        $recipes = $stmt->execute();
                        $count = 0;

                        while ($recipe = $recipes->fetchArray(SQLITE3_ASSOC)) {
                            renderRecipe($recipe);
                            $count++;
                        }

                        if ($count == 0) {
                            echo <<<HTML
                                <div style="
                                    padding: 1rem;
                                    text-align: center;
                                    font-size: 1.5rem;
                                    color: #555;">
                                    No recipes yet
                                </div>
                            HTML;
                        }
                    ?>
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 5rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    Bookmarks:
                </div>
                <div style="
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    padding: 1rem;
                    padding-top: 0rem;"
                    data-mobile="
                    grid-template-columns: 1fr;">
                    <?php
                        $query = <<<SQL
                            SELECT * FROM `bookmarks` WHERE `user_id` = :user_id
                        SQL;

                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":user_id", $target["id"]);
                        $bookmarks = $stmt->execute();
                        $count = 0;

                        while ($bookmark = $bookmarks->fetchArray(SQLITE3_ASSOC)) {
                            $query = <<<SQL
                                SELECT * FROM `recipes` WHERE `id` = :id
                            SQL;

                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id", $bookmark["recipe_id"]);
                            $recipe = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

                            renderRecipe($recipe);
                            $count++;
                        }

                        if ($count == 0) {
                            echo <<<HTML
                                <div style="
                                    padding: 1rem;
                                    text-align: center;
                                    font-size: 1.5rem;
                                    color: #555;">
                                    No bookmarks yet
                                </div>
                            HTML;
                        }
                    ?>
                </div>
            </div>
            <div style="
                background-color: #eee;">
                <div style="
                    padding: 1rem;
                    text-align: center;
                    font-size: 2rem;
                    font-weight: bold;">
                    <?= htmlentities($target["firstname"] . " " . $target["lastname"]) ?>
                </div>
                <div style="
                    padding: 1rem;
                    text-align: center;
                    font-size: 1.5rem;
                    color: #555;">
                    <?= htmlentities($target["username"]) ?>
                </div>
                <div style="
                    padding: 1rem;
                    padding-bottom: 0.5rem;
                    text-align: center;
                    color: #555;">
                    Contact number:
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;
                    text-align: center;
                    color: #555;">
                    <?= htmlentities($target["phone"]) ?>
                </div>
                <?php
                    if ($target["id"] == $user["id"]) {
                        echo <<<HTML
                            <div style="
                                padding: 1rem;
                                text-align: center;">
                                <a style="
                                    display: inline-block;"
                                    href="profile/edit/">
                                    <button style="
                                        background-color: #a00;
                                        color: #fff;">
                                        Edit Profile
                                    </button>
                                </a>
                            </div>
                            <form style="
                                padding: 1rem;
                                text-align: center;"
                                action="server.php"
                                method="post"
                                enctype="multipart/form-data">
                                <button style="
                                    background-color: #a00;
                                    color: #fff;"
                                    name="method"
                                    value="logout">
                                    Logout
                                </button>
                            </form>
                        HTML;
                    }
                ?>
            </div>
        </div>
        <?= renderFooter() ?>
        <script src="script.js"></script>
        <script>
            
        </script>
    </body>
</html>