<?php
include 'koneksi.php';

// Atur header agar browser tahu ini adalah JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan data tidak kosong
    if (isset($_POST['nama_kategori'])) {
        $nama = $_POST['nama_kategori'];
        $ket  = $_POST['keterangan'];

        $stmt = $conn->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $ket);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nama kategori wajib diisi']);
    }
}
?>