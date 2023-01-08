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
        <script src="./public/js/main.js" defer></script>
        <script src="./public/js/search.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js" integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <title>Document</title>
    </head>
<body>

    <form method="POST">
        <label for="film-search">Quel filme recherchez-vous ?</label>
        <input type="text" id="film-search">
    </form>

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

    <section id="list">

    </section>
    <span id="page-plus">Next Page</span>
    <span id="page-minus">Prev Page</span>

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