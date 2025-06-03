<?php
include_once __DIR__.'/../config/db.php';

function getAllPemesanan() {
    global $conn;
    $sql = "SELECT * FROM pemesanan ORDER BY tanggal_pemesanan DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function getPemesananById($id) {
    global $conn;
    $sql = "SELECT * FROM pemesanan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function addPemesanan($data) {
    global $conn;
    $sql = "INSERT INTO pemesanan (nama_pelanggan, file_upload, jenis_pemesanan, jenis_warna, ukuran_kertas, jumlah_copy, catatan, tanggal_pemesanan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss",
        $data['nama_pelanggan'],
        $data['file_upload'],
        $data['jenis_pemesanan'],
        $data['jenis_warna'],
        $data['ukuran_kertas'],
        $data['jumlah_copy'],
        $data['catatan'],
        $data['tanggal_pemesanan']
    );
    return mysqli_stmt_execute($stmt);
}

function updatePemesanan($id, $data) {
    global $conn;
    $sql = "UPDATE pemesanan SET nama_pelanggan=?, file_upload=?, jenis_pemesanan=?, jenis_warna=?, ukuran_kertas=?, jumlah_copy=?, catatan=?, tanggal_pemesanan=?
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi",
        $data['nama_pelanggan'],
        $data['file_upload'],
        $data['jenis_pemesanan'],
        $data['jenis_warna'],
        $data['ukuran_kertas'],
        $data['jumlah_copy'],
        $data['catatan'],
        $data['tanggal_pemesanan'],
        $id
    );
    return mysqli_stmt_execute($stmt);
}

function deletePemesanan($id) {
    global $conn;
    $sql = "DELETE FROM pemesanan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>