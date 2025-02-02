<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db_connection.php'; // Sesuaikan dengan file koneksi DB Anda

    $nama_pelatihan = $_POST['nama_pelatihan'];
    $tgl_pelaksanaan = $_POST['tgl_pelaksanaan'];
    $jam_pelaksanaan = $_POST['jam_pelaksanaan'];
    $durasi_pelatihan = $_POST['durasi_pelatihan'];
    $lok_pelatihan = $_POST['lok_pelatihan'];
    $pem_pelatihan = $_POST['pem_pelatihan'];
    $status_pelatihan = $_POST['status_pelatihan'];

    $query = "INSERT INTO pelatihann (nama_pelatihan, tgl_pelaksanaan, jam_pelaksanaan, durasi_pelatihan, lok_pelatihan, pem_pelatihan, status_pelatihan) 
              VALUES ('$nama_pelatihan', '$tgl_pelaksanaan', '$jam_pelaksanaan', '$durasi_pelatihan', '$lok_pelatihan', '$pem_pelatihan', '$status_pelatihan')";

    if (mysqli_query($conn, $query)) {
        echo "Pelatihan berhasil ditambahkan!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Pelatihan</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
            <input type="text" class="form-control" id="nama_pelatihan" name="nama_pelatihan" required>
        </div>
        <div class="mb-3">
            <label for="tgl_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
            <input type="date" class="form-control" id="tgl_pelaksanaan" name="tgl_pelaksanaan" required>
        </div>
        <div class="mb-3">
            <label for="jam_pelaksanaan" class="form-label">Jam Pelaksanaan</label>
            <input type="time" class="form-control" id="jam_pelaksanaan" name="jam_pelaksanaan" required>
        </div>
        <div class="mb-3">
            <label for="durasi_pelatihan" class="form-label">Durasi Pelatihan</label>
            <input type="text" class="form-control" id="durasi_pelatihan" name="durasi_pelatihan" required>
        </div>
        <div class="mb-3">
            <label for="lok_pelatihan" class="form-label">Lokasi Pelatihan</label>
            <input type="text" class="form-control" id="lok_pelatihan" name="lok_pelatihan" required>
        </div>
        <div class="mb-3">
            <label for="pem_pelatihan" class="form-label">Pemateri Pelatihan</label>
            <input type="text" class="form-control" id="pem_pelatihan" name="pem_pelatihan" required>
        </div>
        <div class="mb-3">
            <label for="status_pelatihan" class="form-label">Status Pelatihan</label>
            <select class="form-select" id="status_pelatihan" name="status_pelatihan" required>
                <option value="terlaksana">Terlaksana</option>
                <option value="belum terlaksana">Belum Terlaksana</option>
                <option value="tidak terlaksana">Tidak Terlaksana</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Pelatihan</button>
    </form>
</div>
</body>
</html>
