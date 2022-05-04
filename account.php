<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['already_connected'])) {
    header('Location: signup.php');
}
if (!isset($_SESSION['user']) && isset($_SESSION['already_connected'])) {
    header('Location: connect.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Raleway, sans-serif;
        }

        body {
            background: linear-gradient(90deg, #C7C5F4, #776BCC);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: white;
            width: 400px;
            height: 600px;
            border-radius: 12.5px;
        }
    </style>
</head>

<body>
    <div class="card">
    </div>
</body>

</html>