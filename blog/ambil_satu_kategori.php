<?php
include 'koneksi.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM kategori_artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
echo json_encode($stmt->get_result()->fetch_assoc());
?>