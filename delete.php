<?php
session_start();

include_once 'new-connection.php';
$id = $_GET['id'];  // gets the id from the anchor tag

// --------- deleting comments first --------------- //
$query =    "DELETE FROM comments WHERE message_id = " . $id;
run_mysql_query($query);

// --------- deletes messages second -------------- //
$query =    "DELETE FROM messages WHERE id = " . $id;
run_mysql_query($query);

header('Location: main.php');
die();
?>