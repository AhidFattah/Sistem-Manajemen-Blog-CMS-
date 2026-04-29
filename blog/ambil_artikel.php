<?php
include 'koneksi.php';

$query = "SELECT artikel.*, penulis.nama_depan, penulis.nama_belakang, kategori_artikel.nama_kategori 
          FROM artikel 
          JOIN penulis ON artikel.id_penulis = penulis.id 
          JOIN kategori_artikel ON artikel.id_kategori = kategori_artikel.id 
          ORDER BY artikel.id DESC";
$result = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Data Artikel</h5>
    <button class="btn btn-success btn-sm" onclick="showTambahArtikel()">+ Tambah Artikel</button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0 overflow-auto">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">GAMBAR</th>
                    <th>JUDUL</th>
                    <th>KATEGORI</th>
                    <th>PENULIS</th>
                    <th>TANGGAL</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="ps-3">
                        <img src="uploads_artikel/<?= $row['gambar'] ?>" class="rounded" width="50" height="35" style="object-fit: cover;">
                    </td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><span class="badge bg-light text-info border border-info"><?= $row['nama_kategori'] ?></span></td>
                    <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                    <td class="small text-muted"><?= $row['hari_tanggal'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm" onclick="editArtikel(<?= $row['id'] ?>)">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="konfirmasiHapusArtikel(<?= $row['id'] ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>