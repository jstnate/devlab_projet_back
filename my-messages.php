<?php
    session_start();
    require_once 'class/connection.php';

    $connection = new Connection;

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

    if (isset($_POST['message-id'])) {
        $accept = $connection->addAutorisation($_POST['album-id'], $_SESSION['user_id']);
        $delete = $connection->deleteMessage($_POST['message-id']);
        header('Location: my-albums.php');
    }

    if (isset($_POST['delete-message'])) {
        $delete = $connection->deleteMessage($_POST['delete-message']);
        header('Refresh:0');
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
                    boxShadow: {
                        'lgWhite': '0 0 20px rgb(255 255 255 / 0.1)'
                    }
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js" integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./public/js/burger.js" defer></script>
    <title>Flouflix - Mes messages</title>
</head>
<body class="bg-[#121212] text-white py-[15vh] relative z-0">

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

    <div class="w-[80vw] text-center mx-auto my-[6vh]">
        <h1 class="text-3xl font-bold ">
            Mes messages
        </h1>
    </div>
    <?php
        $messages = $connection->getMessage($_SESSION['user_id']);

        foreach ($messages as $message) { ?>
            <div class="p-6 flex flex-col items-center rounded shadow-lgWhite w-[90vw] mx-auto my-10 md:w-[60vw]">
                <h2 class="text-2xl font-bold"><?= $message['shipper_name']?></h2>
                <h3 class="text-center my-6">Cette personne souhaite partager avec vous  : <?= $message['album_name'] ?></h3>
                <div class="flex items-center gap-5 w-full justify-center">
                    <form method="POST">
                        <input type="hidden" name="message-id" value="<?= $message['id'] ?>">
                        <input type="hidden" name="album-id" value="<?= $message['album_id'] ?>">
                        <button type="submit" class="px-4 py-2 bg-white text-black rounded">Accepter</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="delete-message" value="<?= $message['id'] ?>">
                        <button type="submit" class="px-4 py-2 bg-[#E40B18] text-white rounded">Refuser</button>
                    </form>
                </div>
            </div>
        <?php }
    ?>
</body>
</html>
