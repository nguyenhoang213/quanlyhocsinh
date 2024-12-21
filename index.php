<?php
include('connection.php')
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/image/logo.png">
    <title>Trang Web Tra Cứu Điểm Test Thường Xuyên Thầy Phạm Trường Nghiêm</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/index_style.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/font/themify-icons/themify-icons.css">
    <script src="https://kit.fontawesome.com/8fcd74b091.js" crossorigin="anonymous"></script>
    <style>
        .popup {
            display: none;
            /* Ẩn popup mặc định */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup.active {
            display: block;
            /* Hiển thị popup khi có lớp "active" */
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup-overlay.active {
            display: block;
        }

        /* CSS cho button trong popup với màu xanh da trời nhạt */
        .popup button {
            background-color: #87CEEB;
            /* Màu xanh da trời nhạt */
            color: white;
            /* Màu chữ trắng */
            border: none;
            /* Bỏ viền */
            padding: 10px 20px;
            /* Khoảng cách trong */
            text-align: center;
            /* Căn giữa văn bản */
            text-decoration: none;
            /* Bỏ gạch chân */
            display: inline-block;
            /* Đặt nút inline */
            font-size: 16px;
            /* Kích thước chữ */
            margin: 10px 0;
            /* Khoảng cách giữa các nút */
            cursor: pointer;
            /* Thay đổi con trỏ khi hover */
            border-radius: 5px;
            /* Bo góc nút */
            transition: background-color 0.3s ease;
            /* Hiệu ứng khi hover */
        }

        /* Thay đổi màu nền khi hover vào nút */
        .popup button:hover {
            background-color: #00BFFF;
            /* Xanh da trời đậm hơn khi hover */
        }

        /* CSS cho button đóng popup với màu xanh da trời */
        .popup button.close-btn {
            background-color: #4682B4;
            /* Màu xanh đậm cho nút đóng */
            margin-top: 20px;
            /* Khoảng cách phía trên nút đóng */
        }

        .popup button.close-btn:hover {
            background-color: #5A9BD5;
            /* Xanh sáng hơn khi hover cho nút đóng */
        }
    </style>
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
        <div class="find-box">
            <h1>TRA CỨU THÔNG TIN HỌC SINH</h1>
            <form action="" method="GET">
                <input type="text" name="PhoneNumber" placeholder="Số Điện Thoại"><button type="submit"
                    style="background-color:red">Tìm
                    Kiếm</button>
            </form>
            <div class="popup-overlay" id="popupOverlay"></div>
            <div class="popup" id="popup">
                <h3>Chọn Lớp</h3>
                <div id="classList">
                    <!-- Các lớp sẽ hiển thị ở đây -->
                </div>
                <button class="close-btn" onclick="closePopup()">Đóng</button>
            </div>

            <script>
                function openPopup() {
                    document.getElementById('popup').classList.add('active');
                    document.getElementById('popupOverlay').classList.add('active');
                }

                function closePopup() {
                    document.getElementById('popup').classList.remove('active');
                    document.getElementById('popupOverlay').classList.remove('active');
                }
            </script>

            <?php
            if (isset($_GET['PhoneNumber'])) {
                $PhoneNumber = $_GET['PhoneNumber'];

                // Truy vấn thông tin học sinh dựa trên số điện thoại
                $student_query = $conn->query("SELECT * FROM hocsinh WHERE Phone = '$PhoneNumber'");

                if ($student_query && $student_query->num_rows > 0) {
                    $student_info = $student_query->fetch_assoc();
                    $maHS = $student_info['MaHS'];

                    // Truy vấn các lớp học của học sinh
                    $sql = "SELECT DISTINCT pl.MaLop, l.TenLop, pl.MaPhanLop
                            FROM lop l 
                            JOIN phanlop pl ON l.MaLop = pl.MaLop
                            WHERE pl.MaHS = '$maHS' 
                            ORDER BY TenLop";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<script>openPopup();</script>'; // Mở popup bằng JavaScript
                        echo '<script>
                            var classListDiv = document.getElementById("classList");
                            classListDiv.innerHTML = ""; // Xóa nội dung cũ nếu có
                        </script>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<script>
                            var classListDiv = document.getElementById("classList");
                            var classItem = document.createElement("button");
                            classItem.innerHTML = "Lớp: ' . $row['TenLop'] . ' (Mã lớp: ' . $row['MaLop'] . ')";
                            classItem.onclick = function() {
                                window.location.href = "/student/student.php?MaHS=' . $maHS . '&MaPL=' . $row['MaPhanLop'] . '";
                            };
                            classListDiv.appendChild(classItem);
                        </script>';
                        }
                    }
                } else {
                    echo '<script>
                    alert("Không tìm thấy lớp học nào cho số điện thoại này.")</script>';
                }
            }
            ?>
        </div>
        <div class="about">
            <h1>Thầy Phạm Trường Nghiêm</h1>
            <div class="info">
                <img src="./assets/image/h4.jpg" alt="">
                <div class="info-text">
                    <p>- Tổ trưởng chuyên môn Vật lí trường Nguyễn Tất Thành - ĐHSP Hà Nội.</p>
                    <p>- Từng giảng dạy trên VTV2 và viết sách hướng dẫn ôn thi Đại Học.</p>
                    <p>- 24 năm luyện thi môn Vật lí, đào tạo hàng nghìn học sinh thi đỗ vào các trường Đại Học top đầu
                        của cả nước. Đào tạo nhiều thủ khoa trong kì thi tuyển sinh Đại Học.</p>
                    <p>- Có học sinh điểm trung bình thi Đại Học môn Vật lí cao nhất thành phố Hà Nội trong 4 năm liên
                        tiếp: 2019, 2020, 2021, 2022.</p>
                    <p>- Phương pháp giảng dạy sáng tạo, lôi cuốn, nghiêm túc trong chuyên môn, luôn thấu hiểu và giúp
                        học sinh tiến bộ mỗi ngày.</p>
                    <p>- Luôn nỗ lực học hỏi, truyền cảm hứng học tập suốt đời cho mỗi học sinh.</p>
                </div>
            </div>

        </div>
    </div>
    <div id="footer">
        <div class="address">
            <div class="get-in-touch">
                <h1>Phạm Trường Nghiêm</h1>
                <p><a href="https://www.facebook.com/truongnghiem.dhcn"><i class="ti-facebook icon"></i></a> Tất cả vì
                    sự tiến bộ của học trò!</p>
                <h1>Get In Touch</h1>
                <p><i class="ti-map-alt icon"></i> Số 32 - Ngõ 3 - Thôn Đông - Tàm Xá - Đông Anh - Hà Nội</p>
                <p><i class="ti-map-alt icon"></i> Số 03 - Ngách 69 - Ngõ 260 - Cầu Giấy </p>
                <p><i class="ti-headphone-alt icon"></i> 098 336 1907</p>
                <p><i class="ti-email icon"></i> nghiemcn@gmail.com</p>
            </div>
        </div>
        <div class="description">
            <p>Copyright ©<a href="https://www.facebook.com/as.royal03/">Nguyễn Như Hoàng</a>. All Rights Reserved.</p>
            <p>Desgined by <a href="https://www.facebook.com/as.royal03/">Nguyễn Như Hoàng</a>.</p>
        </div>
    </div>
</body>

</html>