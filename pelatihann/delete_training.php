<?php
include 'db_connection.php'; // Sesuaikan dengan file koneksi DB Anda

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM pelatihann WHERE id_pelatihan = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "Pelatihan berhasil dihapus!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
