<?php
include "koneksi.php";

/* =====================
   AJAX PROCESS
===================== */

// GET DATA (EDIT)
if(isset($_GET['get'])){
    $id = $_GET['get'];
    $q = mysqli_query($koneksi,"SELECT * FROM pesanan WHERE id='$id'");
    echo json_encode(mysqli_fetch_assoc($q));
    exit;
}

// SIMPAN / UPDAT
if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $hp = $_POST['no_hp'];
    $paket = $_POST['paket'];
    $jumlah = $_POST['jumlah_peserta'];
    $tgl = $_POST['tanggal'];
    $transport = $_POST['transport'];
    $makan = $_POST['makan'];
    $penginapan = $_POST['penginapan'];
    $total = $_POST['total'];

    if($id==""){
        mysqli_query($koneksi,"INSERT INTO pesanan
        (nama,no_hp,paket,jumlah_peserta,tanggal,transport,makan,penginapan,total)
        VALUES
        ('$nama','$hp','$paket','$jumlah','$tgl','$transport','$makan','$penginapan','$total')");
        echo "Data berhasil disimpan";
    }else{
        mysqli_query($koneksi,"UPDATE pesanan SET
        nama='$nama',
        no_hp='$hp',
        paket='$paket',
        jumlah_peserta='$jumlah',
        tanggal='$tgl',
        transport='$transport',
        makan='$makan',
        penginapan='$penginapan',
        total='$total'
        WHERE id='$id'");
        echo "Data berhasil diedit";
    }
    exit;
}

// HAPUS
if(isset($_GET['hapus'])){
    mysqli_query($koneksi,"DELETE FROM pesanan WHERE id='$_GET[hapus]'");
    echo "Data berhasil dihapus";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tabel Pesanan Wisata</title>
<style>
body{font-family:Arial;background:#f0f4f9;padding:20px}
table{border-collapse:collapse;width:100%;background:#fff}
th,td{border:1px solid #ddd;padding:8px;text-align:center}
th{background:#0a64d3;color:#fff}
button{padding:6px 12px;border:none;border-radius:6px;cursor:pointer}
.tambah{background:#0a64d3;color:#fff;margin-bottom:10px}
.edit{background:#14a44d;color:#fff}
.hapus{background:#d33;color:#fff}
h2 {text-align: center;}

/* POPUP */
.popup{display:none;position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,.5);justify-content:center;align-items:center}
.box{background:#fff;padding:20px;border-radius:10px;width:360px}
.box input,.box select{width:100%;padding:6px;margin:5px 0}
</style>
</head>
<body>

<h2> Tabel Pesanan Wisata</h2>
<button class="tambah" onclick="tambah()">+ Pesan Sekarang</button>

<table>
<tr>
<th>No</th><th>Nama</th><th>No HP</th><th>Paket</th>
<th>Jumlah</th><th>Tgl</th>
<th>T</th><th>M</th><th>P</th>
<th>Total</th><th>Aksi</th>
</tr>

<?php
$no=1;
$q = mysqli_query($koneksi,"SELECT * FROM pesanan ORDER BY id DESC");
while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>$no</td>
<td>$r[nama]</td>
<td>$r[no_hp]</td>
<td>$r[paket]</td>
<td>$r[jumlah_peserta]</td>
<td>$r[tanggal]</td>
<td>$r[transport]</td>
<td>$r[makan]</td>
<td>$r[penginapan]</td>
<td>Rp ".number_format($r['total'])."</td>
<td>
<button class='edit' onclick='edit($r[id])'>Edit</button>
<button class='hapus' onclick='hapus($r[id])'>Hapus</button>
</td>
</tr>";
$no++;
}
?>
</table>

<!-- POPUP FORM -->
<div id="popup" class="popup">
<div class="box">
<h3>Pesan / Edit Paket</h3>
<input type="hidden" id="id">

<input id="nama" placeholder="Nama">
<input id="no_hp" placeholder="No HP">

<select id="paket">
<option value="Bukit Pamboborang">Bukit Pamboborang</option>
<option value="Pantai Dato'">Pantai Dato'</option>
<option value="Taraujung Pamboang">Taraujung Pamboang</option>
<option value="Pulau Karampuang">Pulau Karampuang</option>
<option value="Desa Kunyi">Desa Kunyi</option>
<option value="Air Terjun Sambabo">Air Terjun Sambabo</option>
</select>

<input type="number" id="jumlah" placeholder="Jumlah Peserta" min="1">
<input type="date" id="tanggal">

<label><input type="checkbox" id="transport"> Transport</label><br>
<label><input type="checkbox" id="makan"> Makan</label><br>
<label><input type="checkbox" id="penginapan"> Penginapan</label><br><br>

<b>Total: Rp <span id="total">0</span></b><br><br>

<button class="edit" onclick="simpan()">Simpan</button>
<button class="hapus" onclick="tutup()">Tutup</button>
</div>
</div>

<script>
const popup = document.getElementById('popup');

function hitung(){
let total = 0;
if(paket.value=="Bukit Pamboborang") total+=350000;
if(paket.value=="Pantai Dato'") total+=450000;
if(paket.value=="Taraujung Pamboang") total+=500000;
if(paket.value=="Pulau Karampuang") total+=500000;
if(paket.value=="Desa Kunyi") total+=500000;
if(paket.value=="Air Terjun Sambabo") total+=500000;
total *= jumlah.value||1;
if(transport.checked) total+=1000000;
if(makan.checked) total+=100000;
if(penginapan.checked) total+=200000;
document.getElementById('total').innerText=total.toLocaleString();
}

paket.onchange=jumlah.oninput=transport.onchange=makan.onchange=penginapan.onchange=hitung;

function tambah(){
id.value="";
nama.value="";
no_hp.value="";
jumlah.value=1;
tanggal.value="";
transport.checked=false;
makan.checked=false;
penginapan.checked=false;
hitung();
popup.style.display="flex";
}

function edit(i){
fetch("?get="+i)
.then(r=>r.json())
.then(d=>{
id.value=d.id;
nama.value=d.nama;
no_hp.value=d.no_hp;
paket.value=d.paket;
jumlah.value=d.jumlah_peserta;
tanggal.value=d.tanggal;
transport.checked=d.transport=="Y";
makan.checked=d.makan=="Y";
penginapan.checked=d.penginapan=="Y";
hitung();
popup.style.display="flex";
});
}

function simpan(){
let f=new FormData();
f.append("simpan",1);
f.append("id",id.value);
f.append("nama",nama.value);
f.append("no_hp",no_hp.value);
f.append("paket",paket.value);
f.append("jumlah_peserta",jumlah.value);
f.append("tanggal",tanggal.value);
f.append("transport",transport.checked?"Y":"N");
f.append("makan",makan.checked?"Y":"N");
f.append("penginapan",penginapan.checked?"Y":"N");
f.append("total",total.innerText.replace(/\./g,''));

fetch("",{method:"POST",body:f})
.then(r=>r.text())
.then(m=>{alert(m);location.reload();});
}

function hapus(i){
if(confirm("Hapus data?")){
fetch("?hapus="+i).then(()=>location.reload());
}
}

function tutup(){popup.style.display="none";}
</script>

</body>
</html>
