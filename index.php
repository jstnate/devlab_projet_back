<?php
session_start();
require_once "class/album.php";
require_once "class/connection.php";
require_once "class/user.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="./public/js/main.js" defer></script>
    <title>Document</title>
</head>
<body>
<form id="filters" method="POST">
    <select name="genre" id="genre">
        <option value="null">Select genre</option>
    </select>
    <button type="submit" id="btn">Submit</button>
</form>

<section id="list">

</section>
<span id="page-plus">Next Page</span>
<span id="page-minus">Prev Page</span>

<?php
if (isset($_POST['film-id'])) {

}
?>
</body>
</html>