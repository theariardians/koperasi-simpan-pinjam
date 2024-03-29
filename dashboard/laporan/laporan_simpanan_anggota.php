<?php
include'koneksi.php';
include"fpdf.php";
require('makefont/makefont.php');
$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(1,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->SetX(1.6);            
$pdf->MultiCell(15.5,0.6,'Koperasi Bhakti Sejahtera',0,'L');
$pdf->SetX(1.6);
$pdf->SetFont('Times','i',10);
$pdf->MultiCell(15.5,0.6,'Jalan KH. Ahmad Dahlan, Desa Bhakti Sejahtera',0,'L'); 
$periode = $_POST['periode'];
$tahun = $_POST['tahun'];
$pdf->SetX(1.6);
$pdf->SetFont('Times','i',10);
$pdf->MultiCell(22.5,0.6,"Laporan Simpanan Anggota Periode: ".$periode."/ Tahun: ".$tahun,0,'L'); 
$pdf->Line(1,3.1,28.5,3.1);
$pdf->SetLineWidth(0.1);      
$pdf->Line(1,3.2,28.5,3.2);   
$pdf->SetLineWidth(0);
$pdf->SetFont('Times','i',8);
$periode = $_POST['periode'];
$tahun = $_POST['tahun'];
$pdf->ln(1);
$pdf->Cell(3.5,0.7,"Di cetak pada : ".date("D-d/m/Y"),0,0,'C');
$pdf->ln(1);
$pdf->SetFont('Times','B',8);
$pdf->Cell(1, 0.6, 'No', 1, 0, 'C');
$pdf->Cell(2, 0.6, 'ID Simpanan', 1, 0, 'L');
$pdf->Cell(3, 0.6, 'Id Anggota', 1, 0, 'L');
$pdf->Cell(4, 0.6, 'Nama Anggota', 1, 0, 'L');
$pdf->Cell(2, 0.6, 'P/T', 1, 0, 'L');
$pdf->Cell(4, 0.6, 'Nama Penyetor', 1, 0, 'L');
$pdf->Cell(3, 0.6, 'Tgl. Setor', 1, 0, 'L');
$pdf->Cell(3, 0.6, 'SW', 1, 1, 'L');

$no=1;
$sql="SELECT * FROM tbl_simpanan_a WHERE periode='$periode' AND tahun='$tahun'";
$tampil=mysqli_query($connect, $sql);
while($lihat=mysqli_fetch_array($tampil)){
    $pdf->SetFont('Times','',10);
    $pdf->Cell(1, 0.6, $no , 1, 0, 'C');
    $pdf->Cell(2, 0.6, $lihat['id_simpanan'],1, 0, 'L');
    $pdf->Cell(3, 0.6, $lihat['id_anggota'],1, 0, 'L');
    $query1="SELECT * FROM tbl_anggota WHERE id_anggota='".$lihat['id_anggota']."'";
    $sql1=mysqli_query($connect, $query1);
    while ($data1=mysqli_fetch_array($sql1)) {
        $pdf->Cell(4, 0.6, $data1['nama_anggota'],1, 0, 'L');
    }
    $pdf->Cell(2, 0.6, $lihat['periode']."/".$lihat['tahun'],1, 0, 'L');
    $pdf->Cell(4, 0.6, $lihat['nama_penyetor'],1, 0, 'L');
    $pdf->Cell(3, 0.6, $lihat['tgl_setor'],1, 0, 'L');
    $pdf->Cell(3, 0.6,"Rp. ".number_format($lihat['simpanan_wajib'], 2, ".", "."),1, 1, 'L');
    $no++;
}
$pdf->ln(1);
$pdf->SetFont('Times','i',10);
$pdf->Cell(22.5,0.7,"Hasil Rekapitulasi Simpanan Anggota Periode: ".$periode." Tahun: ".$tahun,0,0,'L');
$pdf->ln(1);
$no=1;
$pdf->SetFont('Times','B',8);
$pdf->Cell(1, 0.6, 'No', 1, 0, 'C');
$pdf->Cell(3, 0.6, 'ID Anggota', 1, 0, 'L');
$pdf->Cell(10, 0.6, 'Nama Anggota', 1, 0, 'L');
$pdf->Cell(8, 0.6, 'Periode/Tahun', 1, 0, 'L');
$pdf->Cell(5.5, 0.6, 'Total Simpanan', 1, 1, 'L');

$sql_x="SELECT * FROM tbl_rekap_simpanan_a WHERE periode='$periode' AND tahun = '$tahun'";
$tampil_x=mysqli_query($connect, $sql_x);
while($lihat_x=mysqli_fetch_array($tampil_x)){
    $pdf->SetFont('Times','',10);
    $pdf->Cell(1, 0.6, $no , 1, 0, 'C');
    $pdf->Cell(3, 0.6, $lihat_x['id_anggota'],1, 0, 'L');
    $query_a="SELECT * FROM tbl_anggota WHERE id_anggota='".$lihat_x['id_anggota']."'";
    $sql_a=mysqli_query($connect, $query_a);
    while ($data_a=mysqli_fetch_array($sql_a)) {
        $pdf->Cell(10, 0.6, $data_a['nama_anggota'],1, 0, 'L');
    }
    $pdf->Cell(8, 0.6, $lihat_x['periode']."/".$lihat_x['tahun'],1, 0, 'L');
    $pdf->Cell(5.5, 0.6,"Rp. ".number_format($lihat_x['ttl_simpanan'], 2, ".", "."),1, 1, 'L');
    $no++;
}

$order="SELECT * FROM tbl_rekap_simpanan_a WHERE periode='$periode' AND tahun='$tahun'";
$query_order=mysqli_query($connect, $order);
$data_order=array();
while(($row_order=mysqli_fetch_array($query_order)) !=null){
$data_order[]=$row_order;
}
$count=count($data_order);
$pdf->SetFont('Times','B',10);
$result="SELECT SUM(ttl_simpanan) AS ttl_simpanan FROM  tbl_rekap_simpanan_a
WHERE periode='$periode' AND tahun='$tahun'";
 $sd=mysqli_query($connect, $result);
while($hasil=mysqli_fetch_object($sd))
{
    $pdf->Cell(22, 0.6,"Total Simpanan Anggota",1, 0, '');
    $pdf->Cell(5.5, 0.6, "Rp. ".number_format($hasil->ttl_simpanan, 2, ".", "."),1, 1, '');
}
$pdf->SetFont('Times','B',10);
$pdf->Cell(22, 0.6,"Jumlah Anggota",1, 0, '');
$pdf->Cell(5.5, 0.6, $count ,1, 1, 'C');
$pdf->Output();
?>