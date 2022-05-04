<?php
session_start();
$mysqli = new PDO('mysql:host=localhost:3306;dbname=php_twitter', 'root', 'cece3007');


$stmt = $mysqli->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(array(
    ':username' => $_POST['username']
));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['user'] = $user['id'];
$_SESSION['already_connected'] = 'yes';
if ($user) {
    header('Location: twitr.php');
} else {
    header('Location: connect.php');
}
