<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pegawai</th>
                <th>Jabatan Lama</th>
                <th>Jabatan Baru</th>
                <th>Departemen Lama</th>
                <th>Departemen Baru</th>
                <th>Tanggal Mutasi</th>
                <th>Alasan Mutasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = ($page - 1) * $per_page + 1;
            while ($row = $result->fetch_assoc()): 
                // Debug status
                // var_dump($row['status_mutasi']);
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['id_pegawai']) ?></td>
                <td><?= htmlspecialchars($row['jbt_lama']) ?></td>
                <td><?= htmlspecialchars($row['jbt_baru']) ?></td>
                <td><?= htmlspecialchars($row['dpm_lama']) ?></td>
                <td><?= htmlspecialchars($row['dpm_baru']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_mutasi'])) ?></td>
                <td><?= htmlspecialchars($row['alasan_mutasi']) ?></td>
                <td>
                    <?php 
                    $status = trim($row['status_mutasi']);
                    switch($status) {
                        case 'Setuju':
                            echo '<span class="badge bg-success">Approved</span>';
                            break;
                        case 'Di Tolak':
                            echo '<span class="badge bg-danger">Rejected</span>';
                            break;
                        case 'Sedang Di Proses':
                        default:
                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                            break;
                    }
                    ?>
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" 
                            onclick='editMutasi(<?= json_encode($row) ?>)'>
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <a href="javascript:void(0);" 
                       onclick="confirmDelete(<?= $row['id_mutasi'] ?>, 'mutasi')"
                       class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($result->num_rows === 0): ?>
            <tr>
                <td colspan="10" class="text-center">Tidak ada data yang tersedia</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
// Fungsi untuk memformat tampilan status
function formatStatus(status) {
    let badgeClass = '';
    switch(status.toLowerCase()) {
        case 'Setuju':
            badgeClass = 'bg-warning text-dark';
            break;
        case 'Di Tolak':
            badgeClass = 'bg-success';
            break;
        case 'Sedang Di Proses':
            badgeClass = 'bg-danger';
            break;
        default:
            badgeClass = 'bg-secondary';
    }
    return `<span class="badge ${badgeClass}">${status}</span>`;
}

// Update tampilan status saat edit
function updateStatusDisplay(status) {
    document.getElementById('status_display').innerHTML = formatStatus(status);
}
</script>

<style>
.badge {
    font-size: 0.875rem;
    padding: 0.5em 0.8em;
}

.bg-warning.text-dark {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.bg-success {
    background-color: #28a745 !important;
    color: #fff !important;
}

.bg-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
}

.bg-secondary {
    background-color: #6c757d !important;
    color: #fff !important;
}
</style>