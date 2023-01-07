<?php
    session_start();
    require_once "class/album.php";
    require_once "class/connection.php";
    require_once "class/user.php";

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
        <link rel="stylesheet" href="public/css/style.css">
        <script src="./public/js/main.js" defer></script>
        <title>Document</title>
    </head>
<body>

    <?= $_SESSION['user_id'] . $_SESSION['user_name']?>
    <aside>
        <nav>
            <form method="POST">
                <label for="user-search">Qui recherchez-vous ?</label>
                <input type="text" name="username" id="user-search">
                <button type="submit">Rechercher</button>
            </form>
        </nav>

        <?php if (isset($_POST['username'])) {
            $connection = new Connection();
            $search = $connection->getUserByName($_POST['username']);
            if ($search) {
                foreach ($search as $user) { ?>
                    <a href="view-profil.php?user-id=<?= $user['id'] ?>"> <?= $user['pseudo'] ?></a>
                <?php }
            }
        }?>
    </aside>

    <form id="filters" method="POST">
        <select name="genre" id="genre">
            <option value="null">Select genre</option>
        </select>
        <button type="submit" id="btn">Submit</button>
    </form>

    <div id="sort-tag" style="display: none">
        <button id="tag" value="original_title.desc">Trier par nom</button>
        <button id="tag" value="vote_average.desc">Trier par avis</button>
        <button id="tag" value="popularity.desc">Trier par popularité</button>
        <p>Les films sont triés par : <span id="sort-infos">Popularité décroissante</span></p>
    </div>

    <section id="list">

    </section>
    <span id="page-minus">Prev Page</span>
    <span id="page-plus">Next Page</span>

    <?php
        if (isset($_GET['film-id'])) {
            $connection = new Connection();
            $list = $connection->getAllAlbums($_SESSION['user_id']);

            foreach ($list as $myAlbum) { ?>
                <form id="show-album" method="POST">
                    <input type="hidden" name="albumId" value="<?= $myAlbum['id'] ?>">
                    <button type="submit"><?= $myAlbum['name'] ?></button>
                </form>
            <?php }

        }

        if (isset($_POST['albumId'])) {
            if (isset($_GET['film-id'])) {
                $film = $_GET['film-id'];
            }
            $album = $_POST['albumId'];
            $connection = new Connection();
            $result = $connection->insertFilm($film, $album);

            if ($result) {
                echo "Film ajouté à l'album";
            } else {
                "Echec";
            }
        }

        if (isset($_POST['liked-mark'])) {
            $connection = new Connection();
            $id = $_POST['film-id'];
            $insert = $connection->insertFilm($id, $_SESSION['liked']);
        }

        if (isset($_POST['watched-mark'])) {
            $connection = new Connection();
            $id = $_POST['film-id'];
            $insert = $connection->insertFilm($id, $_SESSION['watched']);
        }
    ?>
</body>
</html>