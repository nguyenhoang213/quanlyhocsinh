<?php
include("../connection.php"); // Kết nối đến cơ sở dữ liệu
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['uname'])) {
    echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu dữ liệu cần thiết được cung cấp
if (!isset($data['MaHS'], $data['password'], $data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không đầy đủ.']);
    exit();
}

// Gán giá trị từ yêu cầu
$maHS = $data['MaHS'];
$password = $data['password'];
$action = $data['action'];
$maLop = isset($data['MaLop']) ? $data['MaLop'] : null;

// Kiểm tra mật khẩu admin
$username = $_SESSION['uname'];
$sql = "SELECT PassWord FROM admin WHERE UserName = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Tài khoản admin không tồn tại.']);
    exit();
}

$row = $result->fetch_assoc();
$adminPassword = $row['PassWord'];

// So sánh mật khẩu
if ($password !== $adminPassword) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng.']);
    exit();
}

// Thực hiện hành động
if ($action === 'delete_from_system') {
    // Xóa học sinh khỏi hệ thống
    $sql = "DELETE FROM hocsinh WHERE MaHS = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $maHS);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Học sinh đã được xóa khỏi hệ thống.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa học sinh khỏi hệ thống.']);
    }

} elseif ($action === 'delete_from_class' && $maLop) {
    // Xóa học sinh khỏi lớp
    $sql = "DELETE FROM phanlop WHERE MaHS = ? AND MaLop = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $maHS, $maLop);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Học sinh đã được xóa khỏi lớp.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa học sinh khỏi lớp.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>