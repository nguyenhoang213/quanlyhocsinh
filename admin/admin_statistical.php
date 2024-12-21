<?php
include("../connection.php");
session_start();
if (!$_SESSION['uname'])
    header('Location: /login.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Admin Vật Lý Trường Nghiêm</title>
    <link rel="stylesheet" href="/assets/css/admin-style.css">
    <link rel="stylesheet" href="/assets/font/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    include("../side_nav.php");
    ?>
    <div class="content">
    </div>
</body>

</html>