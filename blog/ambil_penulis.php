<?php
include 'koneksi.php';

$query = "SELECT * FROM penulis ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Data Penulis</h5>
    <button class="btn btn-success btn-sm" onclick="showTambahPenulis()">
        <i class="fas fa-plus"></i> Tambah Penulis
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">FOTO</th>
                    <th>NAMA</th>
                    <th>USERNAME</th>
                    <th>PASSWORD</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): 
                    $foto = !empty($row['foto']) ? 'uploads_penulis/'.$row['foto'] : 'uploads_penulis/default.png';
                ?>
                <tr>
                    <td class="ps-3">
                        <img src="<?= $foto ?>" class="rounded" width="40" height="40" style="object-fit: cover;">
                    </td>
                    <td><?= htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']) ?></td>
                    <td><span class="badge bg-light text-primary"><?= htmlspecialchars($row['user_name']) ?></span></td>
                    <td class="text-muted small"><?= isset($row['password']) ? substr($row['password'], 0, 10) . '...' : '-' ?></td>
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm" onclick="editPenulis(<?= $row['id'] ?>)">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="konfirmasiHapus(<?= $row['id'] ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>