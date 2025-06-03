<?php
require_once '../functions/pemesanan.php';

if (isset($_GET['hapus'])) {
    deletePemesanan($_GET['hapus']);
    header("Location: list_pemesanan.php");
    exit;
}

$pemesanans = getAllPemesanan();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pemesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body class="container pt-4">
    <h2>Daftar Pemesanan</h2>
    <a href="add_pemesanan.php" class="btn btn-primary mb-3">+ Tambah Pemesanan</a>
    <a href="../export/export_pdf.php" class="btn btn-danger mb-3">Export PDF</a>
    <a href="../export/export_excel.php" class="btn btn-success mb-3">Export Excel</a>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nama Pelanggan</th>
                <th>File Upload</th>
                <th>Jenis Pemesanan</th>
                <th>Jenis Warna</th>
                <th>Ukuran Kertas</th>
                <th>Jumlah Copy</th>
                <th>Catatan</th>
                <th>Tanggal Pemesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($pemesanans) == 0): ?>
            <tr><td colspan="10" class="text-center">Data kosong</td></tr>
        <?php else: foreach ($pemesanans as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                <td>
                    <?php if ($p['file_upload']): ?>
                        <a href="../uploads/<?= htmlspecialchars($p['file_upload']) ?>" target="_blank"><?= htmlspecialchars($p['file_upload']) ?></a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['jenis_pemesanan']) ?></td>
                <td><?= htmlspecialchars($p['jenis_warna']) ?></td>
                <td><?= htmlspecialchars($p['ukuran_kertas']) ?></td>
                <td><?= (int)$p['jumlah_copy'] ?></td>
                <td><?= htmlspecialchars($p['catatan']) ?></td>
                <td><?= htmlspecialchars($p['tanggal_pemesanan']) ?></td>
                <td>
                    <a href="edit_pemesanan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="list_pemesanan.php?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</body>
</html>