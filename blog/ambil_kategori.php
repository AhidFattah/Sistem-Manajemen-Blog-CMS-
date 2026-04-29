<?php
include 'koneksi.php'; // [cite: 123]
$query = "SELECT * FROM kategori_artikel ORDER BY id DESC"; // [cite: 27]
$result = $conn->query($query);
?>
<div class="d-flex justify-content-between mb-3">
    <h5>Data Kategori Artikel</h5>
    <button class="btn btn-success btn-sm" onclick="showTambahKategori()">+ Tambah Kategori</button>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>NAMA KATEGORI</th>
            <th>KETERANGAN</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_kategori']) ?></td> <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="editKategori(<?= $row['id'] ?>)">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="konfirmasiHapusKategori(<?= $row['id'] ?>)">Hapus</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>