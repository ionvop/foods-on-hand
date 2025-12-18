<?php

require_once "common.php";

if (isset($_POST["method"])) {
    switch ($_POST["method"]) {
        case "register":
            register();
            break;
        case "login":
            login();
            break;
        case "logout":
            logout();
            break;
        case "post":
            post();
            break;
        case "edit":
            edit();
            break;
        case "delete":
            delete();
            break;
        case "comment":
            comment();
            break;
        case "delete_comment":
            deleteComment();
            break;
        case "edit_profile":
            editProfile();
            break;
        case "change_password":
            changePassword();
            break;
        default:
            defaultMethod();
            break;
    }
} else {
    defaultMethod();
}

function register() {
    $db = new SQLite3("database.db");

    if (strlen($_POST["username"]) < 4 || strlen($_POST["username"]) > 20) {
        alert("Username must be between 4 and 20 characters");
    }

    if (strlen($_POST["password"]) < 4) {
        alert("Password must be at least 4 characters");
    }

    if ($_POST["password"] != $_POST["repassword"]) {
        alert("Passwords do not match");
    }

    $query = <<<SQL
        SELECT * FROM `users` WHERE `username` = :username;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":username", $_POST["username"]);
    $user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($user != false) {
        alert("Username already exists");
    }

    $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $session = uniqid("session-");

    $query = <<<SQL
        INSERT INTO `users` (`firstname`, `lastname`, `username`, `phone`, `hash`, `session`) VALUES (:firstname, :lastname, :username, :phone, :hash, :session);
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":firstname", $_POST["firstname"]);
    $stmt->bindValue(":lastname", $_POST["lastname"]);
    $stmt->bindValue(":username", $_POST["username"]);
    $stmt->bindValue(":phone", $_POST["phone"]);
    $stmt->bindValue(":hash", $hash);
    $stmt->bindValue(":session", $session);
    $stmt->execute();
    setcookie("session", $session, time() + 86400);
    header("Location: ./");
}

function login() {
    $db = new SQLite3("database.db");

    $query = <<<SQL
        SELECT * FROM `users` WHERE `username` = :username;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":username", $_POST["username"]);
    $user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($user == false) {
        alert("Invalid credentials");
    }

    if (password_verify($_POST["password"], $user["hash"]) == false) {
        alert("Invalid credentials");
    }

    $session = uniqid("session-");

    $query = <<<SQL
        UPDATE `users` SET `session` = :session WHERE `id` = :id;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $user["id"]);
    $stmt->bindValue(":session", $session);
    $stmt->execute();
    setcookie("session", $session, time() + 86400);
    header("Location: ./");
}

function logout() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        UPDATE `users` SET `session` = NULL WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $user["id"]);
    $stmt->execute();
    setcookie("session", "", time() - 86400);
    header("Location: ./");
}

function post() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    if ($_FILES["image"]["error"] != 0) {
        alert("Failed to upload image.");
    }

    $filename = uniqid("image-") . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $filename) == false) {
        alert("Failed to upload image.");
    }

    $query = <<<SQL
        INSERT INTO `recipes` (`user_id`, `title`, `content`, `category`, `image`)
        VALUES (:user_id, :title, :content, :category, :image);
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":user_id", $user["id"]);
    $stmt->bindValue(":title", $_POST["title"]);
    $stmt->bindValue(":content", $_POST["content"]);
    $stmt->bindValue(":category", $_POST["category"]);
    $stmt->bindValue(":image", $filename);
    $stmt->execute();
    $id = $db->lastInsertRowID();
    header("Location: recipe/?id={$id}");
}

function edit() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        SELECT * FROM `recipes` WHERE id = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $recipe = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($recipe["user_id"] != $user["id"]) {
        alert("You are not the owner of this recipe.");
    }

    $query = <<<SQL
        UPDATE `recipes` SET `title` = :title, `content` = :content, `category` = :category WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $stmt->bindValue(":title", $_POST["title"]);
    $stmt->bindValue(":content", $_POST["content"]);
    $stmt->bindValue(":category", $_POST["category"]);
    $stmt->execute();
    header("Location: recipe/?id={$_POST["id"]}");
}

function delete() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        SELECT * FROM `recipes` WHERE id = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $recipe = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($recipe["user_id"] != $user["id"]) {
        alert("You are not the owner of this recipe.");
    }

    $query = <<<SQL
        DELETE FROM `recipes` WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $stmt->execute();
    header("Location: recipes/");
}

function comment() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        INSERT INTO `comments` (`user_id`, `recipe_id`, `content`)
        VALUES (:user_id, :recipe_id, :content);
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":user_id", $user["id"]);
    $stmt->bindValue(":recipe_id", $_POST["recipe_id"]);
    $stmt->bindValue(":content", $_POST["content"]);
    $stmt->execute();
    header("Location: recipe/?id={$_POST['recipe_id']}");
}

function deleteComment() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        SELECT * FROM `comments` WHERE id = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $comment = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($comment["user_id"] != $user["id"]) {
        alert("You are not the owner of this comment.");
    }

    $query = <<<SQL
        DELETE FROM `comments` WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_POST["id"]);
    $stmt->execute();
    header("Location: recipe/?id={$comment['recipe_id']}");
}

function editProfile() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    $query = <<<SQL
        UPDATE `users` SET `firstname` = :firstname, `lastname` = :lastname, `phone` = :phone WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $user["id"]);
    $stmt->bindValue(":firstname", $_POST["firstname"]);
    $stmt->bindValue(":lastname", $_POST["lastname"]);
    $stmt->bindValue(":phone", $_POST["phone"]);
    $stmt->execute();
    header("Location: profile/");
}

function changePassword() {
    $db = new SQLite3("database.db");
    $user = getUser();

    if ($user == false) {
        alert("You are not logged in.");
    }

    if (password_verify($_POST["oldpassword"], $user["hash"]) == false) {
        alert("Invalid credentials.");
    }

    if (strlen($_POST["newpassword"]) < 4) {
        alert("Password must be at least 4 characters.");
    }

    if ($_POST["newpassword"] != $_POST["repassword"]) {
        alert("Passwords do not match.");
    }

    $hash = password_hash($_POST["newpassword"], PASSWORD_DEFAULT);

    $query = <<<SQL
        UPDATE `users` SET `hash` = :hash WHERE `id` = :id
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $user["id"]);
    $stmt->bindValue(":hash", $hash);
    $stmt->execute();
    header("Location: profile/");
}

function defaultMethod() {
    breakpoint([
        "post" => $_POST,
        "files" => $_FILES
    ]);
}