<?php
    session_start();
    require_once "class/user.php";
    require_once 'class/connection.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="email" name="email" placeholder="enter ur mail">
        <input type="password" name="password" placeholder="enter ur password">
        <button type="submit" name="register">Log toi FDP</button>
    </form>

    <?php
        if($_POST){

            $connection = new Connection();
            $email = $_POST['email'];
            $user = $connection->emailVerify($email);

            if ($user) {
                if (md5($_POST['password'] . 'SALT') === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['pseudo'];
                    $getLiked = $connection->getAlbum('Films Aimés');
                    $getWatched = $connection->getAlbum('Films visionés');
                    $_SESSION['liked'] = $getLiked['id'];
                    $_SESSION['watched'] = $getWatched['id'];
                    header('Location: index.php');
                }
            } else {
                echo '<h2>Erreur interne, veuillez reéssayer ultérieurement</h2>';
            }

        }
    ?>
</body>
</html>
