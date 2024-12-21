<?php
include("../connection.php");
if (isset($_POST['AlterScore'])) {
    $maLop = $_GET['MaLop'];
    $maBH = $_GET['MaBH'];
    $maPL = $_GET['MaPL'];
    $diem = $_POST['AlterScore'];
    if ($diem != "") {
        $sql = "UPDATE diemso SET Diem = '$diem' WHERE MaPhanLop = '$maPL' and MaBuoiHoc = '$maBH'";
        if ($conn->query($sql)) {
            $student_info = $conn->query("SELECT * FROM hocsinh hs JOIN phanlop pl ON hs.MaHS = pl.MaHS WHERE pl.MaPhanLop = $maPL")->fetch_assoc();
            $class_info = $conn->query("SELECT * FROM lop WHERE MaLop = $maLop")->fetch_assoc();
            echo '<script>
        alert("Sửa điểm thành công cho học sinh ' . $student_info['Ho'] . ' ' . $student_info['Ten'] . '!");
        window.location.href="/score/score_statistical.php?MaBuoiHoc=' . $maBH . '&MaLop=' . $maLop . '";
        </script>';
        }
    } else {
        $sql = "DELETE FROM diemso WHERE MaPhanLop = '$maPL' and MaBuoiHoc = '$maBH'";
        if ($conn->query($sql)) {
            $student_info = $conn->query("SELECT * FROM hocsinh hs JOIN phanlop pl ON hs.MaHS = pl.MaHS WHERE pl.MaPhanLop = $maPL")->fetch_assoc();
            $class_info = $conn->query("SELECT * FROM lop WHERE MaLop = $maLop")->fetch_assoc();
            echo '<script>
        alert("Xóa điểm thành công cho học sinh ' . $student_info['Ho'] . ' ' . $student_info['Ten'] . '!");
        window.location.href="/score/score_statistical.php?MaBuoiHoc=' . $maBH . '&MaLop=' . $maLop . '";
        </script>';
        }
    }
}
?>