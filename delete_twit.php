<?php
$mysqli = new PDO('mysql:host=localhost:3306;dbname=php_twitter', 'root', 'cece3007');

$mysqli->prepare("DELETE FROM tweets WHERE id = :id")->execute(array(
    ':id' => $_POST['tweetId']
));

header('Location: twitr.php');
