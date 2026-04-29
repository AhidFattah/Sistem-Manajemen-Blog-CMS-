<?php
include 'koneksi.php'; // Mengambil konfigurasi database 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_depan    = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    $user_name     = $_POST['user_name'];
    $password      = password_hash($_POST['password'], PASSWORD_BCRYPT); // Enkripsi password [cite: 80]
    
    // Penanganan upload foto [cite: 103, 115]
    $foto_name = 'default.png'; // Default jika tidak ada upload [cite: 79]
    if (!empty($_FILES['foto']['name'])) {
        $temp      = $_FILES['foto']['tmp_name'];
        $foto_name = time() . '_' . $_FILES['foto']['name'];
        $target    = "uploads_penulis/" . $foto_name;
        
        // Validasi tipe file (Opsional: gunakan finfo untuk keamanan lebih) [cite: 115]
        move_uploaded_file($temp, $target);
    }

    // Menggunakan Prepared Statement untuk keamanan [cite: 114]
    $stmt = $conn->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $password, $foto_name);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    $conn->close();
}
?>