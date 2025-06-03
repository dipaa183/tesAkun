<?php
require_once '../functions/pemesanan.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daftar_pemesanan.xls");

$pemesanans = getAllPemesanan();

echo "<table border='1'>";
echo "<tr>
    <th>#</th>
    <th>Nama Pelanggan</th>
    <th>File Upload</th>
    <th>Jenis Pemesanan</th>
    <th>Jenis Warna</th>
    <th>Ukuran Kertas</th>
    <th>Jumlah Copy</th>
    <th>Catatan</th>
    <th>Tanggal Pemesanan</th>
</tr>";

foreach ($pemesanans as $i => $p) {
    echo "<tr>";
    echo "<td>".($i+1)."</td>";
    echo "<td>".htmlspecialchars($p['nama_pelanggan'])."</td>";
    echo "<td>".htmlspecialchars($p['file_upload'])."</td>";
    echo "<td>".htmlspecialchars($p['jenis_pemesanan'])."</td>";
    echo "<td>".htmlspecialchars($p['jenis_warna'])."</td>";
    echo "<td>".htmlspecialchars($p['ukuran_kertas'])."</td>";
    echo "<td>".(int)$p['jumlah_copy']."</td>";
    echo "<td>".htmlspecialchars($p['catatan'])."</td>";
    echo "<td>".htmlspecialchars($p['tanggal_pemesanan'])."</td>";
    echo "</tr>";
}
echo "</table>";
exit;
?>