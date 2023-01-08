<?php
    session_start();
    require_once "class/album.php";
    require_once "class/connection.php";
    require_once "class/user.php";

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
    }

    if (isset($_POST['albumId'])) {
        if (isset($_GET['film-id'])) {
            $film = $_GET['film-id'];
        }
        $album = $_POST['albumId'];
        $connection = new Connection();
        $result = $connection->insertFilm($film, $album);
        
        if ($result) {
            header('Location: index.php');
        }
    }

    if (isset($_POST['liked-mark'])) {
        print_r('caca');
        $connection = new Connection();
        $id = $_POST['film-id'];
        $insert = $connection->insertFilm($id, $_SESSION['liked']);
    }

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

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <script src="./public/js/main.js" defer></script>
        <script src="./public/js/search.js" defer></script>
        <title>Flouflix - Accueil</title>
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

    <div class="w-[80vw] flex flex-col justify-center gap-[20px] my-[25px] mx-auto">
        <form class="flex w-[80vw] m-auto justify-between items-center md:w-[40vw]" method="POST">
            <input type="text" name="username" id="user-search" placeholder="Rechercher un utilisateur..." class="p-[5px] rounded text-black w-[100%]">
            <button type="submit"></button>
        </form>

        <?php if (isset($_POST['username'])) {
            $connection = new Connection();
            $search = $connection->getUserByName($_POST['username']);
            ?>
            <div class="grid grid-cols-user flow-dense w-[80vw] md:w-[40vw] mx-auto place-items-center gap-10">
                <?php if ($search) {
                    foreach ($search as $user) { ?>
                        <a href="view-profil.php?user-id=<?= $user['id'] ?>" class="w-[100%] text-center text-xl text-bold hover:text-[#E40B18]"> <?= $user['pseudo'] ?></a>
                    <?php }
                } ?>
            </div>
        <?php }?>
    </div>

    <form class="flex w-[80vw] m-auto justify-between items-center md:w-[40vw]" method="POST">
        <input type="text" id="film-search" placeholder="Rechercher un film..." class="p-[5px] rounded text-black w-[100%]">
        <button type="submit"></button>
    </form>

    <form id="filters"  class="flex items-center justify-center gap-[10px] my-[25px]" method="POST">
        <select name="genre" id="genre" class="text-black w-[150px] h-[30px]">
            <option value="null">Genres</option>
        </select>
        <button type="submit" id="btn" class="text-black bg-[#e40b18] h-[30px] w-[50px] text-bold text-xl hover:text-white">Go</button>
    </form>

    <div class="w-full flex-col items-center justify-center gap-[10px]" id="sort-tag" style="display: none">
        <div class="w-full flex items-center justify-center gap-[5px]">
            <button class="px-[0.5em] py-[0.5em] bg-[#e40b18] text-white hover:cursor-pointer" id="tag" value="original_title.desc">Trier par nom</button>
            <button class="px-[0.5em] py-[0.5em] bg-[#e40b18] text-white hover:cursor-pointer" id="tag" value="vote_average.desc">Trier par avis</button>
            <button class="px-[0.5em] py-[0.5em] bg-[#e40b18] text-white hover:cursor-pointer" id="tag" value="popularity.desc">Trier par popularité</button>
        </div>
        
        <p>Les films sont triés par : <span id="sort-infos">Popularité décroissante</span></p>
    </div>

    <section id="list" class="grid grid-cols-cards grid-flow-dense gap-x-[25px] gap-y-[50px] my-[50px] place-items-center mx-auto md:w-[90vw]">

    </section>

    <div class="flex items-center justify-center mx-auto my-[50px] gap-[10px]">
        <span id="page-minus"><i class="fa-solid fa-chevron-left px-[2em] py-[1em] bg-[#e40b18] hover:cursor-pointer"></i></span>
        <span id="page-plus"><i class="fa-solid fa-chevron-right px-[2em] py-[1em] bg-[#e40b18] hover:cursor-pointer"></i></span>
    </div>

    <?php
        if (isset($_GET['film-id'])) {
            $connection = new Connection();
            $list = $connection->getAllAlbums($_SESSION['user_id']);
            ?>

            <div class="fixed top-[10vh] left-0 bg-[#121212] h-[90vh] w-[100vw] flex flex-col items-center py-[50px] gap-[20px] bg-opacity-[0.9]">
                <?php foreach ($list as $myAlbum) { ?>
                    <form class="w-full flex justify-center items-center" id="show-album" method="POST">
                        <input type="hidden" name="albumId" value="<?= $myAlbum['id'] ?>">
                        <button type="submit" class="w-[50%] h-[50px] bg-[#e40b18]"><?= $myAlbum['name'] ?></button>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>
</body>
</html>