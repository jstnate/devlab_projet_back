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

    if (isset($_POST['delete'])) {
        $connection = new Connection();
        $albumId = $_GET['album-id'];
        $filmId = $_POST['film-id'];
        $request = $connection->removeFilm($filmId, $albumId);
        header("Refresh:0");
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
    <title>Flouflix - <?= $_GET['album-name'] ?></title>
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

    <div class="w-[90vw] mx-auto my-20 flex justify-center">
        <h1 class="text-4xl font-bold"><?= $_GET['album-name'] ?></h1>
    </div>
    <?php
        $connection = new Connection();
        $result = $connection->getFilms($_GET['album-id']);

        if ($result) { ?>

                <section id="films" class="grid grid-cols-cards flow-dense place-items-center gap-10 pb-20 w-[90vw] mx-auto">
                </section>

                <script>
                    let movies = document.getElementById('films')
                <?php foreach ($result as $item) { ?>
                    fetch('https://api.themoviedb.org/3/movie/<?= $item['movie_id'] ?>?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
                        .then(result => result.json())
                        .then(data => {
                            let movie = document.createElement('div')
                            movie.classList.add('w-[250px]', 'flex', 'flex-col', 'items-center', 'gap-8')
                            let img = document.createElement('img')
                            if (data.poster_path === null) {
                                img.src = 'img/room.jpeg'
                            } else {
                                img.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
                            }
                            img.classList.add('w-full', 'object-fill', 'aspect-11/16')

                            let a = document.createElement('a')
                            a.href = 'movie.php?id=' + data.id + '&name=' + data.title

                            // let p = document.createElement('p')
                            // p.innerHTML = data.overview

                            let deleteForm = document.createElement('form')
                            let filmId = document.createElement('input')
                            let button = document.createElement('button')

                            deleteForm.method = 'POST'

                            filmId.value = data.id
                            filmId.name = 'film-id'
                            filmId.type = 'hidden'

                            button.type = 'submit'
                            button.name = 'delete'
                            button.innerHTML = "Supprimer de l'album"
                            button.classList.add('bg-[#E40B18]', 'p-[10px]', 'rounded')

                            deleteForm.appendChild(filmId)
                            deleteForm.appendChild(button)

                            a.appendChild(img)

                            movie.appendChild(a)
                            movie.appendChild(deleteForm)
                            movies.appendChild(movie)
                        })
                    <?php } ?>
                </script>

            <?php }
        ?>
</body>
</html>
