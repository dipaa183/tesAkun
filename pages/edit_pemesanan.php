<?php
require_once '../functions/pemesanan.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_pemesanan.php");
    exit;
}
$pemesanan = getPemesananById($id);
if (!$pemesanan) {
    header("Location: list_pemesanan.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload jika user mengganti file
    $filename = $pemesanan['file_upload'];
    $upload_path = '../uploads/';
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $filename = uniqid() . '_' . basename($_FILES["file_upload"]["name"]);
        $target_file = $upload_path . $filename;
        if (!move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
            $error = "Gagal mengunggah file!";
        }
    }
    if ($error === "") {
        $jenis_pemesanan = isset($_POST['jenis_pemesanan']) ? implode(',', $_POST['jenis_pemesanan']) : '';
        $data = [
            'nama_pelanggan' => $_POST['nama_pelanggan'],
            'file_upload' => $filename,
            'jenis_pemesanan' => $jenis_pemesanan,
            'jenis_warna' => $_POST['jenis_warna'],
            'ukuran_kertas' => $_POST['ukuran_kertas'],
            'jumlah_copy' => (int)$_POST['jumlah_copy'],
            'catatan' => $_POST['catatan'],
            'tanggal_pemesanan' => $_POST['tanggal_pemesanan'],
        ];
        if (updatePemesanan($id, $data)) {
            header("Location: list_pemesanan.php");
            exit;
        } else {
            $error = "Gagal mengupdate data!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pemesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body class="container pt-4">
    <h2>Edit Pemesanan</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" required value="<?= htmlspecialchars($pemesanan['nama_pelanggan']) ?>">
        </div>
        <div class="form-group">
            <label>File Upload</label><br>
            <?php if ($pemesanan['file_upload']): ?>
                <a href="../uploads/<?= htmlspecialchars($pemesanan['file_upload']) ?>" target="_blank">File saat ini: <?= htmlspecialchars($pemesanan['file_upload']) ?></a><br>
            <?php endif; ?>
            <input type="file" name="file_upload" class="form-control-file">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
        </div>
        <div class="form-group">
            <label>Jenis Pemesanan</label><br>
            <?php
            $jenis_selected = explode(',', $pemesanan['jenis_pemesanan']);
            ?>
            <input type="checkbox" name="jenis_pemesanan[]" value="Print" <?= in_array('Print', $jenis_selected) ? 'checked' : '' ?>> Print
            <input type="checkbox" name="jenis_pemesanan[]" value="Laminasi" <?= in_array('Laminasi', $jenis_selected) ? 'checked' : '' ?>> Laminasi
            <input type="checkbox" name="jenis_pemesanan[]" value="Jilid" <?= in_array('Jilid', $jenis_selected) ? 'checked' : '' ?>> Jilid
        </div>
        <div class="form-group">
            <label>Jenis Warna</label><br>
            <input type="radio" name="jenis_warna" value="Hitam Putih" <?= $pemesanan['jenis_warna']=='Hitam Putih'?'checked':'' ?>> Hitam Putih
            <input type="radio" name="jenis_warna" value="Berwarna" <?= $pemesanan['jenis_warna']=='Berwarna'?'checked':'' ?>> Berwarna
        </div>
        <div class="form-group">
            <label>Ukuran Kertas</label>
            <select name="ukuran_kertas" class="form-control" required>
                <option value="">-- Pilih Ukuran --</option>
                <option value="A4" <?= $pemesanan['ukuran_kertas']=='A4'?'selected':'' ?>>A4</option>
                <option value="F4" <?= $pemesanan['ukuran_kertas']=='F4'?'selected':'' ?>>F4</option>
                <option value="A3" <?= $pemesanan['ukuran_kertas']=='A3'?'selected':'' ?>>A3</option>
                <option value="Legal" <?= $pemesanan['ukuran_kertas']=='Legal'?'selected':'' ?>>Legal</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Copy</label>
            <input type="number" name="jumlah_copy" class="form-control" required min="1" value="<?= (int)$pemesanan['jumlah_copy'] ?>">
        </div>
        <div class="form-group">
            <label>Catatan Tambahan</label>
            <textarea name="catatan" class="form-control"><?= htmlspecialchars($pemesanan['catatan']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Tanggal Pemesanan</label>
            <input type="date" name="tanggal_pemesanan" class="form-control" required value="<?= htmlspecialchars($pemesanan['tanggal_pemesanan']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="list_pemesanan.php" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>