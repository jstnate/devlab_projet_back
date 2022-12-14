<?php
    session_start();
    require_once 'class/user.php';
    require_once 'class/album.php';
    require_once 'class/connection.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
    <title>Flouflix - Inscription</title>
</head>



<body class="w-auto bg-[url('./img/bg_film.jpeg')] bg-cover">

    <header class="h-[60px] w-full flex items-center p-[16px] bg-gradient-to-t from-grey-header to-black-header">
        <img class="h-[131px] h-[24px] mr-[16px]" src="./img/Flouflix.png" alt="Logo 'Flouflix'">
    </header>

    <div class="form w-[350px] h-[600px] bg-black/80 bg-opacity-55 m-auto mt-[100px] p-[16px] md:w-[450px]">
        <h1 class="text-white text-3xl font-bold mt-[24px] text-center">Créez un compte !</h1>

        <form class="mt-[24px] flex flex-col items-center gap-[16px]" method="POST">
            <div class="civilite">
                <div class="fname">
                    <input class="w-[272px] h-[48px] rounded-[6px] mt-[16px] pl-[8px]" type="text" name="firstname" placeholder="Entrez votre prénom">
                </div>

                <div class="lname">
                    <input class="w-[272px] h-[48px] rounded-[6px] mt-[16px] pl-[8px]" type="text" name="lastname" placeholder="Entre votre nom">
                </div>
            </div>

            <input class="w-[272px] h-[48px] rounded-[6px] pl-[8px]" type="text" name="pseudo" placeholder="Entrez votre pseudonyme">

            <input class="w-[272px] h-[48px] rounded-[6px] pl-[8px]" type="email" name="email" placeholder="Entrez votre votre email">

            <input class="w-[272px] h-[48px] rounded-[6px] pl-[8px]" type="password" name="password" placeholder="Entrer votre mot de passe">

            <button class="w-[172px] h-[48px] rounded-[6px] bg-red-btn text-white mt-[16px]" type="submit"><a href="index.php">S'inscrire</a></button>
        </form>

        <p class="text-white mt-8 w-full text-center">Déja un compte chez Flouflix ? <a href="login.php">Connectez-vous</a></p>

    </div>

    <?php
        if ($_POST) {
            $user = new User(
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['pseudo'],
                $_POST['email'],
                $_POST['password']
            );

            if ($user->inputVerify()) {
                $connection = new Connection();
                $insert = $connection->insertUser($user);

                $liked_album = new Album(
                    'Ma liste',
                    0
                );

                $visioned_album = new Album(
                    'Films visionés',
                    0
                );

                if ($insert) {
                    $request = $connection->getUserByEmail($_POST['email']);
                    $_SESSION['user_id'] = $request['id'];
                    $_SESSION['user_name'] = $request['pseudo'];
                    $liked = $connection->createAlbum($liked_album);
                    $visioned = $connection->createAlbum($visioned_album);
                    $getLiked = $connection->getAlbum('Ma liste');
                    $getWatched = $connection->getAlbum('Films visionés');
                    $_SESSION['liked'] = $getLiked['id'];
                    $_SESSION['watched'] = $getWatched['id'];
                    $liked_true = $connection->addAutorisation($_SESSION['liked'], $_SESSION['user_id']);
                    $watched_true = $connection->addAutorisation($_SESSION['watched'], $_SESSION['user_id']);
                    header('Location: index.php');
                } else {
                    echo '<h2>Erreur interne, veuillez reéssayer ultérieurement</h2>';
                }
            }
        }
    ?>
</body>
</html>
