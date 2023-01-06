<?php
    session_start();
    require_once 'class/connection.php';
    $connection = new Connection;
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
        $messages = $connection->getMessage($_SESSION['user_id']);

        foreach ($messages as $message) { ?>
            <div>
                <h2><?= $message['shipper_name']?></h2>
                <form method="POST">
                    <input type="hidden" name="message-id" value="<?= $message['id'] ?>">
                    <input type="hidden" name="album-id" value="<?= $message['album_id'] ?>">
                    <button type="submit">Accepter</button>
                </form>
            </div>
        <?php }

        if (isset($_POST['message-id'])) {
            $accept = $connection->addAutorisation($_POST['album-id'], $_SESSION['user_id']);
            $delete = $connection->deleteMessage($_POST['message-id']);
            header('Location: my-albums.php');
        }
    ?>
</body>
</html>
