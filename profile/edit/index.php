<?php

chdir("../../");
require_once "common.php";
$user = getUser();

if ($user == false) {
    alert("You are not logged in");
}

?>

<html>
    <head>
        <title>
            Edit Profile
        </title>
        <base href="../../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("Edit Profile", "Edit your profile") ?>
        <div style="
            display: grid;
            grid-template-columns: 1fr max-content 1fr">
            <div></div>
            <div style="
                width: 30rem;">
                <form action="server.php"
                    method="post"
                    enctype="multipart/form-data">
                    <div style="
                        padding: 1rem;
                        font-size: 2rem;
                        font-weight: bold;
                        text-align: center;">
                        Edit Profile
                    </div>
                    <div style="
                        padding: 1rem;">
                        First name
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input name="firstname"
                            value="<?= htmlentities($user["firstname"]) ?>">
                    </div>
                    <div style="
                        padding: 1rem;">
                        Last name
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input name="lastname"
                            value="<?= htmlentities($user["lastname"]) ?>">
                    </div>
                    <div style="
                        padding: 1rem;">
                        Phone number
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input name="phone"
                            value="<?= htmlentities($user["phone"]) ?>">
                    </div>
                    <div style="
                        padding: 1rem;
                        text-align: center;">
                        <button style="
                            background-color: #a00;
                            color: #fff;"
                            name="method"
                            value="edit_profile">
                            Save
                        </button>
                    </div>
                </form>
                <form action="server.php"
                    method="post"
                    enctype="multipart/form-data">
                    <div style="
                        padding: 1rem;
                        padding-top: 5rem;
                        font-size: 2rem;
                        font-weight: bold;
                        text-align: center;">
                        Change password
                    </div>
                    <div style="
                        padding: 1rem;">
                        Old password
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input type="password"
                            name="oldpassword">
                    </div>
                    <div style="
                        padding: 1rem;">
                        New password
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input type="password"
                            name="newpassword">
                    </div>
                    <div style="
                        padding: 1rem;">
                        Confirm password
                    </div>
                    <div style="
                        padding: 1rem;
                        padding-top: 0rem;
                        text-align: center;">
                        <input type="password"
                            name="repassword">
                    </div>
                    <div style="
                        padding: 1rem;
                        text-align: center;">
                        <button style="
                            background-color: #a00;
                            color: #fff;"
                            name="method"
                            value="change_password">
                            Save
                        </button>
                    </div>
                </form>
            </div>
            <div></div>
        </div>
        <?= renderFooter() ?>
    </body>
</html>