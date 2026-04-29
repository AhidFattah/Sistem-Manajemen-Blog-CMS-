<?php
include 'koneksi.php';

// Atur format tanggal sesuai instruksi soal [cite: 88-102]
date_default_timezone_set('Asia/Jakarta');
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$sekarang = new DateTime();
$hari_tanggal = $hari[$sekarang->format('w')] . ", " . $sekarang->format('j') . " " . $bulan[(int)$sekarang->format('n')] . " " . $sekarang->format('Y') . " | " . $sekarang->format('H:i');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul       = $_POST['judul'];
    $id_penulis  = $_POST['id_penulis'];
    $id_kategori = $_POST['id_kategori'];
    $isi         = $_POST['isi'];
    
    // Upload Gambar Wajib [cite: 103]
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_gambar = time() . '.' . $ext;
    move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads_artikel/" . $nama_gambar);

    $stmt = $conn->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $nama_gambar, $hari_tanggal);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>