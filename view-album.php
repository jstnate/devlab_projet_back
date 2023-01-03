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
        $result = $connection->getFilms($_GET['album-id']);

        if ($result) {
            foreach ($result as $item) { ?>
                <div id="film">
                </div>

                <script>
                    fetch('https://api.themoviedb.org/3/movie/<?= $item['movie_id'] ?>?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
                        .then(result => result.json())
                        .then(data => {
                            let movie = document.getElementById('film')
                            // let img = document.createElement('img')
                            // img.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
                            let h1 = document.createElement('h1')
                            h1.innerHTML = data.title
                            // let p = document.createElement('p')
                            // p.innerHTML = data.overview

                            movie.appendChild(h1)
                        })
                </script>
            <?php }
        }
    ?>
</body>
</html>
