<?php
    session_start();
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
    <meta name="viewport"  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/b050931f68.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    gridTemplateColumns: {
                        'cards': 'repeat(auto-fit, minmax(250px, 1fr))'
                    }
                }
            }
        }
    </script>
    <script src="public/js/burger.js" defer></script>
    <title>Flouflix - <?= $_GET['name'] ?></title>
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
    <section class="my-10 flex flex-col gap-10 items-start md:w-[90vw] mx-auto">
        <div id="header-infos" class="flex flex-col items-center text-center gap-10 w-[90%] mx-auto md:flex-row md:items-end md:text-left">

        </div>
        <div class="w-[90%] mx-auto">
            <h2>Synopsis</h2>
            <hr class="bg-[#E40B18] h-1 border-0 rounded my-2">
            <div id="overview">

            </div>
        </div>

        <div class="w-[90%] mx-auto">
            <h2>Casting</h2>
            <hr class="bg-[#E40B18] h-1 border-0 rounded mt-2 mb-4">
            <div id="casting" class="grid grid-cols-cards flow-dense gap-10 place-items-center">

            </div>
        </div>
    </section>
    

    <script>
        let header = document.getElementById('header-infos')
        let synopsis = document.getElementById('overview')
        let casting = document.getElementById('casting')

        fetch('https://api.themoviedb.org/3/movie/' + <?= $_GET['id'] ?> + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
            .then(result => result.json())
            .then(data => {
                let poster = document.createElement('img');
                if (data.poster_path === null) {
                    poster.src = 'img/room.jpeg'
                } else {
                    poster.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
                }
                poster.classList.add('w-[70%]', 'md:w-[300px]')

                header.appendChild(poster)

                let infos = document.createElement('div')

                let title = document.createElement('h1');
                title.innerHTML = data.title
                title.classList.add('text-2xl', 'font-bold', 'md:text-4xl', 'my-4')

                infos.appendChild(title)
                
                let vote = document.createElement('span');
                vote.innerHTML = 'Note : ' + data.vote_average
                vote.classList.add('text-gold')

                infos.appendChild(vote)

                header.appendChild(infos);

                let overview = document.createElement('p')
                overview.innerHTML = data.overview

                synopsis.appendChild(overview)
            })

        fetch('https://api.themoviedb.org/3/movie/' + <?= $_GET['id'] ?>  + '/credits?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en-US')
            .then(result => result.json())
            .then(data => {
                let list = data.cast
                for (let i = 0; i < 8; i++) {
                    if (list[i].profile_path !== null) {
                        let actor = document.createElement('div')
                        actor.classList.add('w-[250px]', 'text-center', 'flex', 'flex-col', 'items-center', 'gap-5')
                        let pic = document.createElement('img')
                        pic.src = "https://image.tmdb.org/t/p/original"+ list[i].profile_path
                        pic.classList.add('w-[200px]')

                        actor.appendChild(pic)

                        let name = document.createElement('h3')
                        name.innerHTML = list[i].name

                        actor.appendChild(name)

                        casting.appendChild(actor)
                    }
                }
            })
    </script>
</body>
</html>
