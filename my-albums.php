<?php
    session_start();
    require_once "class/user.php";
    require_once "class/album.php";
    require_once "class/connection.php";

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
        $result = $connection->getAllAlbums($_SESSION['user_id']);

        if ($result) {
            foreach ($result as $album) { ?>
                <a href="view-album.php?album-id=<?= $album['id']?>" ><?= $album['name'] ?></a>
                <?php if ($album['name'] !== 'Films visionés' && $album['name'] !== 'Ma liste') { ?>
                    <form method="GET">
                        <input type="hidden" name="album-id" value="<?= $album['id'] ?>">
                        <input type="hidden" name="album-name" value="<?= $album['name'] ?>">
                        <button type="submit">Partager l'album</button>
                    </form>
                <?php }
            }
        }
    ?>
    <aside>
        <?php if (isset($_GET['album-id'])) {
            $share = $connection->getUsers();

            foreach ($share as $user) {
                if ($user['pseudo'] !== $_SESSION['user_name']) { ?>
                    <form method="POST">
                        <input type="hidden" name="share-id" value="<?= $user['id'] ?>">
                        <button type="submit"> <?= $user['pseudo'] ?></button>
                    </form>
                <?php }
            }

            if (isset($_POST['share-id'])) {
                $send = $connection->sendMessage($_SESSION['user_name'], $_GET['album-id'], $_GET['album-name'], $_POST['share-id']);

                if ($send) {
                    echo '<h2>Album partagé...</h2>';
                } else {
                    echo '<h2>Erreur interne veuillez réessayer...</h2>';
                }

            }
        } ?>
    </aside>
</body>
</html>
