<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Penerimaan Siswa Baru - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Penerimaan Siswa Baru', '&#128101;', 'Pendataan proses penerimaan calon siswa');

$colors = array('#c2701f','#2f6bb5','#0a8f61','#1896a8','#7a4fb5','#b53f3f','#3f9db5');

menu_panel('all', array(
	array('href'=>'siswa_baru/settingpsb_main.php','label'=>'Konfigurasi Pendataan PSB','desc'=>'Atur konfigurasi penerimaan siswa baru','icon'=>'&#9881;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/pincs.main.php','label'=>'PIN Calon Siswa','desc'=>'Kelola PIN calon siswa','icon'=>'&#128273;','color'=>array_shift($colors)),
	array('href'=>'referensi/tambahandata.php?from=Penerimaan%20Siswa%20Baru','label'=>'Kolom Tambahan Data','desc'=>'Konfigurasi kolom tambahan calon siswa','icon'=>'&#128221;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/proses.php','label'=>'Proses Penerimaan','desc'=>'Pendataan proses penerimaan siswa baru','icon'=>'&#128257;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/kelompok.php','label'=>'Kelompok Penerimaan','desc'=>'Pendataan kelompok penerimaan siswa baru','icon'=>'&#128101;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/calon_main.php','label'=>'Calon Siswa','desc'=>'Pendataan calon siswa','icon'=>'&#129489;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/cari_main.php','label'=>'Pencarian Calon Siswa','desc'=>'Mencari data calon siswa','icon'=>'&#128269;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/penempatan_main.php','label'=>'Penempatan Calon Siswa','desc'=>'Penempatan calon siswa ke kelas','icon'=>'&#128204;','color'=>array_shift($colors)),
	array('href'=>'siswa_baru/statistik_main.php','label'=>'Statistik Calon Siswa','desc'=>'Statistik data calon siswa','icon'=>'&#128202;','color'=>array_shift($colors)),
	array('href'=>'','label'=>'Tahun Ajaran','desc'=>'Kelola tahun ajaran via menu Referensi','icon'=>'&#128197;','color'=>'#8a6a12','alert'=>'Gunakan menu Tahun Ajaran di bagian referensi untuk mendata Tahun Ajaran'),
), 'Semua menu pendataan & pengaturan penerimaan siswa baru dalam satu tempat.');

menu_page_end();
