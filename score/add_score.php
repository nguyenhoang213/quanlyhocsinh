<?php
include("../connection.php");
if (isset($_POST['Score'])) {
    $maLop = $_GET['MaLop'];
    $maBH = $_GET['MaBH'];
    $maPL = $_GET['MaPL'];
    $diem = $_POST['Score'];
    $sql = "INSERT INTO diemso(MaPhanLop, MaBuoiHoc, Diem) VALUES ('$maPL','$maBH','$diem')";
    if ($conn->query($sql)) {
        $student_info = $conn->query("SELECT * FROM hocsinh hs JOIN phanlop pl ON hs.MaHS = pl.MaHS WHERE pl.MaPhanLop = $maPL")->fetch_assoc();
        $class_info = $conn->query("SELECT * FROM lop WHERE MaLop = $maLop")->fetch_assoc();
        $upload = "UPDATE phanlop SET TinhTrang='1' WHERE MaPhanLop = " . $maPL;
        $conn->query($upload);
        echo '<script>
        alert("Nhập điểm thành công cho học sinh ' . $student_info['Ho'] . ' ' . $student_info['Ten'] . '!");
        window.location.href="/score/input_score.php?MaBH=' . $maBH . '&MaLop=' . $maLop . '";
        </script>';
    } else {

    }
}
?>