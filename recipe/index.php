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
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);"
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
                display: grid;
                grid-template-rows: repeat(5, max-content) repeat(2, 1fr);
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
                            if ($user != false) {
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
                <div>
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
                </div>
                <div style="
                    padding: 1rem;
                    font-size: 1.5rem;">
                    Comments:
                </div>
                <div>
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
                        } else {
                            echo <<<HTML
                                <div style="
                                    padding: 1rem;
                                    text-align: center;">
                                    You must be logged in to comment
                                </div>
                            HTML;
                        }
                    ?>
                </div>
                <div style="
                    max-height: 40rem;
                    overflow-y: auto;">
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
                <div style="
                    display: grid;
                    grid-template-rows: max-content 1fr repeat(2, max-content);
                    height: 40rem;
                    background-color: #ddd;">
                    <div style="
                        padding: 1rem;
                        font-size: 1.5rem;
                        font-weight: bold;">
                        AI Assistant Chat
                    </div>
                    <div style="
                        overflow-y: auto;"
                        id="panelChat">
                    </div>
                    <div style="
                        display: grid;
                        grid-template-columns: max-content 1fr;">
                        <div style="
                            padding: 1rem;">
                            <div style="
                                display: flex;
                                align-items: center;
                                padding: 1rem;
                                padding-top: 0rem;
                                padding-bottom: 0rem;
                                height: 2rem;
                                background-color: #fff;
                                border-radius: 1rem;
                                cursor: pointer;"
                                id="btnPrompt">
                                Can you adjust the recipe for 2 people?
                            </div>
                        </div>
                    </div>
                    <div style="
                        display: grid;
                        grid-template-columns: 1fr max-content;">
                        <div style="
                            display: flex;
                            align-items: center;
                            padding: 1rem;
                            padding-top: 0rem;">
                            <input placeholder="Chat to AI..."
                                id="inputChat">
                        </div>
                        <div style="
                            display: flex;
                            align-items: center;
                            padding: 1rem;
                            padding-top: 0rem;
                            padding-left: 0rem;"
                            id="btnSend">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#aa0000"><path d="M176-183q-20 8-38-3.5T120-220v-180l320-80-320-80v-180q0-22 18-33.5t38-3.5l616 260q25 11 25 37t-25 37L176-183Z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= renderFooter() ?>
        <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
        <script src="script.js"></script>
        <script>
            const panelContent = document.getElementById("panelContent");
            const panelChat = document.getElementById("panelChat");
            const inputChat = document.getElementById("inputChat");
            const btnSend = document.getElementById("btnSend");
            const btnPrompt = document.getElementById("btnPrompt");

            const chat = [
                {
                    role: "system",
                    content: "You are a helpful assistant that will answer questions about this recipe.\n\n" +
                        "Title: " + <?= json_encode($recipe["title"]) ?> + "\n\n" +
                        "Author: " + <?= json_encode($author["firstname"] . " " . $author["lastname"]) ?> + "\n\n" +
                        "Content:\n" + <?= json_encode($recipe["content"]) ?>
                },
                {
                    role: "assistant",
                    content: "Hello! How can I help you?"
                }
            ];

            initialize();

            function initialize() {
                g_panelBackground.style.backgroundImage = <?= json_encode("linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(uploads/" . $recipe["image"] . ")") ?>;
                panelContent.innerHTML = marked.parse(<?= json_encode($recipe["content"]) ?>);

                for (const time of document.getElementsByClassName("time")) {
                    time.innerHTML = new Date(parseInt(time.innerHTML) * 1000).toLocaleString();
                }

                panelContent.scrollIntoView();
                renderChats();
            }

            function renderChats() {
                panelChat.innerHTML = "";

                for (const message of chat) {
                    switch (message.role) {
                        case "user": {
                            panelChat.innerHTML += `
                                <div style="
                                    display: grid;
                                    grid-template-columns: 1fr minmax(0, max-content)">
                                    <div style="
                                        min-width: 5rem;">
                                    </div>
                                    <div style="
                                        padding: 1rem;">
                                        <div style="
                                            padding: 1rem;
                                            padding-top: 0.5rem;
                                            padding-bottom: 0.5rem;
                                            background-color: #eee;
                                            border-radius: 1rem;">
                                            ${marked.parse(message.content)}
                                        </div>
                                    </div>
                                </div>
                            `;
                        } break;
                        case "assistant": {
                            panelChat.innerHTML += `
                                <div style="
                                    display: grid;
                                    grid-template-columns: minmax(0, max-content) 1fr">
                                    <div style="
                                        padding: 1rem;">
                                        <div style="
                                            padding: 1rem;
                                            padding-top: 0.5rem;
                                            padding-bottom: 0.5rem;
                                            background-color: #a00;
                                            color: #fff;
                                            border-radius: 1rem;">
                                            ${marked.parse(message.content)}
                                        </div>
                                    </div>
                                    <div style="
                                        min-width: 5rem;">
                                    </div>
                                </div>
                            `;
                        } break;
                    }
                }

                panelChat.scrollTop = panelChat.scrollHeight;
            }

            function sendChat() {
                btnPrompt.style.display = "none";

                chat.push({
                    role: "user",
                    content: inputChat.value
                });

                inputChat.value = "";
                renderChats();

                fetch("api/chat/", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        messages: chat
                    })
                }).then(response => response.json()).then(data => {
                    console.log(data);
                    chat.push(data.choices[0].message);
                    renderChats();
                });
            }

            btnSend.onclick = () => {
                sendChat();
            }

            inputChat.onkeydown = (e) => {
                if (e.key === "Enter") {
                    sendChat();
                }
            };

            btnPrompt.onclick = () => {
                inputChat.value = "Can you adjust the recipe for 2 people?";
                sendChat();
            };
        </script>
    </body>
</html>