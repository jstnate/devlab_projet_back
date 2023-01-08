<?php
    session_start();
    require_once 'class/connection.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
    }
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
    <?php
        $connection = new Connection();
        $profil = $connection->getUserById($_GET['user-id']);
        $result = $connection->getPublicAlbums($_GET['user-id']);
        echo $_GET['user-id'];
        echo $_SESSION['user_id'];

        if ($_GET['user-id'] != $_SESSION['user_id']) {
            if ($profil) { ?>
                <h1><?= $profil['pseudo'] ?></h1>
                <p><?= $profil['first_name'] . ' ' . $profil['last_name'] ?></p>
            <?php }
            if ($result) { ?>
                <h2>Ses albums :</h2>
                <?php foreach ($result as $album) { ?>
                    <a href="view-album.php?album-id=<?= $album['id']?>" ><?= $album['name'] ?></a>
                    <?php if ($album['name'] !== 'Films visionés' && $album['name'] !== 'Ma liste') { ?>
                        <form method="POST">
                            <input type="hidden" name="album-id" value="<?= $album['id']?>">
                            <button type="submit">Aimer l'album</button>
                        </form>
                    <?php }?>
                <?php }
            }

            $likes = $connection->getLikedAlbums($_GET['user-id']);
            if ($likes) { ?>
                <h2>Ses albums likés :</h2>
                <?php foreach ($likes as $like) { ?>
                    <a href="view-album.php?album-id=<?= $like['id']?>" ><?= $like['name'] ?></a>
                    <?php if ($like['name'] !== 'Films visionés' && $like['name'] !== 'Ma liste') { ?>
                        <form method="POST">
                            <input type="hidden" name="album-id" value="<?= $like['id']?>">
                            <button type="submit">Aimer l'album</button>
                        </form>
                    <?php }?>
                <?php }
            }

            if (isset($_POST['album-id'])) {
                $liking = $connection->albumLike($_POST['album-id'], $_SESSION['user_id']);

                if ($liking) {
                    header('Refresh:0');
                }
            }
        } else {
            echo "C'est ton compte";
        }
    ?>
</body>
</html>
