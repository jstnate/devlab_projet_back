<?php
    session_start();
    require_once "class/user.php";
    require_once "class/connection.php";
    require_once "class/album.php";

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
    <script src="./public/js/burger.js" defer></script>
    <title>Créer un album</title>
</head>
<body class="bg-[#121212] text-white py-[10vh] relative z-0 overflow-hidden">
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
    <div class="m-auto md:h-[60vh] my-20 md:my-[15vh] md:w-[40vw] flex flex-col items-center py-10">
        <div class="flex flex-col items-center mb-20">
            <h1 class="text-2xl font-bold">Créer un nouvel album</h1>
            <p class="text-[#E40B18]">Veuillez renseigner les chanps requis</p>
        </div>
        <form class="flex flex-col items-center w-full" method="POST">
            <input type="text" name="albumName" placeholder="Nom de l'album" class="p-1 rounded w-[60%] mb-8 text-black" required>
            <select name="privacy" class="text-black p-1 rounded w-[60%] mb-14" required>
                <option value="1">Privé</option>
                <option value="0">Publique</option>
            </select>
            <button type="submit" class="font-bold text-xl bg-white text-black hover:text-white hover:bg-[#E40B18] px-10 py-2 rounded">Créer l'album</button>
        </form>

        <?php
        if(isset($_POST['albumName'])){
            $album = new Album(
                $_POST['albumName'],
                $_POST['privacy']
            );
            $connection = new Connection();
            $result = $connection->createAlbum($album);
            if($result){
                $getId = $connection->getAlbum($_POST['albumName']);
                $add = $connection->addAutorisation($getId['id'], $_SESSION['user_id']);
                echo '<h2 class="text-xl font-bold mt-10">Album créé avec succès</h2>';
            }else {
                echo '<h2>Erreur interne, veuillez réessayer</h2>';
            }
        }
        ?>
    </div>
</body>
</html>
