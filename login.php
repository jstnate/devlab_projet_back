<?php
    session_start();
    require_once "class/user.php";
    require_once 'class/connection.php';
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
            'black-header': '#060606',
            'grey-header': '#222222',
            'red-btn': '#e40b18'
          }
        }
      }
    }
    </script>
    <title>Flouflix - Connexion</title>
</head>

<header class="h-[60px] w-full flex items-center p-[16px] bg-gradient-to-t from-grey-header to-black-header">
    <img class="h-[131px] h-[24px] mr-[16px]" src="./img/Flouflix.png" alt="Logo 'Flouflix'">
</header>

<body class="w-auto bg-[url('./img/bg_film.jpeg')] bg-cover">

    <div class="w-[350px] bg-black/80 bg-opacity-55 m-auto mt-[140px] p-[16px] md:w-[400px] h-[500px] z-99">
        <h1 class="text-white text-3xl font-bold mt-[50px] text-center mx-auto">Connexion</h1>
        <form class="mt-[50px] flex flex-col items-center gap-[16px]" method="POST">
            <input class="w-[272px] h-[48px] rounded-[6px] mt-[16px] pl-[8px]" type="email" name="email" placeholder="Enter your mail">
            <input class="w-[272px] h-[48px] rounded-[6px] pl-[8px]" type="password" name="password" placeholder="Enter your password">
            <button class="w-[172px] h-[48px] rounded-[6px] bg-red-btn text-white mt-[16px]" type="submit" name="register">Connexion</button>
            <p class="text-white text-sm mt-[8px] m-auto">Premiere visite sur FlouFlix ? <a href="signin.php">Inscrivez-vous</a></p>
        </form>
    </div>
    
    

    <?php
        if($_POST){

            $connection = new Connection();
            $email = $_POST['email'];
            $user = $connection->emailVerify($email);

            if ($user) {
                if (md5($_POST['password'] . 'SALT') === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['pseudo'];
                    $getLiked = $connection->getAlbum('Films Aimés');
                    $getWatched = $connection->getAlbum('Films visionés');
                    $_SESSION['liked'] = $getLiked['id'];
                    $_SESSION['watched'] = $getWatched['id'];
                    header('Location: index.php');
                }
            } else {
                echo '<h2>Erreur interne, veuillez reéssayer ultérieurement</h2>';
            }

        }
    ?>
</body>
</html>
