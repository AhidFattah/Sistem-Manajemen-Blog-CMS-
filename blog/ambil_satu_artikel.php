<?php
include 'koneksi.php';
$id = (int)$_GET['id'];
$query = $conn->query("SELECT * FROM artikel WHERE id = $id");
echo json_encode($query->fetch_assoc());
?>