<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../connection.php';

// Fungsi untuk mendapatkan data dari tabel
function getData($table) {
    global $conn;
    $sql = "SELECT * FROM `$table`";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Mendapatkan data dari tabel
$lowongan = getData('lowongan');
$departemen = getData('departemen');
$jabatan = getData('jabatan');
$pelamar = getData('pelamar');
$tahap_lamaran = getData('tahap_lamaran');
$penilaian_pelamar = getData('penilaian_pelamar');
?>
<main id="main" class="main">
    <div class="container">
        <h1 class="text-center my-4 page-title">Recruitment</h1>
        <ul class="nav nav-tabs justify-content-center" id="recruitmentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="lowongan-tab" data-bs-toggle="tab" href="#lowongan" role="tab" aria-controls="lowongan" aria-selected="true">Lowongan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="departemen-tab" data-bs-toggle="tab" href="#departemen" role="tab" aria-controls="departemen" aria-selected="false">Departemen</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="jabatan-tab" data-bs-toggle="tab" href="#jabatan" role="tab" aria-controls="jabatan" aria-selected="false">Jabatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pelamar-tab" data-bs-toggle="tab" href="#pelamar" role="tab" aria-controls="pelamar" aria-selected="false">Pelamar</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tahap-lamaran-tab" data-bs-toggle="tab" href="#tahap-lamaran" role="tab" aria-controls="tahap-lamaran" aria-selected="false">Tahap Lamaran</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="penilaian-pelamar-tab" data-bs-toggle="tab" href="#penilaian-pelamar" role="tab" aria-controls="penilaian-pelamar" aria-selected="false">Penilaian Pelamar</a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="recruitmentTabContent">
            <div class="tab-pane fade show active" id="lowongan" role="tabpanel" aria-labelledby="lowongan-tab">
                <h2 class="section-title">Lowongan</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Lowongan</th>
                            <th>Deskripsi</th>
                            <th>ID Jabatan</th>
                            <th>Tanggal Posting</th>
                            <th>Tanggal Tutup</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowongan as $row): ?>
                            <tr>
                                <td><?= $row['id_lowongan'] ?></td>
                                <td><?= $row['nama_lowongan'] ?></td>
                                <td><?= $row['deskripsi_lowongan'] ?></td>
                                <td><?= $row['id_jabatan'] ?></td>
                                <td><?= $row['tgl_posting'] ?></td>
                                <td><?= $row['tgl_tutup'] ?></td>
                                <td><?= $row['status'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="departemen" role="tabpanel" aria-labelledby="departemen-tab">
                <h2 class="section-title">Departemen</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Departemen</th>
                            <th>Kepala Departemen</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($departemen as $row): ?>
                            <tr>
                                <td><?= $row['id_departemen'] ?></td>
                                <td><?= $row['nama_departemen'] ?></td>
                                <td><?= $row['kepala_departemen'] ?></td>
                                <td><?= $row['lokasi_departemen'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="jabatan" role="tabpanel" aria-labelledby="jabatan-tab">
                <h2 class="section-title">Jabatan</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Jabatan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>ID Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jabatan as $row): ?>
                            <tr>
                                <td><?= $row['id_jabatan'] ?></td>
                                <td><?= $row['nama_jabatan'] ?></td>
                                <td><?= $row['desk_jabatan'] ?></td>
                                <td><?= $row['status_jabatan'] ?></td>
                                <td><?= $row['id_departemen'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="pelamar" role="tabpanel" aria-labelledby="pelamar-tab">
                <h2 class="section-title">Pelamar</h2>
                <form method="post" action="submit_pelamar.php">
                    <div class="mb-3">
                        <label for="id_pelamar" class="form-label">ID Pelamar</label>
                        <input type="text" class="form-control" id="id_pelamar" name="id_pelamar" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pel" class="form-label">Nama Pelamar</label>
                        <input type="text" class="form-control" id="nama_pel" name="nama_pel" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_pel" class="form-label">Email Pelamar</label>
                        <input type="email" class="form-control" id="email_pel" name="email_pel" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_lowongan" class="form-label">ID Lowongan</label>
                        <input type="text" class="form-control" id="id_lowongan" name="id_lowongan" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan_dipilih" class="form-label">Jabatan Dipilih</label>
                        <input type="text" class="form-control" id="jabatan_dipilih" name="jabatan_dipilih" required>
                    </div>
                    <button type="submit" class="btn btn-primary submit-button">Submit</button>
                </form>
                <table class="table mt-3 table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>ID Lowongan</th>
                            <th>Status</th>
                            <th>Jabatan Dipilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pelamar as $row): ?>
                            <tr>
                                <td><?= $row['id_pelamar'] ?></td>
                                <td><?= $row['nama_pel'] ?></td>
                                <td><?= $row['email_pel'] ?></td>
                                <td><?= $row['id_lowongan'] ?></td>
                                <td><?= $row['status_pel'] ?></td>
                                <td><?= $row['jabatan_dipilih'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="tahap-lamaran" role="tabpanel" aria-labelledby="tahap-lamaran-tab">
                <h2 class="section-title">Tahap Lamaran</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Tahap</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tahap_lamaran as $row): ?>
                            <tr>
                                <td><?= $row['id_tahap'] ?></td>
                                <td><?= $row['nama_tahap'] ?></td>
                                <td><?= $row['deskripsi_tahap'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="penilaian-pelamar" role="tabpanel" aria-labelledby="penilaian-pelamar-tab">
                <h2 class="section-title">Penilaian Pelamar</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Pelamar</th>
                            <th>ID Tahap</th>
                            <th>Tanggal Dinilai</th>
                            <th>Skor</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penilaian_pelamar as $row): ?>
                            <tr>
                                <td><?= $row['id_penilaian_pel'] ?></td>
                                <td><?= $row['id_pelamar'] ?></td>
                                <td><?= $row['id_tahap'] ?></td>
                                <td><?= $row['tgl_dinilai'] ?></td>
                                <td><?= $row['skor'] ?></td>
                                <td><?= $row['catatan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<style>
    body {
        background-color:rgb(251, 251, 251);
        color: #1e90ff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeIn 1s ease-in-out;
    }
    .page-title, .welcome-title {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1e90ff;
        animation: fadeInDown 1s ease-in-out;
    }
    .section-title, .welcome-text {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1e90ff;
        animation: fadeInUp 1s ease-in-out;
    }
    .nav-tabs .nav-link {
        border: 1px solid #1e90ff;
        border-radius: 0.25rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1e90ff;
        transition: background-color 0.3s ease, color 0.3s ease;
        animation: fadeIn 1s ease-in-out;
    }
    .nav-tabs .nav-link.active {
        background-color: #1e90ff;
        color: white;
    }
    .nav-tabs .nav-link:hover {
        background-color: #1e90ff;
        color: #1e90ff;
    }
    .card {
        background-color: #1e2127;
        border: 3px solid #1e90ff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 700;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 30px 20px;
        color: #1e90ff;
        border-radius: 45px;
        margin-top: 20px;
        animation: fadeIn 1s ease-in-out;
    }
    .App-logo {
        pointer-events: none;
        margin-top: -10px;
        animation: App-logo-spin infinite 5s linear;
    }
    .App-button {
        padding: 10px 20px;
        background-color: transparent;
        color: #1e90ff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        border: 3px solid #1e90ff;
        border-radius: 5em;
        margin-top: 20px;
        transition: 0.1s;
        text-decoration: none;
        text-align: center;
        animation: fadeIn 1s ease-in-out;
    }
    .App-button:hover {
        color: #1e2127;
        background-color: #1e90ff;
    }
    .table {
        animation: fadeIn 1.5s ease-in-out;
    }
    .form-label, .form-control, .submit-button {
        animation: fadeIn 1.5s ease-in-out;
    }
    .submit-button {
        background-color: #1e90ff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .submit-button:hover {
        background-color: #1e90ff;
        color: #1e2127;
    }
    @media (prefers-reduced-motion: no-preference) {
        .App-logo {
            animation: App-logo-spin infinite 5s linear;
        }
    }
    @keyframes App-logo-spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<style>
    .tab-pane {
        transition: all 0.5s ease;
    }
</style>

<script>
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('href'));
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            target.classList.add('show', 'active');
        });
    });
</script>
<?php
include '../template/footer.php';
?>