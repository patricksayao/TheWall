<?php
session_start();
require_once('new-connection.php');

//--------------------------------------------------------//
//----------------- functions ----------------------------//
//--------------------------------------------------------//
function login_user($post)  {
    $errors = array();
    $email = $_POST['email'];
    $salt = '123';  // the value 123 changes randomly
    $encrypted_password = md5($_POST['password'] . ' ' . $salt);
    $query =    "SELECT * FROM users WHERE users.password = '$encrypted_password'
                AND users.email = '$email'";
    $user = fetch_all($query);
    if (count($user) > 0) {
        $_SESSION['user_id'] = $user[0]['id'];
        $_SESSION['first_name'] = $user[0]['first_name'];
        $_SESSION['logged_in'] = TRUE;
        header('location: main.php');
    } 
    else {
        // $errors[] = 'user not found';
        $_SESSION['msg'] = 'user not found';
        // echo $_SESSION['msg'];
        // same as $_SESSION['errors'] = array()
        header('location: index.php');
        die();
    }
}

function register_user($post) {
    $errors = array();
    if (empty($_POST['first_name'])) {
        $errors[] = 'first name cannot be blank';
    } 
    else if (!empty($_POST['first_name'])) {
        if (strlen($_POST['first_name']) < 3 ) {
            $errors[] = 'must be 2 characters long';
        }
        if (is_numeric($_POST['first_name'])) {
            $errors[] = 'first name cannot contain numeric value/s';
        }
    }
    if (empty($_POST['last_name'])) {
        $errors[] = 'last name cannot be blank';
    } 
    else if (!empty($_POST['last_name'])) {
        if (strlen($_POST['last_name']) < 3) {
            $errors[] = 'must be 2 characters long';
        }
        if (is_numeric($_POST['last_name'])) {
            $errors[] = 'last name cannot contain numeric values/s';
        }
    }
    if (empty($_POST['email'])) {
        $errors[] = 'email cannot be blank';
    } 
    else if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'email must be valid';
        }
    }
    if (empty($_POST['password'])) {
        $errors[] = 'password cannot be blank';
    } 
    else if (!empty($_POST['password'])) {
        if (strlen($_POST['password']) < 8) {
            $errors[] = 'password must be 8 characters long';
        }
    }
    if (empty($_POST['confirm_password'])) {
        $errors[] = 'please confirm password';
    } 
    else if (!empty($_POST['confirm_password'])) {
        if ($_POST['confirm_password'] !== $_POST['password']) {
            $errors[] = 'password must be the same';
        }
    }
    if (count($errors) > 0) {
        $_SESSION['$errors'] = $errors;
        header('location: index.php');
        die();
    } 
    else {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        // $password = $_POST['password'];
        $salt = '123';  // the value 123 changes randomly
        $encrypted_password = md5($_POST['password'] . ' ' . $salt);

        $query =    "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) 
                    VALUES ('$first_name','$last_name','$email','$encrypted_password',NOW(),NOW())";
        if (run_mysql_query($query)) {
            $_SESSION['msg'] = 'Succesfully Registered - <br /> - Please Login';
        } else {
            $_SESSION['msg'] = 'Registration Unsuccessful';
            // $_SESSION['msg'] = "Error: " . $query . "" . mysqli_error($connection);
        }
        header('location: index.php');
        die();
    }
}

function user_message($post) {
    if (empty($_POST['message'])) {
        $_SESSION['msg_message'] = 'message must be longer than "1" character';
    }
    else {
        $message = $_POST['message'];
        $user = $_SESSION['user_id'];

        $query =    "INSERT INTO messages (user_id, message, created_at, updated_at)
                    VALUES ('$user','$message',NOW(),NOW())";
        
        run_mysql_query($query);
        //$_SESSION['message_id'] = run_mysql_query($query)[0]['id'];
        //echo $_SESSION['message_id'];
        header('location: main.php');
    }
}

function user_set_comment($post) {
    if (empty($_POST['comment'])) {
        $_SESSION['msg_comment'] = 'comment must be longer than "1" character';
    }
    else {
        $comment = $_POST['comment'];
        $user = $_SESSION['user_id'];
        $message = $_POST['message_id'];

        $query =    "INSERT INTO comments (message_id, user_id, comment, created_at, updated_at)
                    VALUES ('$message','$user','$comment',NOW(),NOW())";
        
        // run_mysql_query($query);
        run_mysql_query($query);
        header('location: main.php');
    }
}

//--------------------------------------------------------//
//----------------- post methods -------------------------//
//--------------------------------------------------------//
if (isset($_POST['action']) AND $_POST['action'] == 'Register') {
    register_user($_POST);
}
else if (isset($_POST['action']) AND $_POST['action'] == 'Login') {
    login_user($_POST);
}
else if (isset($_POST['action']) AND $_POST['action'] == 'Message') {
    user_message($_POST);
}
else if (isset($_POST['action']) AND $_POST['action'] == 'Comment') {
    user_set_comment($_POST);
}
else {
    // echo "what's happening?";
    session_destroy();
    //session_unset();
    // unset($_SESSION['logged_in']);
    header('location: index.php');
    die();
}
