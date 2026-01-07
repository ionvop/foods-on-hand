<?php

/**
 * Prints the given message and exits the script.
 *
 * @param mixed $message The message to be printed.
 * @return void
 */
function breakpoint($message) {
    header("Content-type: application/json");
    print_r($message);
    exit;
}

/**
 * Prints the given message as an alert and redirects the user.
 *
 * @param mixed $message The message to be displayed.
 * @param string $redirect The URL to redirect the user to. If empty, the user will be redirected back.
 * @return void
 */
function alert($message, $redirect = "") {
    $message = json_encode($message);

    $redirectScript = <<<JS
        window.history.back();
    JS;
    
    if ($redirect != "") {
        $redirect = json_encode($redirect);

        $redirectScript = <<<JS
            location.href = {$redirect};
        JS;
    }

    echo <<<HTML
        <script>
            alert({$message});
            {$redirectScript}
        </script>
    HTML;

    exit;
}

function getUser() {
    $db = new SQLite3("database.db");

    if (isset($_COOKIE["session"]) == false) {
        return false;
    }

    $query = <<<SQL
        SELECT * FROM `users` WHERE `session` = :session
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":session", $_COOKIE["session"]);
    $user = $stmt->execute()->fetchArray();

    if ($user == false) {
        return false;
    }

    if ($user["session"] == null) {
        return false;
    }

    return $user;
}

function renderHeader($title, $description, $descriptionLink = false) {
    $title = str_replace("\n", "<br>", $title);
    $description = str_replace("\n", "<br>", $description);
    $user = getUser();

    $login = <<<HTML
        <a style="
            display: block;
            padding: 3rem;
            color: #fff;
            font-weight: bold;
            cursor: pointer;"
            data-mobile="
            padding: 1rem;"
            class="g_panelTab"
            href="login/">
            LOGIN
        </a>
    HTML;

    if ($user != false) {
        $login = <<<HTML
            <a style="
                display: block;
                padding: 3rem;
                color: #fff;
                font-weight: bold;
                cursor: pointer;"
                data-mobile="
                padding: 1rem;"
                class="g_panelTab"
                href="profile/?id={$user['id']}">
                PROFILE
            </a>
        HTML;
    }

    if ($descriptionLink == false) {
        $descriptionBlock = <<<HTML
            <div style="
                padding: 5rem;
                padding-top: 0rem;
                font-size: 1.5rem;
                color: #fff;">
                {$description}
            </div>
        HTML;
    } else {
        $descriptionBlock = <<<HTML
            <div style="
                padding: 5rem;
                padding-top: 0rem;
                font-size: 1.5rem;
                color: #fff;">
                <a style="
                    display: inline-block;"
                    href="{$descriptionLink}">
                    {$description}
                </a>
            </div>
        HTML;
    }

    return <<<HTML
        <div style="
            height: 99vh;
            position: relative;
            background-image: url(assets/bg.jpg);
            background-size: cover;
            background-attachment: fixed;
            background-position: center;"
            data-mobile="
            height: 45rem;
            background-attachment: scroll;
            background-size: cover;"
            id="g_panelBackground">
            <div>
                <div style="
                    display: grid;
                    grid-template-columns: 1fr repeat(3, max-content) 5rem;
                    background-color: #a00;"
                    data-mobile="
                    grid-template-columns: 1fr;
                    text-align: center;">
                    <div></div>
                    <a style="
                        display: block;
                        padding: 3rem;
                        color: #fff;
                        font-weight: bold;
                        cursor: pointer;"
                        data-mobile="
                        padding: 1rem;"
                        class="g_panelTab"
                        href="./">
                        HOME
                    </a>
                    <a style="
                        display: block;
                        padding: 3rem;
                        color: #fff;
                        font-weight: bold;
                        cursor: pointer;"
                        data-mobile="
                        padding: 1rem;"
                        class="g_panelTab"
                        href="recipes/">
                        RECIPES
                    </a>
                    {$login}
                    <div></div>
                </div>
                <div style="
                    padding: 5rem;
                    padding-top: 15rem;
                    padding-bottom: 1rem;
                    font-size: 5rem;
                    font-weight: bold;
                    color: #fff;"
                    data-mobile="
                    padding-top: 5rem;">
                    {$title}
                </div>
                {$descriptionBlock}
            </div>
            <div style="
                position: absolute;
                left: 0rem;
                top: 0rem;
                padding: 1rem;">
                <img style="
                    width: 15rem;
                    height: 15rem;
                    object-fit: contain;"
                    data-mobile="
                    display: none;"
                    src="assets/logo.png">
            </div>
        </div>
    HTML;
}

function renderFooter() {
    return <<<HTML
        <div style="
            padding: 5rem;
            background-color: #555;
            color: #fff;
            text-align: center;">
            <div style="
                padding: 1rem;
                font-weight: bold;">
                Contact Us
            </div>
            <div style="
                padding: 1rem;">
                jadepadayhag4444@gmail.com
            </div>
            <div style="
                padding: 1rem;">
                +63 951 1054 683
            </div>
        </div>
    HTML;
}

function renderRecipe($recipe) {
    $db = new SQLite3("database.db");

    $categoryMap = [
        "pork" => "Pork",
        "chicken" => "Chicken",
        "beef" => "Beef",
        "fish" => "Fish",
        "vegetable" => "Vegetable",
        "seafood" => "Seafood"
    ];

    $src = "uploads/" . $recipe["image"];
    $title = htmlentities($recipe["title"]);
    $category = $categoryMap[$recipe["category"]];

    $query = <<<SQL
        SELECT * FROM `users` WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $recipe["user_id"]);
    $author = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    echo <<<HTML
        <a style="
            padding: 1rem;
            padding-top: 0rem;"
            href="recipe/?id={$recipe['id']}">
            <div style="
                height: 10rem;">
                <img style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;"
                    src="{$src}">
            </div>
            <div style="
                display: grid;
                grid-template-rows: 1fr max-content;">
                <div style="
                    padding: 1rem;
                    font-size: 1.5rem;
                    overflow: hidden;">
                    {$title}
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;
                    font-size: 0.7rem;
                    color: #555;">
                    Author: {$author["firstname"]} {$author["lastname"]}
                </div>
                <div style="
                    padding: 1rem;
                    padding-top: 0rem;
                    font-size: 0.7rem;
                    color: #555;">
                    Category: {$category}
                </div>
            </div>
        </a>
    HTML;
}