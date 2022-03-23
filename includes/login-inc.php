<?php

if (isset($_POST['submit'])) {
    // Add database connection when LOGIN button is clicked
    require 'server.php';

    // store username and password from the form in login.php
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check for empty fields
        // if username field is empty
    if (empty($username)) {
        header("Location: ../login.php?error=emptyfield&username");
        exit();
        // if password field is empty
    } elseif (empty($password)) {
        header("Location: ../login.php?error=emptyfield&username");
        exit();


    } else {
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            header("Location: ../login.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // if we can fetch such username, check password
            if ($row = mysqli_fetch_assoc($result)) {
                // password_verify returns a boolean checking hashed passwords
                // $passCheck = password_verify($password, $row['password']);
                // if password do not match
                if (strcmp($password,$row['password']) != 0) {
                    header("Location: ../login.php?wrongpassword");
                    exit();
                // if password match start the SESSION and save SESSION global variables
                } elseif (strcmp($password,$row['password']) == 0) {
                    session_start();
                    $_SESSION['sessionId'] = $row['userId'];
                    $_SESSION['sessionUser'] = $row['username'];
                    $_SESSION['sessionRole'] = $row['roleId'];
                    header("Location: ../index.php?success=loggedin");
                    exit();
                } else {
                    header("Location: ../login.php?wrongsomething");
                    exit();
                }
            } else {
                header("Location: ../login.php?error=nosuchuser");
                exit();
            }   
        }
    }
} else {
    header("Location: ../index.php?error=accessforbidden");
    exit();
}

?>