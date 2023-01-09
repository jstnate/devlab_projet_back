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

    $connection = new Connection();

    if (isset($_POST['share-id'])) {
        $send = $connection->sendMessage($_SESSION['user_name'], $_GET['album-id'], $_GET['album-name'], $_POST['share-id']);

        if ($send) {
            header('Location: my-albums.php?status=sucess');
        } else {
            header('Location: my-albums.php?status=error');;
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'black-header': '#080808',
            'grey-header': '#121212',
            'red-btn': '#e40b18'
          },
            gridTemplateColumns: {
                'cards': 'repeat(auto-fit, minmax(250px, 1fr))'
            },
            placeItems: {
              'top': 'center start'
            }
        }
      }
    }
    </script>
    <script src="public/js/burger.js" defer></script>
    <title>Flouflix - Mes albums</title>
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

    <div class="w-[80vw] text-center mx-auto my-[6vh]">
        <h1 class="text-3xl font-bold ">
            Mes albums
        </h1>

        <a href="create-album.php" class="text-[#E40B18]">Créer un album</a>
    </div>

    <?php
        if (isset($_GET['status']) && $_GET['status'] == 'sucess') { ?>
            <h3 class="text-center w-full mb-[2vh]">Album partagé...</h3>
        <?php } else if (isset($_GET['status']) && $_GET['status'] == 'error') { ?>
            <h3 class="text-center w-full mb-[2vh]">Erreur interne, veuillez réessayer...</h3>
        <?php }
    ?>
    
    <div class="grid grid-cols-cards flow-dense w-[80vw] mx-auto gap-10 items-start place-items-center">
        <?php
            $result = $connection->getAllAlbums($_SESSION['user_id']);

            if ($result) {
                foreach ($result as $album) { ?>
                    <div class="w-[250px] flex flex-col justify-center gap-2 items-center">
                        <a class="w-full h-[72px] rounded-[6px] bg-red-btn text-white text-3xl flex items-center justify-center" href="view-album.php?album-id=<?= $album['id'] ?>&album-name=<?= $album['name'] ?>"><?= $album['name'] ?></a>
                        <?php if ($album['name'] !== 'Films visionés' && $album['name'] !== 'Ma liste') { ?>
                            <form method="GET">
                                <input type="hidden" name="album-id" value="<?= $album['id'] ?>">
                                <input type="hidden" name="album-name" value="<?= $album['name'] ?>">
                                <button type="submit" class="w-full text-center underline">Partager l'album</button>
                            </form>
                        <?php } ?>
                    </div>
                <?php }
            }
        ?>
    </div>
        <?php if (isset($_GET['album-id'])) {
            $share = $connection->getUsers();

            echo '<div class="fixed top-[10vh] left-0 bg-[#121212] h-[90vh] w-[100vw] flex flex-col items-center py-[50px] gap-[20px] bg-opacity-[0.9]">';
                foreach ($share as $user) {
                    if ($user['pseudo'] !== $_SESSION['user_name']) { ?>
                        <form method="POST">
                            <input type="hidden" name="share-id" value="<?= $user['id'] ?>">
                            <button type="submit" class="w-[300px] bg-[#E40B18] py-2 font-bold rounded"><?= $user['pseudo'] ?></button>
                        </form>
                    <?php }
                }
            echo '</div>';
        } ?>
</body>
</html>
