<?php
include 'koneksi.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM penulis WHERE id = $id");
echo json_encode($result->fetch_assoc());
?>