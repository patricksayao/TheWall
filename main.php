<?php
session_start();
// include_once 'process.php';
include_once 'new-connection.php';

?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="main">
            <div class="header">
                <h1><span>T</span>he<span>W</span>all</h1>
                <h3>Welcome <span><?= $_SESSION['first_name']; ?></span></h3>
                <a href="process.php">log out</a>
            </div>
            <div class="content">
                <h2>Post a message</h2>
                <form action="process.php" method="post">
                    <input type="hidden" name="action" value="Message">
                    <textarea name="message" id="" cols="98" rows="10"></textarea>
                    <input type="submit" value="Post a message">
                </form>
<!-- -------------------------- messages ------------------------- -->                
<?php           $query =    "SELECT 
                                CONCAT(users.first_name,' ',users.last_name) AS name, 
                                messages.message, DATE_FORMAT(messages.created_at, '%M %D %Y') AS date, 
                                messages.id 
                            FROM 
                                messages 
                            INNER JOIN 
                                users 
                            ON 
                                messages.user_id = users.id
                            ORDER BY
                                messages.id DESC";
                $results = fetch_all($query);
                
                foreach ($results as $result) { ?>
                    <h4><?= $result['name'] . ' - ' . $result['date']; ?> <a href="delete.php?id=<?= $result['id']; ?>"> delete message </a></h4>
                    <p><?= $result['message'];  ?></p>
                    
<!-- -------------------------- comments --------------------------------- -->
<?php               $query =    "SELECT 
                                    CONCAT(users.first_name, ' ', users.last_name) AS name, 
                                    comments.comment, 
                                    DATE_FORMAT(comments.created_at, '%M %D %Y') AS date, 
                                    messages.id
                                FROM 
                                    comments 
                                INNER JOIN
                                    users 
                                ON
                                    comments.user_id = users.id 
                                INNER join 
                                    messages 
                                ON
                                    comments.message_id = messages.id
                                WHERE
                                    messages.id=" . $result['id'] . ""; // the variable came from the above code in the messages section
                    $results_2 = fetch_all($query);
                    foreach ($results_2 as $result_2) { ?>
                        <div>
                            <h5><?= $result_2['name'] . ' - ' . $result_2['date'] ?></h5>
                            <p><?= $result_2['comment'] ?></p>
                        </div>
<?php               } ?>
                    <form class="comments" action="process.php" method="post">
                        <input type="hidden" name="action" value="Comment">
                        <input type="hidden" name="message_id" value="<?= $result['id']; ?>"> <!-- gets the id of the message / came from the messages section -->
                        <textarea name="comment" id="" cols="93" rows="3"></textarea>
                        <input type="submit" value="Post a comment">
                    </form>
<?php           } ?>
            </div>
        </div>
    </body>
</html>