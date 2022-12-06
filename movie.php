<?php
    session_start()
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $_GET['name'] ?></title>
</head>
<body>
    <div id="infos">

    </div>

    <script defer>
        let movie = document.getElementById('infos')
        fetch('https://api.themoviedb.org/3/movie/' + <?= $_GET['id'] ?> + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
            .then(result => result.json())
            .then(data => {
                let film = document.createElement('div')
                film.id = data.id
                // let img = document.createElement('img')
                // img.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
                let h1 = document.createElement('h1')
                h1.innerHTML = data.title
                // let p = document.createElement('p')
                // p.innerHTML = data.overview

                // film.appendChild(img)
                film.appendChild(h1)
                // film.appendChild(p)
                movie.appendChild(film)
            })
    </script>
</body>
</html>
