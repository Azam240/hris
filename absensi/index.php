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
$absensi = getData('absensi');
$jadwal_kehadiran = getData('jadwal_kehadiran');
$keterlambatan_dan_ketidakhadiran = getData('keterlambatan_dan_ketidakhadiran');

?>

<main id="main" class="main">
    <div class="container">
        <h1>Sistem Manajemen Absensi</h1>
        <ul class="nav nav-tabs" id="absensiTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="absensi-tab" data-bs-toggle="tab" href="#absensi" role="tab" aria-controls="absensi" aria-selected="true">Absensi</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="jadwal-tab" data-bs-toggle="tab" href="#jadwal" role="tab" aria-controls="jadwal" aria-selected="false">Jadwal Kehadiran</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="keterlambatan-tab" data-bs-toggle="tab" href="#keterlambatan" role="tab" aria-controls="keterlambatan" aria-selected="false">Keterlambatan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="ketidakhadiran-tab" data-bs-toggle="tab" href="#ketidakhadiran" role="tab" aria-controls="ketidakhadiran" aria-selected="false">Ketidakhadiran</a>
            </li>
        </ul>

        <div class="tab-content" id="absensiTabContent">
            <!-- Tab Absensi -->
            <div class="tab-pane fade show active" id="absensi" role="tabpanel" aria-labelledby="absensi-tab">
                <h2>Data Absensi</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absensi as $row): ?>
                            <tr>
                                <td><?= $row['Nama_Pegawai'] ?></td>
                                <td><?= $row['Tanggal_Waktu'] ?></td>
                                <td><span class="badge bg-<?= ($row['Status_Kehadiran'] == 'Hadir') ? 'success' : 
                                    (($row['Status_Kehadiran'] == 'Sakit') ? 'warning' : 
                                    (($row['Status_Kehadiran'] == 'Izin') ? 'info' : 'danger')) ?>">
                                    <?= $row['Status_Kehadiran'] ?></span></td>
                                <td><?= $row['Keterangan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab Jadwal Kehadiran -->
            <div class="tab-pane fade" id="jadwal" role="tabpanel" aria-labelledby="jadwal-tab">
                <h2>Jadwal Kehadiran</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Hari</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal_kehadiran as $row): ?>
                            <tr>
                                <td><?= $row['Nama_Pegawai'] ?></td>
                                <td><?= $row['Hari'] ?></td>
                                <td><?= $row['Jam_Masuk'] ?></td>
                                <td><?= $row['Jam_Keluar'] ?></td>
                                <td><?= $row['Keterangan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab Keterlambatan -->
            <div class="tab-pane fade" id="keterlambatan" role="tabpanel" aria-labelledby="keterlambatan-tab">
                <h2>Data Keterlambatan</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Tanggal</th>
                            <th>Durasi Keterlambatan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keterlambatan_dan_ketidakhadiran as $row): ?>
                            <tr>
                                <td><?= $row['Nama_Pegawai'] ?></td>
                                <td><?= $row['Tanggal'] ?></td>
                                <td><?= $row['Durasi_Keterlambatan'] ?> menit</td>
                                <td><span class="badge bg-warning">Terlambat</span></td>
                                <td><?= $row['Keterangan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tab Ketidakhadiran -->
            <div class="tab-pane fade" id="ketidakhadiran" role="tabpanel" aria-labelledby="ketidakhadiran-tab">
                <h2>Data Ketidakhadiran</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keterlambatan_dan_ketidakhadiran as $row): ?>
                            <tr>
                                <td><?= $row['Nama_Pegawai'] ?></td>
                                <td><?= $row['Tanggal'] ?></td>
                                <td><?= $row['Jenis_Ketidakhadiran'] ?></td>
                                <td><span class="badge bg-<?= ($row['Status'] == 'Disetujui') ? 'success' : 
                                    (($row['Status'] == 'Pending') ? 'warning' : 'danger') ?>">
                                    <?= $row['Status'] ?></span></td>
                                <td><?= $row['Keterangan'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main><!-- End #main -->
<style>
    .tab-pane {
        transition: all 0.5s ease;
    }
</style>

<!-- Add JavaScript for tab switching -->
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