<?php
session_start();
require '../../koneksi.php';

$meja = htmlspecialchars($_POST['meja']);
$id_order = htmlspecialchars($_POST['id_order']);
$keterangan = htmlspecialchars($_POST['keterangan']);
$user_id = $_SESSION['id_user'];
$tanggal = time();
$tanggal2 = date('d-m-Y');
if ($meja < 1) {
    $_SESSION['pesan'] = '
		<div class="alert alert-warning mb-2 alert-dismissible text-small " role="alert">
			<b>Maaf!</b> Meja belum dipilih
			<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
		</div>
	';
	header('location:../index.php');
    return false;
}
$cekItem = mysqli_query($kon, 
    "SELECT COUNT(*) AS total_item 
    FROM tb_detail_order 
    WHERE id_order = '$id_order' 
    AND status_dorder = 0");
$itemCount = mysqli_fetch_assoc($cekItem);

if($itemCount['total_item'] < 1) {
    $_SESSION['pesan'] = '
        <div class="alert alert-danger mb-2 alert-dismissible text-small" role="alert">
            <b>Gagal!</b> Belum ada item yang dipesan
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    ';
    header('location:../index.php');
    exit();
}
mysqli_query($kon, "UPDATE tb_detail_order set status_dorder = 1 WHERE id_order = '$id_order'");

mysqli_query($kon, "UPDATE tb_meja set status = 1 WHERE meja_id = '$meja'");

$queryTambah = "INSERT INTO tb_order VALUES('$id_order', '$meja', '$tanggal', '$tanggal2', '$user_id', '$keterangan', 0)";
$query = mysqli_query($kon, $queryTambah);

if ($query > 0) {
    $_SESSION['pesan'] = '
		<div class="alert alert-success mb-2 alert-dismissible text-small " role="alert">
			<b>Yoi!</b> Pesanan sedang diproses, mohon tunggu sampai masakan datang
			<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
		</div>
	';
	header('location:../index.php');
} else {
    $_SESSION['pesan'] = '
		<div class="alert alert-danger mb-2 alert-dismissible text-small " role="alert">
			<b>Maaf!</b> Pesanan gagal diproses
			<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
		</div>
	';
	header('location:../index.php');
}
