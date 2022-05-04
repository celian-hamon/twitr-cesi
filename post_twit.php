<?php
$mysqli = new PDO('mysql:host=localhost:3306;dbname=php_twitter', 'root', 'cece3007');

$stmt = $mysqli->prepare("INSERT INTO tweets (message, author) VALUES (:message, :author)")->execute(array(
    ':message' => $_POST['message'],
    ':author' => $_POST['author']
));

header('Location: twitr.php');
