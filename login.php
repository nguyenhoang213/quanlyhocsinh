<?php
include("connection.php");
session_start();

// Kiểm tra nếu đã đăng nhập, chuyển đến trang admin
if (isset($_SESSION['uname'])) {
    header('Location: /admin/admin.php');
}

// Kiểm tra nếu có cookie đăng nhập
if (isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];
    // Truy vấn cơ sở dữ liệu để kiểm tra token hợp lệ
    $sql = "SELECT * FROM login_tokens WHERE token = '$token'";
    $query = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($query);

    if ($num_rows > 0) {
        // Nếu token hợp lệ, đặt session và chuyển hướng
        $row = mysqli_fetch_assoc($query);
        $_SESSION['uname'] = $row['username'];
        header('Location: /admin/admin.php');
        exit();
    }
}

// Kiểm tra khi người dùng nhấn nút đăng nhập
if (isset($_POST['login-button']) || isset($_POST['uname']) || isset($_POST['psw'])) {
    $username = $_POST["uname"];
    $password = $_POST["psw"];

    // Bảo mật chuỗi nhập vào
    $username = strip_tags($username);
    $username = addslashes($username);
    $password = strip_tags($password);
    $password = addslashes($password);

    // Truy vấn kiểm tra username và password
    $sql = "SELECT * FROM admin WHERE Username = '$username' AND Password = '$password'";
    $query = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($query);

    if ($num_rows == 0) {
        // Thông báo nếu sai tên đăng nhập hoặc mật khẩu
        echo '<script>alert("Tên đăng nhập hoặc mật khẩu không đúng ! Vui lòng kiểm tra lại.")</script>';
    } else {
        // Đăng nhập thành công, thiết lập session
        $_SESSION['uname'] = $username;
        $ip = POST_client_ip();
        $date = date('Y-m-d H:i:s');

        // Lưu thông tin đăng nhập vào lịch sử
        $login = "INSERT INTO login_htr (username, ip_address, login_time) VALUES ('$username', '$ip', '$date')";
        $conn->query($login);

        // Kiểm tra nếu người dùng chọn "Lưu Mật Khẩu"
        if (isset($_POST['remember'])) {
            // Tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(16));

            // Lưu token vào cơ sở dữ liệu
            $saveToken = "INSERT INTO login_tokens (username, token) VALUES ('$username', '$token')";
            $conn->query($saveToken);

            // Lưu token vào cookie, hết hạn sau 30 ngày
            setcookie("login_token", $token, time() + (30 * 24 * 60 * 60), "/"); // 30 ngày
        }

        // Chuyển hướng đến trang admin
        header('Location: /admin/admin.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.png">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/login_style.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/font/themify-icons/themify-icons.css">
    <script src="https://kit.fontawesome.com/8fcd74b091.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="header">
        <div class="contact_phone">
            <p><i class="fa-solid fa-phone icon"></i> 098 336 1907</p>
            <p style="font-size: 20px;">|</p>
            <p><i class="ti-email icon"></i> nghiemcn@gmail.com</p>
        </div>
        <div class="network_contact">
            <a href="https://www.facebook.com/truongnghiem.dhcn"><i class="ti-facebook icon"></i></a>
            <a><i class="ti-google icon"></i></a>
            <a><i class="ti-sharethis icon"></i></a>
        </div>
    </div>
    <div id="slider">
        <div class="logo">
            <img src="assets/image/logo.png" alt="logo">
            <h1>PHẠM TRƯỜNG NGHIÊM</h1>
            <button class="menu_button" onclick="Show()"><i class="ti-view-list icon"></i></button>
        </div>
        <div class="menu" id="menu">
            <a href="/index.php">HOME</a>
            <a href="/index.php#content">ABOUT</a>
            <a href="/login.php">ADMIN</a>
            <a href="">EXPERIMENT</a>
            <a href="/index.php#footer">CONTACT</a>
        </div>
    </div>
    <div id="content">
        <div class="login">
            <h1>LOGIN</h1>
            <form action="" method="POST">
                <input name="uname" type="text" placeholder="USERNAME" class="text-input" required> <br>
                <input name="psw" type="password" placeholder="PASSWORD" class="text-input" required> <br>
                <input name="remember" type="checkbox" class="checkbox"> Lưu Mật Khẩu <br>
                <div class="button-list">
                    <button style="background-color: aqua;color: rgb(0, 0, 0);">FORGOT PASSWORD</button>
                    <button name="login-button"
                        style="background-color: rgb(255, 90, 90);color: rgb(0, 0, 0);">LOGIN</button>
                </div>
            </form>
        </div>
        <div id="footer">
            <div class="address">
                <div class="POST-in-touch">
                    <h1>Phạm Trường Nghiêm</h1>
                    <p><a href="https://www.facebook.com/truongnghiem.dhcn"><i class="ti-facebook icon"></i></a> Tất cả
                        vì sự tiến bộ của học trò!</p>
                    <h1>POST In Touch</h1>
                    <p><i class="ti-map-alt icon"></i> Số 32 - Ngõ 3 - Thôn Đông - Tàm Xá - Đông Anh - Hà Nội</p>
                    <p><i class="ti-map-alt icon"></i> Số 03 - Ngách 69 - Ngõ 260 - Cầu Giấy </p>
                    <p><i class="ti-headphone-alt icon"></i> 098 336 1907</p>
                    <p><i class="ti-email icon"></i> nghiemcn@gmail.com</p>
                </div>
                <div class="new-letter">
                    <h1>Contact</h1>
                    <input type="text"><button>Send</button>
                </div>
            </div>
            <div class="description">
                <p>Copyright ©<a href="https://www.facebook.com/as.royal03/">Nguyễn Như Hoàng</a>. All Rights Reserved.
                </p>
                <p>Desgined by <a href="https://www.facebook.com/as.royal03/">Nguyễn Như Hoàng</a>.</p>
            </div>
        </div>
</body>

</html>

<?php
function POST_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>