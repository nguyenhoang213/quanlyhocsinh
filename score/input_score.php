<?php
include("../connection.php");
session_start();
include("../class.php");
include("../side_nav.php");

if (!$_SESSION['uname'])
    header('Location: https://vatlytruongnghiem.edu.vn/');

if (isset($_GET['MaLop']) && isset($_GET['MaBH'])) {
    $maLop = $_GET['MaLop'];
    $maBH = $_GET['MaBH'];
} else if (!isset($_GET['MaLop'])) {
    echo '<script>
    alert("Không tìm thấy mã lớp");
    window.location.href="/class/class_list.php";
    </script>';
} else if (!isset($_GET['MaBuoiHoc'])) {
    $maLop = $_GET['MaLop'];
    echo '<script>
    alert("Không tìm thấy mã buổi học!");
    window.location.href="/lesson/lesson_list.php?id=' . $maLop . '";
    </script>';
}
$class_info = $conn->query("SELECT * FROM lop WHERE MaLop = '$maLop'")->fetch_assoc();
$lesson_info = $conn->query("SELECT * FROM buoihoc WHERE MaBuoiHoc = '$maBH'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm điểm học sinh <?php echo $title; ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.png">
    <link rel="stylesheet" href="/assets/css/admin-input.css">
    <link rel="stylesheet" href="/assets/css/admin-statistical.css">
    <link rel="stylesheet" href="/assets/css/admin-navigation.css">
    <link rel="stylesheet" href="/assets/font/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @media screen and (min-width: 600px) {
            .Score {
                text-align: center;
                padding: 10px 20px;
                font-size: 22px;
                width: 100% !important;
                border: none;
                font-family: Times New Roman;
            }
        }

        @media screen and (max-width: 600px) {
            .Score {
                text-align: center;
                padding: 5px 20px;
                font-size: 10px;
                width: 100% !important;
                border: none;
                font-family: Times New Roman;
            }
        }

        table th,
        table td {
            padding: 5px;
            border: 1px solid #0d0d0d;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .fixed-button {
            font-size: 20px;
            position: fixed;
            top: 25px;
            right: 25px;
            background-color: #007bff;
            /* Màu nền */
            color: #fff;
            /* Màu chữ */
            border: none;
            border-radius: 50%;
            /* Bo tròn */
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            /* Hiệu ứng đổ bóng */
            cursor: pointer;
            z-index: 1000;
            /* Luôn hiển thị trên cùng */
        }

        .fixed-button:hover {
            background-color: #0056b3;
            /* Màu khi hover */
        }
    </style>
</head>

<body>
    <!-- Fixed Button -->
    <button class="fixed-button" onclick="nhapDiem()">
        <i class="fa-solid fa-chart-bar"></i>
    </button>

    <script>
        function nhapDiem() {
            window.location.href = "score_statistical.php?MaBuoiHoc=<?php echo $maBH ?>&MaLop=<?php echo $maLop ?>";
        }
    </script>

    <div class="content">
        <div class="input-score">
            <h1 style="margin-left:-40px; padding: 20px 0">Nhập điểm
                <?php echo $class_info['TenLop'] . " - " . $lesson_info['TenBai'] ?>
            </h1>

        </div>
        <table class="student-list" style="width: 100%">
            <tr>
                <td>SĐT</td>
                <td>Họ Tên</td>
                <td>Ngày Sinh</td>
                <td>Lớp</td>
                <td>Trường</td>
                <td style="width: 7rem">Điểm</td>
            </tr>

            <?php
            $query = "SELECT distinct hs.MaHS, Ho, Ten, NgaySinh, Lop, Truong, Phone, pl.MaPhanLop, pl.MaLop
                FROM hocsinh hs JOIN phanlop pl ON hs.MaHS = pl.MaHS
                WHERE pl.MaPhanLop not in (SELECT MaPhanLop FROM diemso WHERE MaBuoiHoc = '$maBH') and pl.MaLop = '$maLop' and pl.TinhTrang = 1
                ";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_array($result)) {
                echo '<form method = "POST" action ="./add_score.php?MaLop=' . $maLop . '&MaBH=' . $maBH . '&MaPL=' . $row['MaPhanLop'] . '">';
                echo '<tr>';
                echo '<td>' . $row['Phone'] . '</td>';
                echo '<td style= "text-align:left;padding-left:20px">' . $row['Ho'] . ' ' . $row['Ten'] . '</td>';
                echo '<td>' . $row['NgaySinh'] . '</td>';
                echo '<td>' . $row['Lop'] . '</td>';
                echo '<td>' . $row['Truong'] . '</td>';
                echo '<td>' . '<input class="Score" name ="Score" type = "text" onChange = "this.form.submit()"></td>';
                echo '</form>';
                echo '</tr>';
            }
            ?>
        </table>
        <h2 style="margin-left:50px">Đã nghỉ</h2>
        <table class="student-list" style="width: 100%">
            <tr>
                <td>SĐT</td>
                <td>Họ Tên</td>
                <td>Ngày Sinh</td>
                <td>Lớp</td>
                <td>Trường</td>
                <td style="width: 7rem">Điểm</td>
            </tr>
            <?php
            $query = "SELECT distinct hs.MaHS, Ho, Ten, NgaySinh, Lop, Truong, Phone, pl.MaPhanLop, pl.MaLop
                FROM hocsinh hs JOIN phanlop pl ON hs.MaHS = pl.MaHS
                WHERE pl.MaPhanLop not in (SELECT MaPhanLop FROM diemso WHERE MaBuoiHoc = '$maBH') and pl.MaLop = '$maLop' and pl.TinhTrang = 0
                ";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_array($result)) {
                echo '<form method = "POST" action ="./add_score.php?MaLop=' . $maLop . '&MaBH=' . $maBH . '&MaPL=' . $row['MaPhanLop'] . '">';
                echo '<tr>';
                echo '<td>' . $row['Phone'] . '</td>';
                echo '<td style= "text-align:left;padding-left:20px">' . $row['Ho'] . ' ' . $row['Ten'] . '</td>';
                echo '<td>' . $row['NgaySinh'] . '</td>';
                echo '<td>' . $row['Lop'] . '</td>';
                echo '<td>' . $row['Truong'] . '</td>';
                echo '<td>' . '<input class="Score" name ="Score" type = "text" onChange = "this.form.submit()"></td>';
                echo '</form>';
                echo '</tr>';
            }
            ?>
    </div>
    </div>
</body>

</html>