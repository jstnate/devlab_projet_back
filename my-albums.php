<?php
    session_start();
    require_once "class/user.php";
    require_once "class/album.php";
    require_once "class/connection.php";
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
        $result = $connection->getAllAlbums();

        if ($result) {
            foreach ($result as $album) { ?>
                <a href="view-album.php?album-id=<?= $album['id']?>" ><?= $album['name'] ?></a>
            <?php }
        }
    ?>
</body>
</html>
