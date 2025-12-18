<?php

chdir("../");
require_once "common.php";
$user = getUser();

if ($user == false) {
    alert("You are not logged in.");
}

?>

<html>
    <head>
        <title>
            New Recipe
        </title>
        <base href="../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("New Recipe", "Create a new recipe") ?>
        <form style="
            display: grid;
            grid-template-columns: 2fr 1fr;"
            action="server.php"
            method="post"
            enctype="multipart/form-data"
            id="panelPosts">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    New Recipe
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="title"
                        placeholder="Title"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <textarea style="
                        height: 30rem;"
                        name="content"
                        placeholder="Content (Markdown supported)"
                        required></textarea>
                </div>
            </div>
            <div style="
                display: grid;
                grid-template-rows: repeat(4, max-content) 1fr max-content;
                height: 100%;
                box-sizing: border-box;">
                <div style="
                    padding: 1rem;">
                    Category
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;">
                    <select name="category">
                        <option value="pork">Pork</option>
                        <option value="chicken">Chicken</option>
                        <option value="beef">Beef</option>
                        <option value="fish">Fish</option>
                        <option value="vegetable">Vegetable</option>
                        <option value="seafood">Seafood</option>
                    </select>
                </div>
                <div style="
                    padding: 1rem;">
                    Upload image:
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;">
                    <input type="file"
                        name="image"
                        accept="image/*"
                        required>
                </div>
                <div></div>
                <div style="
                    padding: 1rem;
                    text-align: center;">
                    <button style="
                        background-color: #a00;
                        color: #fff;"
                        name="method"
                        value="post">
                        Submit
                    </button>
                </div>
            </div>
        </form>
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