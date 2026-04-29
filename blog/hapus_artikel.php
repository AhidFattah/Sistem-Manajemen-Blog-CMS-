<?php
include 'koneksi.php';
header('Content-Type: application/json');

$id = (int)$_GET['id'];
$res = $conn->query("SELECT gambar FROM artikel WHERE id = $id");
$data = $res->fetch_assoc();

if ($data) {
    $path = "uploads_artikel/" . $data['gambar'];
    if (file_exists($path)) {
        unlink($path); // Hapus file gambar dari server [cite: 107]
    }
    
    $stmt = $conn->prepare("DELETE FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>