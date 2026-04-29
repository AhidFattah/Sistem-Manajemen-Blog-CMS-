<?php
include 'koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $id_p = $_POST['id_penulis'];
    $id_k = $_POST['id_kategori'];
    $isi = $_POST['isi'];

    $stmt = $conn->prepare("UPDATE artikel SET judul=?, id_penulis=?, id_kategori=?, isi=? WHERE id=?");
    $stmt->bind_param("siisi", $judul, $id_p, $id_k, $isi, $id);
    
    if ($stmt->execute()) {
        // Cek jika ada unggahan gambar baru
        if (!empty($_FILES['gambar']['name'])) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $nama_baru = time() . "." . $ext;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads_artikel/" . $nama_baru)) {
                // Hapus gambar lama dari server
                $lama = $conn->query("SELECT gambar FROM artikel WHERE id = $id")->fetch_assoc();
                if (file_exists("uploads_artikel/" . $lama['gambar'])) {
                    unlink("uploads_artikel/" . $lama['gambar']);
                }
                // Update nama file di database
                $conn->query("UPDATE artikel SET gambar = '$nama_baru' WHERE id = $id");
            }
        }
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>