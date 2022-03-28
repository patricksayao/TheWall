<?php
session_start();
// session_destroy();
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>the wall</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="index">
        <h1><span>T</span>he<span>W</span>all</h1>
        <div>
            <h2>Registration</h2>
            <form action="process.php" method="post">
                <input type="hidden" name="action" value="Register">
                <label for="first_name">First name: </label>
                <input type="text" name="first_name">
                <label for="last_name">Last name: </label>
                <input type="text" name="last_name">
                <label for="email">Email: </label>
                <input type="text" name="email">
                <label for="password">Password: </label>
                <input type="password" name="password">
                <label for="confirm_password">Confirm Password: </label>
                <input type="password" name="confirm_password">
                <input type="submit" class="submit" value="Register now">
            </form>
        </div>
        <div class="whitediv">
            <h2>Login</h2>
            <form action="process.php" method="post">
                <input type="hidden" name="action" value="Login">
                <label for="email">Email: </label>
                <input type="text" name="email">
                <label for="password">Password: </label>
                <input type="password" name="password">
                <input type="submit" class="submit" value="Login">
            </form>
        </div>
<?php   if (isset($_SESSION['$errors'])) {
            foreach ($_SESSION['$errors'] as $value) { ?>
                <h4>- <?= $value; ?> -</h4>
<?php       }
            session_destroy();
        } ?>
<?php   if (isset($_SESSION['$errors2'])) {
            foreach ($_SESSION['$errors2'] as $value) { ?>
                <h4>- <?= $value; ?> -</h4>
<?php       }
            session_destroy();
        } ?>
<?php   if (isset($_SESSION['msg'])) { ?>
            <h4>- <?= $_SESSION['msg']; ?> -</h4>
<?php   }
        unset($_SESSION['msg']);
        die(); ?>
    </div>
</body>

</html>