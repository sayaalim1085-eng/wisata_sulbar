<?php
include "koneksi.php";

$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$paket = $_POST['paket'];
$jumlah_peserta = $_POST['jumlah'];
$tanggal = $_POST['tanggal'];
$transport = $_POST['transport'];
$makan = $_POST['makan'];
$penginapan = $_POST['penginapan'];
$total = $_POST['total'];

$query = mysqli_query($koneksi, "INSERT INTO pesanan
(nama,no_hp,paket,jumlah_peserta,tanggal,transport,makan,penginapan,total)
VALUES
('$nama','$no_hp','$paket','$jumlah_peserta','$tanggal',
 '$transport','$makan','$penginapan','$total')");

if($query){
    echo "DATA BERHASIL DISIMPAN";
}else{
    echo "GAGAL: " . mysqli_error($koneksi);
}
