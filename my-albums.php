<?php
    session_start();
    require_once "class/user.php";
    require_once "class/album.php";
    require_once "class/connection.php";

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
    }
    if (isset($_POST['disconnect'])) {
        $connection = new Connection();
        $disconnect = $connection->disconnect();

        if ($disconnect) {
            header('Location: login.php');
        }
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
<body class="bg-[#121212] text-white py-[10vh] relative z-0">
    <header class="fixed top-0 left-0 w-screen bg-[#121212] flex p-[1em] justify-between items-center h-[10vh] md:px-[9em]">
        <img src="img/Flouflix.png" alt="Logo Flouflix">
        <div class="hover:cursor-pointer">
            <div class="flex flex-col gap-[10px]" id="burger-btn">
                <hr class="bg-white h-[4px] w-[40px] rounded">
                <hr class="bg-white h-[4px] w-[40px] rounded">
                <hr class="bg-white h-[4px] w-[40px] rounded">
            </div>
            <ul id="menu" class="absolute h-screen bg-[#121212] w-screen right-0 top-[10vh] flex flex-col items-center py-[10vh] z-[-1] opacity-0 scale-y-0 bg-opacity-90 gap-[20px] text-xl text-bold duration-300 origin-top">
                <li><a href="index.php" class="hover:text-[#e40b18]">Accueil</a></li>
                <li><a href="my-albums.php" class="hover:text-[#e40b18]">Mes albums</a></li>
                <li><a href="my-messages.php" class="hover:text-[#e40b18]">Mes messages</a></li>
                <li>
                    <form  class="" method="POST">
                        <input type="hidden" name="disconnect">
                        <button type="submit" class="hover:text-[#e40b18]">Se déconnecter</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <div>
        <h1>
            Mes albums
        </h1>

        <a href="create-album.php">Créer un album</a>
    </div>
    <?php
        $connection = new Connection();
        $result = $connection->getAllAlbums($_SESSION['user_id']);

        if ($result) {
            foreach ($result as $album) { ?>
                <a href="view-album.php?album-id=<?= $album['id']?>&album-name=<?= $album['name']?>" ><?= $album['name'] ?></a>
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
