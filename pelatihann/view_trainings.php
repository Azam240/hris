<?php
include 'db_connection.php'; // Sesuaikan dengan file koneksi DB Anda

$query = "SELECT * FROM pelatihann";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelatihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Daftar Pelatihan</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID Pelatihan</th>
                <th scope="col">Nama Pelatihan</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Jam</th>
                <th scope="col">Durasi</th>
                <th scope="col">Lokasi</th>
                <th scope="col">Pemateri</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id_pelatihan'] ?></td>
                <td><?= $row['nama_pelatihan'] ?></td>
                <td><?= $row['tgl_pelaksanaan'] ?></td>
                <td><?= $row['jam_pelaksanaan'] ?></td>
                <td><?= $row['durasi_pelatihan'] ?></td>
                <td><?= $row['lok_pelatihan'] ?></td>
                <td><?= $row['pem_pelatihan'] ?></td>
                <td><?= $row['status_pelatihan'] ?></td>
                <td>
                    <a href="edit_training.php?id=<?= $row['id_pelatihan'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_training.php?id=<?= $row['id_pelatihan'] ?>" class="btn btn-danger">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php mysqli_close($conn); ?>
