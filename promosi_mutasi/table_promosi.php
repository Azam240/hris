<table class="table table-hover">
    <thead>
        <tr>
            <th>ID Pegawai</th>
            <th>Jabatan Lama</th>
            <th>Jabatan Baru</th>
            <th>Tanggal Promosi</th>
            <th>Alasan Promosi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_peg']) ?></td>
                <td><?= htmlspecialchars($row['jbt_lama']) ?></td>
                <td><?= htmlspecialchars($row['jbt_baru']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_promosi']) ?></td>
                <td><?= htmlspecialchars($row['alasan_promosi']) ?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" 
                            onclick='editPromosi(<?= json_encode($row) ?>)'>
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <a href="javascript:void(0);" 
                       onclick="confirmDelete('<?= $row['id_peg'] ?>', 'promosi')"
                       class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>