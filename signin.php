<?php
    session_start();
    require_once 'class/user.php';
    require_once 'class/album.php';
    require_once 'class/connection.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Créer un compte</title>
</head>
<body>
    <div class="form">
        <h1>Créez un compte !</h1>

        <form method="POST">
            <div class="civilite">
                <div class="fname">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" placeholder="John">
                </div>

                <div class="lname">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" placeholder="Doe">
                </div>
            </div>

            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" placeholder="Pseudonyme">

            <label for="email">Email</label>
            <input type="email" name="email" placeholder="mail@gmail.com">

            <label for="password">Mot de passe</label>
            <input type="password" name="password" placeholder="Entrer votre mot de passe">

            <button type="submit">S'inscrire</button>
        </form>

        <a href="login.php">Se connecter</a>
    </div>

    <?php
        if ($_POST) {
            $user = new User(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['pseudo'],
                $_POST['email'],
                $_POST['password']
            );

            if ($user->inputVerify()) {
                $connection = new Connection();
                $insert = $connection->insertUser($user);

                $liked_album = new Album(
                    'Films aimés',
                    1
                );

                $visioned_album = new Album(
                    'Films visionés',
                    1
                );

                if ($insert) {
                    $request = $connection->getUserId($_POST['email']);
                    $_SESSION['user_id'] = $request['id'];
                    $liked = $connection->createAlbum($liked_album);
                    $visioned = $connection->createAlbum($visioned_album);
                    $getLiked = $connection->getAlbum('Films Aimés');
                    $getWatched = $connection->getAlbum('Films visionés');
                    $_SESSION['liked'] = $getLiked;
                    $_SESSION['watched'] = $getWatched;
                    header('Location: index.php');
                } else {
                    echo '<h2>Erreur interne, veuillez reéssayer ultérieurement</h2>';
                }
            }
        }
    ?>
</body>
</html>
