<?php
include 'db_connection.php'; // Sesuaikan dengan file koneksi DB Anda

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM pelatihann WHERE id_pelatihan = '$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelatihan = $_POST['nama_pelatihan'];
    $tgl_pelaksanaan = $_POST['tgl_pelaksanaan'];
    $jam_pelaksanaan = $_POST['jam_pelaksanaan'];
    $durasi_pelatihan = $_POST['durasi_pelatihan'];
    $lok_pelatihan = $_POST['lok_pelatihan'];
    $pem_pelatihan = $_POST['pem_pelatihan'];
    $status_pelatihan = $_POST['status_pelatihan'];

    $update_query = "UPDATE pelatihann SET 
                     nama_pelatihan = '$nama_pelatihan',
                     tgl_pelaksanaan = '$tgl_pelaksanaan',
                     jam_pelaksanaan = '$jam_pelaksanaan',
                     durasi_pelatihan = '$durasi_pelatihan',
                     lok_pelatihan = '$lok_pelatihan',
                     pem_pelatihan = '$pem_pelatihan',
                     status_pelatihan = '$status_pelatihan' 
                     WHERE id_pelatihan = '$id'";

    if (mysqli_query($conn, $update_query)) {
        echo "Pelatihan berhasil diperbarui!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- Form untuk edit pelatihan (sama seperti add_training.php, hanya disesuaikan dengan data yang sudah ada) -->
