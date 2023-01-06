<?php
    session_start();
    require_once "class/user.php";
    require_once "class/connection.php";
    require_once "class/album.php";
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
    <form method="POST">
        <input type="text" name="albumName" placeholder="Album name">
        <select name="privacy">
            <option value="1">Private</option>
            <option value="0">Public</option>
        </select>
        <button type="submit">Un nouveau album</button>
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
                    echo 'Album created successfully';
                }else {
                    echo 'Something went wrong';
                }
        }
    ?>
</body>
</html>
