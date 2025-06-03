<?php
require_once 'fpdf.php';
require_once '../functions/pemesanan.php';

// Subclass FPDF dengan fungsi MultiCellRow untuk wrapping kolom dan penyesuaian tinggi baris
class PDF extends FPDF {
    // Fungsi untuk menghitung jumlah baris yang diperlukan oleh MultiCell
    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb)
        {
            $c = $s[$i];
            if($c=="\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l > $wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}

$pemesanans = getAllPemesanan();

$pdf = new PDF('L', 'mm', 'A4'); // Tetap format landscape
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0,12,'Daftar Pemesanan',0,1,'C');
$pdf->SetFont('Arial','B',10);

// Header tabel, atur lebar kolom
$w = [10, 40, 40, 28, 25, 25, 15, 60, 30];
$pdf->Cell($w[0],8,'#',1);
$pdf->Cell($w[1],8,'Nama Pelanggan',1);
$pdf->Cell($w[2],8,'File Upload',1);
$pdf->Cell($w[3],8,'Jenis',1);
$pdf->Cell($w[4],8,'Warna',1);
$pdf->Cell($w[5],8,'Ukuran',1);
$pdf->Cell($w[6],8,'Copy',1);
$pdf->Cell($w[7],8,'Catatan',1);
$pdf->Cell($w[8],8,'Tgl Pesan',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
foreach ($pemesanans as $i => $p) {
    // Data
    $data = [
        $i + 1,
        $p['nama_pelanggan'],
        $p['file_upload'],
        $p['jenis_pemesanan'],
        $p['jenis_warna'],
        $p['ukuran_kertas'],
        $p['jumlah_copy'],
        $p['catatan'],
        $p['tanggal_pemesanan']
    ];

    // Hitung jumlah baris maksimum yang dibutuhkan pada baris ini
    $maxLines = 1;
    foreach ([1,2,3,4,7] as $idx) { // hanya kolom yang memungkinkan wrap
        $lines = $pdf->NbLines($w[$idx], $data[$idx]);
        if ($lines > $maxLines) $maxLines = $lines;
    }
    $rowHeight = 8 * $maxLines;

    // Simpan posisi awal
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Cetak setiap kolom
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($w[0], $rowHeight/$maxLines, $data[0], 1, 'C');
    $pdf->SetXY($x += $w[0], $y);
    $pdf->MultiCell($w[1], $rowHeight/$pdf->NbLines($w[1], $data[1]), $data[1], 1);
    $pdf->SetXY($x += $w[1], $y);
    $pdf->MultiCell($w[2], $rowHeight/$pdf->NbLines($w[2], $data[2]), $data[2], 1);
    $pdf->SetXY($x += $w[2], $y);
    $pdf->MultiCell($w[3], $rowHeight/$pdf->NbLines($w[3], $data[3]), $data[3], 1);
    $pdf->SetXY($x += $w[3], $y);
    $pdf->MultiCell($w[4], $rowHeight/$pdf->NbLines($w[4], $data[4]), $data[4], 1);
    $pdf->SetXY($x += $w[4], $y);
    $pdf->MultiCell($w[5], $rowHeight, $data[5], 1, 'C');
    $pdf->SetXY($x += $w[5], $y);
    $pdf->MultiCell($w[6], $rowHeight, $data[6], 1, 'C');
    $pdf->SetXY($x += $w[6], $y);
    $pdf->MultiCell($w[7], $rowHeight/$pdf->NbLines($w[7], $data[7]), $data[7], 1);
    $pdf->SetXY($x += $w[7], $y);
    $pdf->MultiCell($w[8], $rowHeight, $data[8], 1, 'C');

    // Pindah ke bawah setelah selesai satu baris
    $pdf->SetY($y + $rowHeight);
}
$pdf->Output('D', 'daftar_pemesanan.pdf');
exit;
?>