<?php

chdir("../");
require_once "common.php";
$user = getUser();
$db = new SQLite3("database.db");
$recipeId = $_GET["id"];

$query = <<<SQL
    SELECT * FROM `recipes` WHERE id = :id
SQL;

$stmt = $db->prepare($query);
$stmt->bindValue(":id", $recipeId);
$recipe = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

$categoryMap = [
    "pork" => "Pork",
    "chicken" => "Chicken",
    "beef" => "Beef",
    "fish" => "Fish",
    "vegetable" => "Vegetable",
    "seafood" => "Seafood"
];

$query = <<<SQL
    SELECT * FROM `users` WHERE `id` = :id
SQL;

$stmt = $db->prepare($query);
$stmt->bindValue(":id", $recipe["user_id"]);
$author = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

?>

<html>
    <head>
        <title>
            Recipe
        </title>
        <base href="../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #panelContent img {
                max-width: 100%;
            }
        </style>
    </head>
    <body>
        <?= renderHeader($recipe["title"], "Author: " . $author["firstname"] . " " . $author["lastname"], "profile/?id=" . $author["id"]) ?>
        <div style="
            display: grid;
            grid-template-columns: minmax(0, 2fr) 1fr;"
            data-mobile="
            grid-template-columns: minmax(0, 1fr);">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    <?= htmlentities($recipe["title"]) ?>
                </div>
                <div style="
                    padding: 1rem;">
                    <img style="
                        width: 100%;"
                        src="<?= "uploads/" . htmlentities($recipe["image"]) ?>">
                </div>
                <div style="
                    padding: 1rem;"
                    id="panelContent">
                </div>
            </div>
            <div style="
                background-color: #eee;">
                <div style="
                    display: grid;
                    grid-template-columns: 1fr max-content;">
                    <div></div>
                    <form style="
                        padding: 1rem;"
                        action="server.php"
                        method="post"
                        enctype="multipart/form-data">
                        <?php
                            $query = <<<SQL
                                SELECT * FROM `bookmarks` WHERE `user_id` = :user_id AND `recipe_id` = :recipe_id
                            SQL;

                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":user_id", $user["id"]);
                            $stmt->bindValue(":recipe_id", $recipe["id"]);
                            $bookmark = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

                            if ($bookmark == false) {
                                echo <<<HTML
                                    <button style="
                                        background-color: #a00;
                                        color: #fff;"
                                        name="method"
                                        value="bookmark">
                                        Bookmark
                                    </button>
                                HTML;
                            } else {
                                echo <<<HTML
                                    <button style="
                                        background-color: #a00;
                                        color: #fff;"
                                        name="method"
                                        value="unbookmark">
                                        Unbookmark
                                    </button>
                                HTML;
                            }
                        ?>
                        <input type="hidden"
                            name="id"
                            value="<?= $recipe["id"] ?>">
                    </form>
                </div>
                <div style="
                    padding: 1rem;
                    font-size: 1.5rem;">
                    Category: <?= $categoryMap[$recipe["category"]] ?>
                </div>
                <?php
                    if ($user != false) {
                        if ($user["id"] == $recipe["user_id"]) {
                            echo <<<HTML
                                <div style="
                                    padding: 1rem;
                                    text-align: center;">
                                    <a style="
                                        display: inline-block;"
                                        href="edit/?id={$recipe['id']}">
                                        <button style="
                                            background-color: #a00;
                                            color: #fff;">
                                            Edit
                                        </button>
                                    </a>
                                </div>
                            HTML;
                        }
                    }
                ?>
                <div style="
                    padding: 1rem;
                    font-size: 1.5rem;">
                    Comments:
                </div>
                <?php
                    if ($user != false) {
                        echo <<<HTML
                            <form action="server.php"
                                method="post"
                                enctype="multipart/form-data">
                                <div style="
                                    padding: 1rem;">
                                    <textarea style="
                                        height: 10rem;"
                                        name="content"
                                        placeholder="Write a comment..."></textarea>
                                </div>
                                <div style="
                                    display: grid;
                                    grid-template-columns: 1fr max-content;">
                                    <div></div>
                                    <div style="
                                        padding: 1rem;">
                                        <button style="
                                            background-color: #a00;
                                            color: #fff;"
                                            name="method"
                                            value="comment">
                                            Comment
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden"
                                    name="recipe_id"
                                    value="{$recipe['id']}">
                            </form>
                        HTML;
                    }
                ?>
                <div>
                    <?php
                        $query = <<<SQL
                            SELECT * FROM `comments` WHERE `recipe_id` = :recipe_id ORDER BY `id` DESC
                        SQL;

                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":recipe_id", $recipeId);
                        $comments = $stmt->execute();

                        while ($comment = $comments->fetchArray(SQLITE3_ASSOC)) {
                            $query = <<<SQL
                                SELECT * FROM `users` WHERE `id` = :id
                            SQL;

                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id", $comment["user_id"]);
                            $author = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
                            $username = htmlentities($author["username"]);
                            
                            $delete = <<<HTML
                                <div></div>
                            HTML;

                            if ($user != false) {
                                if ($comment["user_id"] == $user["id"]) {
                                    $delete = <<<HTML
                                        <form style="
                                            padding: 1rem;"
                                            action="server.php"
                                            method="post"
                                            enctype="multipart/form-data">
                                            <button style="
                                                padding: 0rem;
                                                background-color: transparent;
                                                color: #a00;
                                                text-decoration: underline;"
                                                name="method"
                                                value="delete_comment"
                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                                Delete
                                            </button>
                                            <input type="hidden"
                                                name="id"
                                                value="{$comment['id']}">
                                        </form>
                                    HTML;
                                }
                            }

                            $content = htmlentities($comment["content"]);

                            echo <<<HTML
                                <div style="
                                    padding: 1rem;
                                    border-bottom: 1px solid #aaa;">
                                    <div style="
                                        display: grid;
                                        grid-template-columns: repeat(2, max-content) 1fr max-content;">
                                        <div style="
                                            padding: 1rem;
                                            font-weight: bold;">
                                            {$username}
                                        </div>
                                        <div style="
                                            padding: 1rem;
                                            font-size: 0.7rem;
                                            color: #aaa;"
                                            class="time">
                                            {$comment["time"]}
                                        </div>
                                        <div></div>
                                        {$delete}
                                    </div>
                                    <div style="
                                        padding: 1rem;">
                                        {$content}
                                    </div>
                                </div>
                            HTML;
                        }
                    ?>
                </div>
            </div>
        </div>
        <?= renderFooter() ?>
        <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
        <script src="script.js"></script>
        <script>
            const panelContent = document.getElementById("panelContent");
            initialize();

            function initialize() {
                g_panelBackground.style.backgroundImage = <?= json_encode("linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(uploads/" . $recipe["image"] . ")") ?>;
                panelContent.innerHTML = marked.parse(<?= json_encode($recipe["content"]) ?>);

                for (const time of document.getElementsByClassName("time")) {
                    time.innerHTML = new Date(parseInt(time.innerHTML) * 1000).toLocaleString();
                }

                panelContent.scrollIntoView();
            }
        </script>
    </body>
</html>