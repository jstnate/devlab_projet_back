<?php
    session_start();
    require_once "class/album.php";
    require_once "class/connection.php";
    require_once "class/user.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="./public/js/main.js" defer></script>
        <title>Document</title>
    </head>
<body>
<?php
    echo 'id de album like : ' . $_SESSION['liked'];
    echo 'id de album watch : ' .$_SESSION['watched'];
?>
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
            $list = $connection->getAllAlbums();

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
        $insert = $connection->insertInLiked($id);
    }

    if (isset($_POST['watched-mark'])) {
        $connection = new Connection();
        $id = $_POST['film-id'];
        $insert = $connection->insertInWatched($id);
    }

    ?>
</body>
</html>