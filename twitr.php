<?php
$tweets = [];
session_start();
$userId = $_SESSION['user'];
if ($userId == null) {
    header("Location: account.php");
}

$mysqli = new PDO('mysql:host=localhost:3306;dbname=php_twitter', 'root', 'cece3007');

//retreive current user from db
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = :userId");
$stmt->execute(array(':userId' => $userId));
$userSession = $stmt->fetchAll();

//retrive tweets from the db
$stmt = $mysqli->prepare("SELECT * FROM tweets ORDER BY date DESC LIMIT 100");
$stmt->execute();
$tweetsResult = $stmt->fetchAll();

for ($i = 0; $i < count($tweetsResult); $i++) {
    //retreive likes, and retweets for each tweet
    $stmt = $mysqli->prepare("SELECT * FROM likes WHERE tweetid = :tweetId");
    $stmt->execute(array(':tweetId' => $tweetsResult[$i]['id']));
    $likes = $stmt->fetchAll();

    $stmt = $mysqli->prepare("SELECT * FROM retweets WHERE tweetid = :tweetId");
    $stmt->execute(array(':tweetId' => $tweetsResult[$i]['id']));
    $retweets = $stmt->fetchAll();

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = :tweetAuthorId");
    $stmt->execute(array(':tweetAuthorId' => $tweetsResult[$i]['author']));
    $user = $stmt->fetchAll();

    for ($j = 0; $j < count($likes); $j++) {
        if ($likes[$j]['userid'] == $user[0]['id']) {
            $liked = true;
        }
    }
    for ($j = 0; $j < count($retweets); $j++) {
        if ($retweets[$j]['userid'] == $user[0]['id']) {
            $retweeted = true;
        }
    }
    $tweets[] = [
        'id' => $tweetsResult[$i]['id'],
        "message" => $tweetsResult[$i]['message'],
        "user" => $user[0]['username'],
        "avatar" => $user[0]['avatar'],
        "date" => $tweetsResult[$i]['date'],
        "retweets" => count($retweets),
        "likes" => count($likes),

        "is_deleted" => false,
    ];
}

function isValidTweet($tweet): bool
{
    return !$tweet["is_deleted"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitr</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/000000/php.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Radio+Canada:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            color: #FFFFFF;
            margin: 0;
            padding: 0;
            font-family: Raleway, sans-serif;
        }

        .tweet {
            display: flex;
            flex-direction: column;

            background-color: rgba(255, 255, 255, .15);
            border: #C7C5F4AA solid 2px;
            backdrop-filter: blur(5px);

            overflow-wrap: break-word;
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .row {
            padding: 4px;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-end;
        }

        .row img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .row h2 {
            margin-bottom: 10px;
        }

        .row p.stats {
            padding-left: 10px;
        }

        body {
            background: linear-gradient(90deg, #C7C5F4, #776BCC);
            font-size: 1.1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

        }

        .feed {
            width: 50%;
        }

        .chars {
            border-radius: 50px;
            padding: 5px;
            margin-left: 10px;
            border: 1px solid #FFFFFF;
            opacity: 0.5;
        }

        input {
            background: transparent;
            border: none;
            width: 100%;
            height: 40px;
            border-bottom: 3px solid #fff;
            transition: border-bottom .3s;
        }

        input:focus {
            outline: none;
            border-bottom: 3px solid #5D54A4;
        }

        input:focus {
            outline: none;
        }

        button {
            transition: all 0.3s;
            background-color: #5D54A4;
            border: none;
            color: #FFFFFF;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #ffffff;
            color: #5D54A4;
        }

        nav {
            background-color: rgba(255, 255, 255, .15);
            backdrop-filter: blur(5px);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 50px;
            position: sticky;
            align-self: flex-start;
            border-radius: 0 0 10px 10px;
        }


        nav>h2 {
            padding: 10px;
        }

        nav>ul {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: center;

            width: 100%;
            height: 50px;
        }

        nav>ul>li {
            text-decoration: none;
            list-style: none;
            padding: 10px;
            margin-right: 10px;
            border-radius: 10px;
            transition: all 0.3s;

        }

        nav>ul>li>a {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
        }

        #userId {
            display: none;
        }

        #draft {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        #draft>button {
            margin: 4px;
        }
    </style>
</head>

<body>
    <?php include 'include/header.php' ?>
    <div class="feed">
        <h2 style="margin-bottom:15px;margin-top:15px;">Feed</h2>
        <div class="tweet">
            <div class='row'>
                <?php echo "<img src='" . $userSession[0]["avatar"] . "' alt=''>"; ?>
                <form action="post_twit.php" method="post" id="draft">
                    <input type="number" id="userId" autocomplete="off" name="author" placeholder="1" value="<?php echo $userId ?>">
                    <input type="text" autocomplete="off" name="message" placeholder="">
                    <button type="submit">Tweet</button>
                </form>
            </div>
        </div>

        <?php foreach ($tweets as $tweet) : ?>
            <?php if (isValidTweet($tweet)) : ?>
                <div class="tweet">
                    <div class="row">
                        <img src="<?php echo $tweet['avatar']; ?>" alt="<?php echo $tweet['user']; ?>">
                        <h2>
                            <?php echo $tweet['user']; ?>
                        </h2>
                    </div>
                    <div class="row">
                        <p>
                            <?php echo $tweet['message']; ?>
                        </p>
                    </div>
                    <div class="row">
                        <p>
                            <?php echo $tweet['date']; ?>
                        </p>
                        <p class="stats" onclick="addStat(this)"><span class="material-icons">recycling</span> <span class="count">
                                <?php echo $tweet['retweets']; ?>
                            </span></p>
                        <p class="stats" id="<?php echo "like-" . $tweet['id'] ?>"><span class="material-icons">favorite</span> <span class="count">
                                <?php echo $tweet['likes']; ?>
                            </span></p>
                        <p class="chars">
                            <?php echo strlen($tweet['message']) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>

</html>