<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $cek_artikel = $conn->prepare("SELECT id FROM artikel WHERE id_penulis = ?");
    $cek_artikel->bind_param("i", $id);
    $cek_artikel->execute();
    $hasil_cek = $cek_artikel->get_result();

    if ($hasil_cek->num_rows > 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Gagal: Penulis ini masih memiliki artikel. Hapus artikelnya terlebih dahulu.'
        ]);
    } else {
        $query_foto = $conn->prepare("SELECT foto FROM penulis WHERE id = ?");
        $query_foto->bind_param("i", $id);
        $query_foto->execute();
        $res_foto = $query_foto->get_result();
        $data_penulis = $res_foto->fetch_assoc();
        $stmt = $conn->prepare("DELETE FROM penulis WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($data_penulis['foto'] != 'default.png' && !empty($data_penulis['foto'])) {
                $path = "uploads_penulis/" . $data_penulis['foto'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            echo json_encode(['status' => 'success', 'message' => 'Data penulis berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $conn->error]);
        }
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
}
?>