<?php
$tweets = [
    ];


$tweetCount = rand(1, 10);
$count = 0;
for ($i = 0; $i < $tweetCount; $i++) {
    $response = file_get_contents("https://baconipsum.com/api/?type=all-meat&paras=1&start-with-lorem=0");
    $json = json_decode($response, true);
    $lorem = $json[0];

    $response = file_get_contents("https://randomuser.me/api/");
    $json = json_decode($response, true);
    $user = $json['results'][0];

    $isdeleted = (rand(0, 1)==1) ? true : false;
    ($isdeleted) ? $count++ : null;

    $tweet = [
        "message" => $lorem,
        "user" => $user['name']['first'] . " " . $user['name']['last'],
        "avatar" => $user['picture']['large'],
        "date" => date("d/m/Y"),
        "retweets" => rand(0, 10000),
        "likes" => rand(0, 10000),
        "is_deleted" => $isdeleted,
    ];
    $tweets[] = $tweet;
}

    function isValidTweet($tweet): bool {
        return !$tweet["is_deleted"];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Radio+Canada:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            color: #FFFFFF;
            font-family: 'Radio Canada', sans-serif;
        }

        .tweet {
            display: flex;
            flex-direction: column;

            width: 500px;
            height: auto;
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
            background-color: #090f2d;
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
            height: 200px;
        }

        input:focus {
            outline: none;
        }

        button {
            transition: all 0.3s;
            background-color: #2d0909;
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
            color: #090f2d;
        }
    </style>
</head>

<body>
    <?php include 'include/header.php' ?>
    <div class="feed">
        <h2>Feed </h2>
        <h3><?php echo $count?> tweet masqués</h3>
        <form action="">
            <input type="text" name="message" placeholder="Type a tweet">
            <button type="submit">Tweet</button>
        </form>

        <?php foreach($tweets as $tweet): ?>
        <?php if(isValidTweet($tweet)): ?>
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
                <p class="stats" onclick="addStat(this)"><span class="material-icons">recycling</span> <span
                        class="count">
                        <?php echo $tweet['retweets']; ?>
                    </span></p>
                <p class="stats" onclick="addStat(this)"><span class="material-icons">favorite</span> <span
                        class="count">
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
    <script>
        function addStat(e) {
            if (e.children[0].classList.contains('filled')) {
                e.children[0].classList.remove('filled');
                e.children[1].innerHTML = parseInt(e.children[1].innerHTML) - 1;
            } else {
                e.children[0].classList.add('filled');
                e.children[1].innerHTML = parseInt(e.children[1].innerHTML) + 1;
            }
        }

    </script>
</body>

</html>