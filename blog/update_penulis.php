<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama_depan = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    $user_name = $_POST['user_name'];
    $stmt = $conn->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=? WHERE id=?");
    $stmt->bind_param("sssi", $nama_depan, $nama_belakang, $user_name, $id);
    $stmt->execute();

    if (!empty($_POST['password'])) {
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $conn->query("UPDATE penulis SET password = '$pass' WHERE id = $id");
    }

    if (!empty($_FILES['foto']['name'])) {
        $foto_name = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads_penulis/" . $foto_name);
        $conn->query("UPDATE penulis SET foto = '$foto_name' WHERE id = $id");
    }

    echo json_encode(['status' => 'success']);
}
?>