<?php
    session_start();
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
    <form method="post">
        <input type="text" type="email" name="email" placeholder="enter ur mail">
        <input type="text" type="password" name="password" placeholder="enter ur password">
        <input type="submit">
    </form>
</body>

<?php
    require_once 'vendor/autoload.php';
    require_once 'user.php';
    require_once 'connection.php';

    if($_POST){
        $connection = new Connection;
        $email = $_POST['email'];
        $user = $connection->login($email);

        if (md5($_POST['password'] . 'SALT') === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
        } else {
            echo '<h2>Erreur interne, veuillez reéssayer ultérieurement</h2>';
        }

    }
?>


</html>
