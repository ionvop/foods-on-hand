<?php

chdir("../");
require_once "common.php";

?>

<html>
    <head>
        <title>
            Login
        </title>
        <base href="../">
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

        </style>
    </head>
    <body>
        <?= renderHeader("Login", "Become a member") ?>
        <div style="
            display: grid;
            grid-template-columns: repeat(2, 1fr);"
            id="panelPosts">
            <form style="
                display: grid;
                grid-template-rows: repeat(3, max-content) 1fr, max-content;
                height: 100%;
                box-sizing: border-box;
                padding: 5rem;"
                action="server.php"
                method="post"
                enctype="multipart/form-data">
                <div style="
                    padding: 1rem;
                    text-align: center;
                    font-size: 2rem;
                    font-weight: bold;">
                    Login
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="username"
                        placeholder="Username"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="password"
                        type="password"
                        placeholder="Password"
                        required>
                </div>
                <div></div>
                <div style="
                    padding: 1rem;
                    padding-top: 5rem;
                    text-align: center;">
                    <button style="
                        background-color: #a00;
                        color: #fff;"
                        name="method"
                        value="login">
                        Login
                    </button>
                </div>
            </form>
            <form style="
                display: grid;
                grid-template-rows: repeat(7, max-content) 1fr, max-content;
                height: 100%;
                box-sizing: border-box;
                padding: 5rem;"
                action="server.php"
                method="post"
                enctype="multipart/form-data">
                <div style="
                    padding: 1rem;
                    text-align: center;
                    font-size: 2rem;
                    font-weight: bold;">
                    Register
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="firstname"
                        placeholder="First name"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="lastname"
                        placeholder="Last name"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="username"
                        placeholder="Username"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        type="tel"
                        name="phone"
                        placeholder="Phone number"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="password"
                        type="password"
                        placeholder="Password"
                        required>
                </div>
                <div style="
                    padding: 1rem;">
                    <input style="
                        background-color: transparent;
                        border: none;
                        border-bottom: 1px solid #000;"
                        name="repassword"
                        type="password"
                        placeholder="Confirm Password"
                        required>
                </div>
                <div></div>
                <div style="
                    padding: 1rem;
                    padding-top: 5rem;
                    text-align: center;">
                    <button style="
                        background-color: #a00;
                        color: #fff;"
                        name="method"
                        value="register">
                        Register
                    </button>
                </div>
            </form>
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