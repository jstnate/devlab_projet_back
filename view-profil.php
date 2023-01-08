<?php
    session_start();
    require_once 'class/connection.php';

    if (isset($_POST['watched-mark'])) {
        $connection = new Connection();
        $id = $_POST['film-id'];
        $insert = $connection->insertFilm($id, $_SESSION['watched']);
    }


    if (isset($_POST['disconnect'])) {
        $connection = new Connection();
        $disconnect = $connection->disconnect();

        if ($disconnect) {
            header('Location: login.php');
        }
    }

    if (isset($_POST['album-id'])) {
        $liking = $connection->albumLike($_POST['album-id'], $_SESSION['user_id']);

        if ($liking) {
            header('Refresh:0');
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
    <script src="https://kit.fontawesome.com/b050931f68.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    gridTemplateColumns: {
                        'cards': 'repeat(auto-fit, minmax(250px, 1fr))',
                        'user': 'repeat(auto-fit, minmax(150px, 1fr))'
                    },
                    aspectRatio: {
                        '11/16': '11/16'
                    }
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js" integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./public/js/burger.js" defer></script>
    <title>Flouflix - Profil utilisateur</title>
</head>
<body class="bg-[#121212] text-white py-[10vh] relative z-0">

    <header class="fixed top-0 left-0 w-screen bg-[#121212] flex p-[1em] justify-between items-center h-[10vh] md:px-[9em]"">
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
    <main class="w-[90vw] mx-auto my-[5vh] md:w-[80vw]">
        <?php
            $connection = new Connection();
            $profil = $connection->getUserById($_GET['user-id']);
            $result = $connection->getPublicAlbums($_GET['user-id']);

            if ($_GET['user-id'] != $_SESSION['user_id']) {
                if ($profil) { ?>
                    <h1 class="w-full text-center text-3xl font-bold mb-10"><?= $profil['first_name'] . ' "' . $profil['pseudo'] . '" ' . $profil['last_name'] ?></h1>
                <?php }
                if ($result) { ?>
                    <div class="flex flex-col items-center my-10">
                        <h2 class="text-2xl w-full">Ses albums</h2>
                        <hr class="bg-[#E40B18] h-1 border-0 rounded mt-2 mb-4 w-full">
                        <?php foreach ($result as $album) { ?>
                            <a href="view-album.php?album-id=<?= $album['id']?>&album-name=<?= $album['name'] ?>" class="w-[60%] my-2 text-xl text-center py-2 bg-[#E40B18]"><?= $album['name'] ?></a>
                            <?php if ($album['name'] !== 'Films visionés' && $album['name'] !== 'Ma liste') { ?>
                                <form method="POST">
                                    <input type="hidden" name="album-id" value="<?= $album['id']?>">
                                    <button type="submit" class="decoration-solid">Aimer l'album</button>
                                </form>
                            <?php }?>
                        <?php } ?>
                    </div>
                <?php }

                $likes = $connection->getLikedAlbums($_GET['user-id']);
                if ($likes) { ?>
                    <div class="flex flex-col items-center my-10">
                        <h2 class="text-2xl w-full">Ses albums likés</h2>
                        <hr class="bg-[#E40B18] h-1 border-0 rounded mt-2 mb-4 w-full">
                        <?php foreach ($likes as $like) { ?>
                            <a href="view-album.php?album-id=<?= $like['id']?>&album-name=<?= $like['name'] ?>" class="w-[60%] my-2 text-xl text-center py-2 bg-[#E40B18]"><?= $like['name'] ?></a>
                            <?php if ($like['name'] !== 'Films visionés' && $like['name'] !== 'Ma liste') { ?>
                                <form method="POST">
                                    <input type="hidden" name="album-id" value="<?= $like['id']?>">
                                    <button type="submit" class="underline mb-2">Aimer l'album</button>
                                </form>
                            <?php }?>
                        <?php } ?>
                    </div>
                <?php }
            } else {
                echo "C'est ton compte";
            }
        ?>
    </main>

</body>
</html>
