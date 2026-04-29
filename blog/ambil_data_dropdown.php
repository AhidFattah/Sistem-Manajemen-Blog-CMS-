<?php
include 'koneksi.php';
$type = $_GET['type'];
if($type == 'penulis') {
    $res = $conn->query("SELECT id, nama_depan, nama_belakang FROM penulis");
} else {
    $res = $conn->query("SELECT id, nama_kategori FROM kategori_artikel");
}
echo json_encode($res->fetch_all(MYSQLI_ASSOC));
?>