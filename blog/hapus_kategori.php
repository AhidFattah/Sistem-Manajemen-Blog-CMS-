<?php
include 'koneksi.php';

$id = $_GET['id'];

// Validasi: Cek apakah kategori masih memiliki artikel
$cek = $conn->prepare("SELECT id FROM artikel WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
$res = $cek->get_result();

if ($res->num_rows > 0) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa artikel.'
    ]);
} else {
    $stmt = $conn->prepare("DELETE FROM kategori_artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>