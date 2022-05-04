<?php
session_start();
$mysqli = new PDO('mysql:host=localhost:3306;dbname=php_twitter', 'root', 'cece3007');

$stmt = $mysqli->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(array(
    ':username' => $_POST['username']
));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user == null) {
    $mysqli->prepare("INSERT INTO users (username,avatar) VALUES (:username,:avatar)")->execute(array(
        ':username' => $_POST['username'],
        ':avatar' => $_POST['avatar']
    ));
    header('Location: connect.php');
} else {
    header('Location: connect.php');
}
