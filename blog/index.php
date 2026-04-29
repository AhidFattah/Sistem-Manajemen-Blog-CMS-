<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Blog (CMS)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .header { background-color: #343a40; color: white; padding: 15px; }
        .sidebar { min-height: calc(100vh - 60px); background-color: #ffffff; border-right: 1px solid #dee2e6; }
        .nav-link { color: #333; cursor: pointer; }
        .nav-link:hover, .nav-link.active { background-color: #0d6efd; color: white !important; }
        .main-content { padding: 20px; }
    </style>
</head>
<body>

<div class="header shadow-sm">
    <div class="container-fluid">
        <h4 class="mb-0"><i class="fas fa-blog"></i> Sistem Manajemen Blog (CMS)</h4>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" onclick="loadContent('penulis')">
                            <i class="fas fa-users me-2"></i> Kelola Penulis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="loadContent('artikel')">
                            <i class="fas fa-newspaper me-2"></i> Kelola Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="loadContent('kategori')">
                            <i class="fas fa-tags me-2"></i> Kelola Kategori Artikel
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div id="content-area">
                <div class="text-center mt-5">
                    <h5>Selamat Datang di Dashboard CMS</h5>
                    <p class="text-muted">Pilih menu di samping untuk mulai mengelola data.</p>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="mainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Form Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

function loadContent(menu) {
    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
    if (window.event && window.event.currentTarget) {
        window.event.currentTarget.classList.add('active');
    }

    const contentArea = document.getElementById('content-area');
    contentArea.innerHTML = '<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"></div><p>Memuat data...</p></div>';

    let url = '';
    if(menu === 'penulis') url = 'ambil_penulis.php';
    else if(menu === 'artikel') url = 'ambil_artikel.php';
    else if(menu === 'kategori') url = 'ambil_kategori.php';

    fetch(url)
        .then(response => response.text())
        .then(data => { contentArea.innerHTML = data; })
        .catch(error => { contentArea.innerHTML = `<div class="alert alert-danger">Gagal: ${error}</div>`; });
}

window.onload = () => loadContent('penulis');

// CRUD BAGIAN PENULIS

function showTambahPenulis() {
    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Tambah Penulis';
    document.getElementById('modalBody').innerHTML = `
        <form id="formPenulis" onsubmit="simpanPenulis(event)">
            <div class="row mb-3">
                <div class="col"><label class="form-label">Nama Depan</label><input type="text" name="nama_depan" class="form-control" required></div>
                <div class="col"><label class="form-label">Nama Belakang</label><input type="text" name="nama_belakang" class="form-control" required></div>
            </div>
            <div class="mb-3"><label class="form-label">Username</label><input type="text" name="user_name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Foto Profil</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
            <div class="text-end border-top pt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Data</button>
            </div>
        </form>`;
    modal.show();
}

function simpanPenulis(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formPenulis'));
    fetch('simpan_penulis.php', { method: 'POST', body: formData })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('penulis');
        } else { alert('Gagal: ' + data.message); }
    });
}

function konfirmasiHapus(id) {
    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Konfirmasi Hapus';
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center p-3">
            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
            <h5>Hapus data ini?</h5>
            <p class="text-muted small">Data tidak dapat dikembalikan.</p>
            <div class="mt-4">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="eksekusiHapus(${id})">Ya, Hapus</button>
            </div>
        </div>`;
    modal.show();
}

function eksekusiHapus(id) {
    fetch(`hapus_penulis.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('penulis');
        } else { alert(data.message); }
    });
}

function editPenulis(id) {
    fetch(`ambil_satu_penulis.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('mainModal'));
            document.getElementById('modalTitle').innerText = 'Edit Penulis';
            document.getElementById('modalBody').innerHTML = `
                <form id="formEditPenulis" onsubmit="updatePenulis(event, ${id})">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Nama Depan</label>
                            <input type="text" name="nama_depan" class="form-control" value="${data.nama_depan}" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Nama Belakang</label>
                            <input type="text" name="nama_belakang" class="form-control" value="${data.nama_belakang}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="user_name" class="form-control" value="${data.user_name}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Profil Baru (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">File saat ini: ${data.foto}</small>
                    </div>
                    <div class="text-end border-top pt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            `;
            modal.show();
        });
}

function updatePenulis(event, id) {
    event.preventDefault();
    const form = document.getElementById('formEditPenulis');
    const formData = new FormData(form);
    formData.append('id', id); 

    fetch('update_penulis.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('penulis'); 
        } else {
            alert('Gagal update: ' + data.message);
        }
    });
}

// CRUD KATEGORI ARTIKEL

function showTambahKategori() {
    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Tambah Kategori';
    document.getElementById('modalBody').innerHTML = `
        <form id="formKategori" onsubmit="simpanKategori(event)">
            <div class="mb-3"><label class="form-label">Nama Kategori</label><input type="text" name="nama_kategori" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="3"></textarea></div>
            <div class="text-end border-top pt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Data</button>
            </div>
        </form>`;
    modal.show();
}

function simpanKategori(event) {
    event.preventDefault();
    fetch('simpan_kategori.php', { method: 'POST', body: new FormData(document.getElementById('formKategori')) })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('kategori'); 
        }
    });
}

function editKategori(id) {
    fetch(`ambil_satu_kategori.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('mainModal'));
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('modalBody').innerHTML = `
                <form id="formEditKategori" onsubmit="updateKategori(event, ${id})">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" value="${data.nama_kategori}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">${data.keterangan}</textarea>
                    </div>
                    <div class="text-end border-top pt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            `;
            modal.show();
        });
}

function updateKategori(event, id) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formEditKategori'));
    formData.append('id', id);

    fetch('update_kategori.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('kategori');
        } else {
            alert('Gagal: ' + data.message);
        }
    });
}

function konfirmasiHapusKategori(id) {
    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Konfirmasi Hapus';
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center p-3">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h5>Hapus data ini?</h5>
            <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan.</p>
            <div class="mt-4">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="eksekusiHapusKategori(${id})">Ya, Hapus</button>
            </div>
        </div>
    `;
    modal.show();
}

function eksekusiHapusKategori(id) {
    fetch(`hapus_kategori.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
                loadContent('kategori'); 
            } else {
                alert(data.message); 
            }
        });
}

// CRUD BAGIAN ARTIKEL 

async function showTambahArtikel() {
    const resP = await fetch('ambil_data_dropdown.php?type=penulis');
    const penulis = await resP.json();
    const resK = await fetch('ambil_data_dropdown.php?type=kategori');
    const kategori = await resK.json();

    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Tambah Artikel';
    document.getElementById('modalBody').innerHTML = `
        <form id="formArtikel" onsubmit="simpanArtikel(event)">
            <div class="mb-3"><label class="form-label">Judul</label><input type="text" name="judul" class="form-control" required></div>
            <div class="row mb-3">
                <div class="col"><label class="form-label">Penulis</label><select name="id_penulis" class="form-select">${penulis.map(p => `<option value="${p.id}">${p.nama_depan} ${p.nama_belakang}</option>`).join('')}</select></div>
                <div class="col"><label class="form-label">Kategori</label><select name="id_kategori" class="form-select">${kategori.map(k => `<option value="${k.id}">${k.nama_kategori}</option>`).join('')}</select></div>
            </div>
            <div class="mb-3"><label class="form-label">Isi</label><textarea name="isi" class="form-control" rows="4" required></textarea></div>
            <div class="mb-3"><label class="form-label">Gambar</label><input type="file" name="gambar" class="form-control" accept="image/*" required></div>
            <div class="text-end border-top pt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Data</button>
            </div>
        </form>`;
    modal.show();
}

function simpanArtikel(event) {
    event.preventDefault();
    fetch('simpan_artikel.php', { method: 'POST', body: new FormData(document.getElementById('formArtikel')) })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('artikel'); 
        }
    });
}
async function editArtikel(id) {
    const res = await fetch(`ambil_satu_artikel.php?id=${id}`);
    const data = await res.json();
    
    const resP = await fetch('ambil_data_dropdown.php?type=penulis');
    const penulis = await resP.json();
    const resK = await fetch('ambil_data_dropdown.php?type=kategori');
    const kategori = await resK.json();

    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Edit Artikel';
    document.getElementById('modalBody').innerHTML = `
        <form id="formEditArtikel" onsubmit="updateArtikel(event, ${id})">
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" class="form-control" value="${data.judul}" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Penulis</label>
                    <select name="id_penulis" class="form-select">
                        ${penulis.map(p => `<option value="${p.id}" ${p.id == data.id_penulis ? 'selected' : ''}>${p.nama_depan} ${p.nama_belakang}</option>`).join('')}
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select">
                        ${kategori.map(k => `<option value="${k.id}" ${k.id == data.id_kategori ? 'selected' : ''}>${k.nama_kategori}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Artikel</label>
                <textarea name="isi" class="form-control" rows="5" required>${data.isi}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar (Kosongkan jika tidak diganti)</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <div class="text-end border-top pt-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>`;
    modal.show();
}

function updateArtikel(event, id) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formEditArtikel'));
    formData.append('id', id);

    fetch('update_artikel.php', { method: 'POST', body: formData })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('artikel');
        }
    });
}

function konfirmasiHapusArtikel(id) {
    const modal = new bootstrap.Modal(document.getElementById('mainModal'));
    document.getElementById('modalTitle').innerText = 'Konfirmasi Hapus';
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center p-3">
            <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
            <h5>Hapus artikel ini?</h5>
            <p class="text-muted small">Data dan file gambar akan dihapus permanen.</p>
            <div class="mt-4">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="eksekusiHapusArtikel(${id})">Ya, Hapus</button>
            </div>
        </div>`;
    modal.show();
}

function eksekusiHapusArtikel(id) {
    fetch(`hapus_artikel.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
            loadContent('artikel');
        } else {
            alert(data.message);
        }
    });
}

</script>

</body>
</html>