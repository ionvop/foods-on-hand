<?php

chdir("../");
require_once "common.php";
$user = getUser();

if ($user == false) {
    alert("You are not logged in");
}

$db = new SQLite3("database.db");

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
        <?= renderHeader("Profile", "Your profile") ?>
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
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    padding: 1rem;
                    padding-top: 0rem;">
                    <?php
                        $query = <<<SQL
                            SELECT * FROM `recipes` WHERE `user_id` = :user_id
                        SQL;

                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":user_id", $user["id"]);
                        $recipes = $stmt->execute();

                        while ($recipe = $recipes->fetchArray(SQLITE3_ASSOC)) {
                            renderRecipe($recipe);
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
                    <?= htmlentities($user["firstname"] . " " . $user["lastname"]) ?>
                </div>
                <div style="
                    padding: 1rem;
                    text-align: center;
                    font-size: 1.5rem;
                    color: #555;">
                    <?= htmlentities($user["username"]) ?>
                </div>
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
            </div>
        </div>
        <?= renderFooter() ?>
        <script src="script.js"></script>
        <script>
            const panelPosts = document.getElementById("panelPosts");
            initialize();

            function initialize() {
                const panelPostsOriginalStyles = panelPosts.style.cssText;

                window.onresize = () => {
                    if (window.innerHeight > window.innerWidth) {
                        panelPosts.style.gridTemplateColumns = "minmax(0, 1fr)";
                    } else {
                        panelPosts.style.cssText = panelPostsOriginalStyles;
                    }
                }

                window.onresize();
            }
        </script>
    </body>
</html>