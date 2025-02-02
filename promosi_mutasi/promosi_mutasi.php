<?php
// Start session di awal file
session_start();

// Perbaiki path koneksi - karena file koneksi.php ada di folder config yang sejajar dengan promosi_mutasi
require_once '../config/koneksi.php';

// Cek koneksi
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Simpan pesan dalam session
function setMessage($message, $type) {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    
    // Tambahkan debug info jika type adalah danger
    if ($type === 'danger') {
        error_log("Error in promosi_mutasi.php: " . $message);
    }
}

// Ambil pesan dari session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// Hapus pesan dari session setelah diambil
unset($_SESSION['message']);
unset($_SESSION['message_type']);

// Proses hapus data
if (isset($_GET['delete']) && isset($_GET['tab'])) {
    $id = $mysqli->real_escape_string($_GET['delete']);
    $tab = $_GET['tab'];
    
    if ($tab == 'mutasi') {
        // Cek apakah data ada
        $check_query = "SELECT id_mutasi FROM mutasi WHERE id_mutasi = ?";
        $check_stmt = $mysqli->prepare($check_query);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Data ditemukan, lakukan penghapusan
            $delete_query = "DELETE FROM mutasi WHERE id_mutasi = ?";
            if ($delete_stmt = $mysqli->prepare($delete_query)) {
                $delete_stmt->bind_param("i", $id);
                
                if ($delete_stmt->execute()) {
                    setMessage("Data mutasi berhasil dihapus!", "success");
                } else {
                    setMessage("Error: Gagal menghapus data mutasi.", "danger");
                }
                $delete_stmt->close();
            } else {
                setMessage("Error: " . $mysqli->error, "danger");
            }
        } else {
            setMessage("Error: Data mutasi tidak ditemukan.", "warning");
        }
        $check_stmt->close();
    } elseif ($tab == 'promosi') {
        // Cek apakah data ada
        $check_query = "SELECT id_peg FROM promosi WHERE id_peg = ?";
        $check_stmt = $mysqli->prepare($check_query);
        $check_stmt->bind_param("s", $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Data ditemukan, lakukan penghapusan
            $delete_query = "DELETE FROM promosi WHERE id_peg = ?";
            if ($delete_stmt = $mysqli->prepare($delete_query)) {
                $delete_stmt->bind_param("s", $id);
                
                if ($delete_stmt->execute()) {
                    setMessage("Data promosi berhasil dihapus!", "success");
                } else {
                    setMessage("Error: Gagal menghapus data promosi.", "danger");
                }
                $delete_stmt->close();
            } else {
                setMessage("Error: " . $mysqli->error, "danger");
            }
        } else {
            setMessage("Error: Data promosi tidak ditemukan.", "warning");
        }
        $check_stmt->close();
    }
    
    // Redirect kembali ke halaman dengan tab yang sama
    header("Location: promosi_mutasi.php?tab=" . $tab);
    exit();
}

// Inisialisasi variabel
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'promosi';
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 2;
$start = ($page - 1) * $per_page;

// Query sesuai tab aktif dengan pencarian
if ($active_tab == 'promosi') {
    $count_query = "SELECT COUNT(*) as total FROM promosi";
    $base_query = "SELECT * FROM promosi";
    if (!empty($search)) {
        $where_clause = " WHERE id_peg LIKE '%$search%' 
                         OR jbt_lama LIKE '%$search%' 
                         OR jbt_baru LIKE '%$search%' 
                         OR alasan_promosi LIKE '%$search%'";
        $count_query .= $where_clause;
        $base_query .= $where_clause;
    }
} else {
    $count_query = "SELECT COUNT(*) as total FROM mutasi";
    $base_query = "SELECT * FROM mutasi";
    if (!empty($search)) {
        $where_clause = " WHERE id_pegawai LIKE '%$search%' 
                         OR jbt_lama LIKE '%$search%' 
                         OR jbt_baru LIKE '%$search%' 
                         OR dpm_lama LIKE '%$search%' 
                         OR dpm_baru LIKE '%$search%' 
                         OR alasan_mutasi LIKE '%$search%' 
                         OR status_mutasi LIKE '%$search%'";
        $count_query .= $where_clause;
        $base_query .= $where_clause;
    }
}

// Hitung total records dan halaman
$total_result = $mysqli->query($count_query);
$total_records = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// Query final dengan LIMIT untuk pagination
$query = $base_query . " LIMIT $start, $per_page";
$result = $mysqli->query($query);

// Proses tambah data mutasi
if (isset($_POST['add_mutasi'])) {
    try {
        // Debug: Print POST data
        // echo "<pre>"; print_r($_POST); echo "</pre>"; die();
        
        $id_pegawai = isset($_POST['id_pegawai']) ? $mysqli->real_escape_string($_POST['id_pegawai']) : '';
        $jbt_lama = isset($_POST['jbt_lama']) ? $mysqli->real_escape_string($_POST['jbt_lama']) : '';
        $jbt_baru = isset($_POST['jbt_baru']) ? $mysqli->real_escape_string($_POST['jbt_baru']) : '';
        $dpm_lama = isset($_POST['dpm_lama']) ? $mysqli->real_escape_string($_POST['dpm_lama']) : '';
        $dpm_baru = isset($_POST['dpm_baru']) ? $mysqli->real_escape_string($_POST['dpm_baru']) : '';
        $tgl_mutasi = isset($_POST['tgl_mutasi']) ? $mysqli->real_escape_string($_POST['tgl_mutasi']) : '';
        $alasan_mutasi = isset($_POST['alasan_mutasi']) ? $mysqli->real_escape_string($_POST['alasan_mutasi']) : '';
        $status_mutasi = isset($_POST['status_mutasi']) ? $mysqli->real_escape_string($_POST['status_mutasi']) : 'Pending';

        // Validasi data
        if (empty($id_pegawai) || empty($jbt_lama) || empty($jbt_baru) || 
            empty($dpm_lama) || empty($dpm_baru) || empty($tgl_mutasi) || 
            empty($alasan_mutasi)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Query untuk menambah data mutasi
        $query = "INSERT INTO mutasi (id_pegawai, jbt_lama, jbt_baru, dpm_lama, dpm_baru, 
                                    tgl_mutasi, alasan_mutasi, status_mutasi) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ssssssss", 
                $id_pegawai,
                $jbt_lama,
                $jbt_baru,
                $dpm_lama,
                $dpm_baru,
                $tgl_mutasi,
                $alasan_mutasi,
                $status_mutasi
            );
            
            // Debug: Print query dan parameter
            // $debug_query = str_replace('?', "'%s'", $query);
            // $debug_query = vsprintf($debug_query, [
            //     $id_pegawai, $jbt_lama, $jbt_baru, $dpm_lama, $dpm_baru,
            //     $tgl_mutasi, $alasan_mutasi, $status_mutasi
            // ]);
            // echo $debug_query; die();
            
            if ($stmt->execute()) {
                setMessage("Data mutasi berhasil ditambahkan!", "success");
                header("Location: promosi_mutasi.php?tab=mutasi");
                exit();
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Error preparing query: " . $mysqli->error);
        }
    } catch (Exception $e) {
        setMessage("Error: " . $e->getMessage(), "danger");
        header("Location: promosi_mutasi.php?tab=mutasi");
        exit();
    }
}

// Proses tambah data promosi
if (isset($_POST['add_promosi'])) {
    $id_peg = $mysqli->real_escape_string($_POST['id_peg']);
    $jbt_lama = $mysqli->real_escape_string($_POST['jbt_lama']);
    $jbt_baru = $mysqli->real_escape_string($_POST['jbt_baru']);
    $tanggal_promosi = $mysqli->real_escape_string($_POST['tanggal_promosi']);
    $alasan_promosi = $mysqli->real_escape_string($_POST['alasan_promosi']);

    // Cek apakah ID Pegawai sudah ada
    $check_query = "SELECT COUNT(*) as count FROM promosi WHERE id_peg = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("s", $id_peg);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $exists = $check_result->fetch_assoc()['count'] > 0;
    $check_stmt->close();

    if ($exists) {
        // Jika ID sudah ada, tampilkan pesan error
        setMessage("Error: ID Pegawai sudah ada dalam data promosi.", "danger");
    } else {
        // Jika ID belum ada, tambahkan data baru
        $insert_query = "INSERT INTO promosi (id_peg, jbt_lama, jbt_baru, tanggal_promosi, alasan_promosi) 
                        VALUES (?, ?, ?, ?, ?)";
        
        if ($insert_stmt = $mysqli->prepare($insert_query)) {
            $insert_stmt->bind_param("sssss", 
                $id_peg, 
                $jbt_lama, 
                $jbt_baru, 
                $tanggal_promosi, 
                $alasan_promosi
            );
            
            if ($insert_stmt->execute()) {
                setMessage("Data promosi berhasil ditambahkan!", "success");
            } else {
                setMessage("Error: " . $insert_stmt->error, "danger");
            }
            $insert_stmt->close();
        } else {
            setMessage("Error: " . $mysqli->error, "danger");
        }
    }
    
    header("Location: promosi_mutasi.php?tab=promosi");
    exit();
}

// Proses edit data mutasi
if (isset($_POST['edit_mutasi'])) {
    $id_mutasi = $mysqli->real_escape_string($_POST['id_mutasi']);
    $id_pegawai = $mysqli->real_escape_string($_POST['id_pegawai']);
    $jbt_lama = $mysqli->real_escape_string($_POST['jbt_lama']);
    $jbt_baru = $mysqli->real_escape_string($_POST['jbt_baru']);
    $dpm_lama = $mysqli->real_escape_string($_POST['dpm_lama']);
    $dpm_baru = $mysqli->real_escape_string($_POST['dpm_baru']);
    $tgl_mutasi = $mysqli->real_escape_string($_POST['tgl_mutasi']);
    $alasan_mutasi = $mysqli->real_escape_string($_POST['alasan_mutasi']);
    $status_mutasi = $mysqli->real_escape_string($_POST['status_mutasi']);

    $query = "UPDATE mutasi SET 
              id_pegawai = ?,
              jbt_lama = ?,
              jbt_baru = ?,
              dpm_lama = ?,
              dpm_baru = ?,
              tgl_mutasi = ?,
              alasan_mutasi = ?,
              status_mutasi = ?
              WHERE id_mutasi = ?";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssssssssi", 
            $id_pegawai,
            $jbt_lama,
            $jbt_baru,
            $dpm_lama,
            $dpm_baru,
            $tgl_mutasi,
            $alasan_mutasi,
            $status_mutasi,
            $id_mutasi
        );
        
        if ($stmt->execute()) {
            setMessage("Data mutasi berhasil diperbarui!", "success");
        } else {
            setMessage("Error: " . $stmt->error, "danger");
        }
        $stmt->close();
    } else {
        setMessage("Error: " . $mysqli->error, "danger");
    }
    
    header("Location: promosi_mutasi.php?tab=mutasi");
    exit();
}

// Proses edit data promosi
if (isset($_POST['edit_promosi'])) {
    $id_peg = $mysqli->real_escape_string($_POST['id_peg']);
    $jbt_lama = $mysqli->real_escape_string($_POST['jbt_lama']);
    $jbt_baru = $mysqli->real_escape_string($_POST['jbt_baru']);
    $tanggal_promosi = $mysqli->real_escape_string($_POST['tanggal_promosi']);
    $alasan_promosi = $mysqli->real_escape_string($_POST['alasan_promosi']);

    $query = "UPDATE promosi SET 
              jbt_lama = ?,
              jbt_baru = ?,
              tanggal_promosi = ?,
              alasan_promosi = ?
              WHERE id_peg = ?";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sssss", 
            $jbt_lama,
            $jbt_baru,
            $tanggal_promosi,
            $alasan_promosi,
            $id_peg
        );
        
        if ($stmt->execute()) {
            setMessage("Data promosi berhasil diperbarui!", "success");
        } else {
            setMessage("Error: " . $stmt->error, "danger");
        }
        $stmt->close();
    } else {
        setMessage("Error: " . $mysqli->error, "danger");
    }
    
    header("Location: promosi_mutasi.php?tab=promosi");
    exit();
}

// Sekarang baru include header dan sidebar
include '../template/header.php';
include '../template/sidebar.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Promosi & Mutasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
            z-index: 1000;
        }

        /* Main content styles */
        .main-content {
            margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
            margin-top: 80px; /* Sesuaikan dengan tinggi header */
            padding: 20px;
        }

        .container-fluid {
            padding-top: 20px;
            width: 100%;
        }

        /* Table responsive modifications */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Table styles */
        .table {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 0;
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }

        /* Additional utility classes */
        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        /* Search form styles */
        .search-form {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Pagination styles */
        .pagination {
            margin: 20px 0;
        }

        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link:hover {
            z-index: 2;
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination .page-item .page-link {
            padding: 8px 16px;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination .page-link:hover {
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-link:focus {
            z-index: 3;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }

        /* Responsive Pagination */
        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 6px 12px;
                font-size: 14px;
            }
        }

        h2.mb-4 {
            margin-top: 20px;
            color: #333;
            font-weight: 500;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.5em 0.8em;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .main-content {
                margin-top: 60px;
            }
            
            h2.mb-4 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="sidebar">
            <?php include '../template/sidebar.php'; ?>
        </div>
        
        <div class="main-content">
            <h2 class="mb-4">Manajemen Promosi & Mutasi</h2>

            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab == 'promosi' ? 'active' : '' ?>" 
                       href="?tab=promosi">Data Promosi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab == 'mutasi' ? 'active' : '' ?>" 
                       href="?tab=mutasi">Data Mutasi</a>
                </li>
            </ul>

            <!-- Search and Add Button Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="hidden" name="tab" value="<?= $active_tab ?>">
                        <input type="text" name="search" class="form-control me-2" 
                               placeholder="Cari data..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="?tab=<?= $active_tab ?>" class="btn btn-secondary ms-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                            data-bs-target="#<?= $active_tab ?>Modal">
                        Tambah <?= ucfirst($active_tab) ?>
                    </button>
                </div>
            </div>

            <!-- Notifikasi Hasil Pencarian -->
            <?php if (!empty($search)): ?>
                <?php if ($total_records > 0): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Ditemukan <?= $total_records ?> data untuk pencarian "<?= htmlspecialchars($search) ?>"
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Tidak ditemukan data untuk pencarian "<?= htmlspecialchars($search) ?>"
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tabel Data -->
            <div class="card">
                <div class="card-body">
                    <?php if ($total_records > 0): ?>
                        <?php include "table_{$active_tab}.php"; ?>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <div class="text-center mt-4">
                            <ul class="pagination justify-content-center">
                                <!-- First Page -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?tab=<?= $active_tab ?>&page=1&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-double-left"></i>
                                    </a>
                                </li>
                                
                                <!-- Previous Page -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?tab=<?= $active_tab ?>&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                <!-- Page Numbers -->
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);

                                for ($i = $start_page; $i <= $end_page; $i++): 
                                ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?tab=<?= $active_tab ?>&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?tab=<?= $active_tab ?>&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>

                                <!-- Last Page -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?tab=<?= $active_tab ?>&page=<?= $total_pages ?>&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-double-right"></i>
                                    </a>
                                </li>
                            </ul>

                            <!-- Informasi halaman -->
                            <div class="text-muted mt-2">
                                Halaman <?= $page ?> dari <?= $total_pages ?> 
                                (Total <?= $total_records ?> data)
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <?php if (!empty($search)): ?>
                                Tidak ditemukan data untuk pencarian "<?= htmlspecialchars($search) ?>"
                            <?php else: ?>
                                Belum ada data <?= $active_tab ?> yang tersedia.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modal Forms -->
    <?php 
    include 'modal_promosi.php';
    include 'modal_mutasi.php';
    ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Auto hide alert
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);

        // Konfirmasi hapus
        $('.btn-hapus').click(function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Fungsi untuk edit mutasi
    function editMutasi(data) {
        // Set nilai ke form edit
        document.getElementById('edit_id_mutasi').value = data.id_mutasi;
        document.getElementById('edit_id_pegawai').value = data.id_pegawai;
        document.getElementById('edit_jbt_lama').value = data.jbt_lama;
        document.getElementById('edit_jbt_baru').value = data.jbt_baru;
        document.getElementById('edit_dpm_lama').value = data.dpm_lama;
        document.getElementById('edit_dpm_baru').value = data.dpm_baru;
        document.getElementById('edit_tgl_mutasi').value = data.tgl_mutasi;
        document.getElementById('edit_alasan_mutasi').value = data.alasan_mutasi;
        
        // Set nilai status mutasi
        const statusSelect = document.getElementById('edit_status_mutasi');
        statusSelect.value = data.status_mutasi;

        // Tampilkan modal edit
        var editModal = new bootstrap.Modal(document.getElementById('editMutasiModal'));
        editModal.show();
    }

    function confirmDelete(id, type) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `promosi_mutasi.php?tab=${type}&delete=${id}`;
            }
        });
    }

    // Fungsi untuk edit promosi
    function editPromosi(data) {
        // Set nilai ke form edit
        document.getElementById('edit_id_peg').value = data.id_peg;
        document.getElementById('edit_jbt_lama').value = data.jbt_lama;
        document.getElementById('edit_jbt_baru').value = data.jbt_baru;
        document.getElementById('edit_tanggal_promosi').value = data.tanggal_promosi;
        document.getElementById('edit_alasan_promosi').value = data.alasan_promosi;

        // Tampilkan modal edit
        var editModal = new bootstrap.Modal(document.getElementById('editPromosiModal'));
        editModal.show();
    }
    </script>

    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>