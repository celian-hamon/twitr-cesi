<?php ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <title>SIGNIN</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/000000/php.png">
</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="signup.php" method="POST">
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" name="username" autocomplete="off" class="login__input" placeholder="Username">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user-astronaut"></i>
                        <input type="text" name="avatar" autocomplete="off" class="login__input" placeholder="Avatar">
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Sign in Now</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
                <div class="social-login">
                    <a href="connect.php">
                        <h3>Bitches ? Login</h3>
                    </a>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</body>

</html>