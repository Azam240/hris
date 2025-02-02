<?php
include 'connection.php';
session_start();

// Cek CSRF Token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "<script>alert('Invalid CSRF token.'); window.location.href='index.php';</script>";
        exit();
    }

    // Reset token setelah digunakan
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Ambil dan sanitasi input
    $id_pegawai = intval($_POST['id_pegawai']);
    $status_kehadiran = htmlspecialchars(trim($_POST['status_kehadiran']));
    $keterangan = htmlspecialchars(trim($_POST['keterangan']));
    $tanggal_waktu = date('Y-m-d H:i:s');
    $metode_verifikasi = "Manual"; // Karena input manual dari form
    $lokasi_ip = $_SERVER['REMOTE_ADDR'];

    // Validasi input
    if (empty($id_pegawai) || empty($status_kehadiran)) {
        echo "<script>alert('Harap lengkapi semua field yang wajib.'); window.history.back();</script>";
        exit();
    }

    // Mulai transaksi untuk memastikan integritas data
    $conn->begin_transaction();

    try {
        // Insert ke tabel absensi
        $query = "INSERT INTO `absensi` (`ID_Pegawai`, `Tanggal_Waktu`, `Status_Kehadiran`, `Metode_Verifikasi`, `Lokasi_IP`, `Keterangan`) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("isssss", 
            $id_pegawai, 
            $tanggal_waktu, 
            $status_kehadiran, 
            $metode_verifikasi, 
            $lokasi_ip, 
            $keterangan
        );

        if (!$stmt->execute()) {
            throw new Exception("Error pada Insert Absensi: " . $stmt->error);
        }

        // Ambil Jadwal Kehadiran berdasarkan hari
        $hari = date('l', strtotime($tanggal_waktu)); // Nama hari dalam Bahasa Inggris
        $query_jadwal = "SELECT * FROM `jadwal_kehadiran` 
                         WHERE `ID_Pegawai` = ? AND `Hari` = ?";
        $stmt_jadwal = $conn->prepare($query_jadwal);
        if ($stmt_jadwal === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt_jadwal->bind_param("is", $id_pegawai, $hari);
        $stmt_jadwal->execute();
        $result_jadwal = $stmt_jadwal->get_result();

        if ($result_jadwal->num_rows > 0) {
            $jadwal = $result_jadwal->fetch_assoc();
            $jam_masuk = strtotime($jadwal['Jam_Masuk']);
            $jam_absen = strtotime(date('H:i:s', strtotime($tanggal_waktu)));

            if ($jam_absen > $jam_masuk) {
                $jam_masuk_str = date('H:i:s', $jam_masuk);
                $jam_absen_str = date('H:i:s', $jam_absen);
                $tanggal = date('Y-m-d', strtotime($tanggal_waktu));

                // Insert ke tabel keterlambatan dan ketidakhadiran
                $query_terlambat = "INSERT INTO `keterlambatan dan ketidakhadiran` (`Tanggal`, `Jam_Masuk_Terlambat`, `Alasan_Keterlambatan`) 
                                    VALUES (?, ?, ?)";
                $stmt_terlambat = $conn->prepare($query_terlambat);
                if ($stmt_terlambat === false) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $alasan = "Terlambat masuk kerja";
                $stmt_terlambat->bind_param("sss", 
                    $tanggal,
                    $jam_absen_str,
                    $alasan
                );

                if (!$stmt_terlambat->execute()) {
                    throw new Exception("Error pada Insert Keterlambatan: " . $stmt_terlambat->error);
                }

                $stmt_terlambat->close();
            }
        }

        $stmt_jadwal->close();

        // Commit transaksi
        $conn->commit();

        echo "<script>
                alert('Absensi berhasil dicatat!'); 
                window.location.href='index.php';
              </script>";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "<script>
                alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "'); 
                window.location.href='add_absensi.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Jika tidak disubmit via POST, arahkan kembali ke index
    header("Location: index.php");
    exit();
}
?>