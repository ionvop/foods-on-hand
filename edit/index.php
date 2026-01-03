<?php

chdir("../");
require_once "common.php";
$user = getUser();

if ($user == false) {
    alert("You are not logged in.");
}

$db = new SQLite3("database.db");

$query = <<<SQL
    SELECT * FROM `recipes` WHERE id = :id
SQL;

$stmt = $db->prepare($query);
$stmt->bindValue(":id", $_GET["id"]);
$recipe = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($recipe["user_id"] != $user["id"]) {
    alert("You are not the owner of this recipe.");
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
        <?= renderHeader("Edit Recipe", "Edit a recipe") ?>
        <form style="
            display: grid;
            grid-template-columns: 2fr 1fr;"
            data-mobile="
            grid-template-columns: minmax(0, 1fr);"
            action="server.php"
            method="post"
            enctype="multipart/form-data">
            <div>
                <div style="
                    padding: 1rem;
                    font-size: 2rem;
                    font-weight: bold;">
                    Edit Recipe
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="title"
                        placeholder="Title"
                        value="<?= htmlentities($recipe["title"]) ?>"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <textarea style="
                        height: 30rem;"
                        name="content"
                        placeholder="Content (Markdown supported)"
                        required><?= htmlentities($recipe["content"]) ?></textarea>
                </div>
            </div>
            <div style="
                display: grid;
                grid-template-rows: repeat(2, max-content) 1fr repeat(2, max-content);
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
                        <option value="pork"
                            <?= $recipe["category"] == "pork" ? "selected" : "" ?>>
                            Pork
                        </option>
                        <option value="chicken"
                            <?= $recipe["category"] == "chicken" ? "selected" : "" ?>>
                            Chicken
                        </option>
                        <option value="beef"
                            <?= $recipe["category"] == "beef" ? "selected" : "" ?>>
                            Beef
                        </option>
                        <option value="fish"
                            <?= $recipe["category"] == "fish" ? "selected" : "" ?>>
                            Fish
                        </option>
                        <option value="vegetable"
                            <?= $recipe["category"] == "vegetable" ? "selected" : "" ?>>
                            Vegetable
                        </option>
                        <option value="seafood"
                            <?= $recipe["category"] == "seafood" ? "selected" : "" ?>>
                            Seafood
                        </option>
                    </select>
                </div>
                <div></div>
                <div style="
                    padding: 1rem;
                    text-align: center;">
                    <button style="
                        background-color: #a00;
                        color: #fff;"
                        name="method"
                        value="delete"
                        id="btnDelete">
                        Delete
                    </button>
                </div>
                <div style="
                    padding: 1rem;
                    text-align: center;">
                    <button style="
                        background-color: #a00;
                        color: #fff;"
                        name="method"
                        value="edit">
                        Save
                    </button>
                </div>
            </div>
            <input type="hidden"
                name="id"
                value="<?= $recipe["id"] ?>">
        </form>
        <?= renderFooter() ?>
        <script src="script.js"></script>
        <script>
            const btnDelete = document.getElementById("btnDelete");

            btnDelete.onclick = () => {
                return confirm("Are you sure you want to delete this recipe?");
            }
        </script>
    </body>
</html>