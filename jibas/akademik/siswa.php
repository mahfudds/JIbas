<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Kesiswaan - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Kesiswaan', '&#128106;', 'Pendataan data siswa &amp; kesiswaan');

$colors = array('#2f6bb5','#0a8f61','#1896a8','#7a4fb5','#b53f3f','#c2701f');

menu_panel('all', array(
	array('href'=>'siswa/siswa_main.php','label'=>'Pendataan Siswa','desc'=>'Kelola data siswa','icon'=>'&#128106;','color'=>array_shift($colors)),
	array('href'=>'siswa/siswa_cari_main.php','label'=>'Pencarian Siswa','desc'=>'Mencari data siswa','icon'=>'&#128269;','color'=>array_shift($colors)),
	array('href'=>'siswa/siswa_pindah_main.php','label'=>'Pindah Kelas','desc'=>'Pendataan siswa yang akan pindah kelas','icon'=>'&#128260;','color'=>array_shift($colors)),
	array('href'=>'siswa/siswa_statistik_main.php','label'=>'Statistik Kesiswaan','desc'=>'Statistik data kesiswaan','icon'=>'&#128202;','color'=>array_shift($colors)),
	array('href'=>'siswa/pin_main.php','label'=>'PIN Siswa','desc'=>'Kelola PIN siswa','icon'=>'&#128273;','color'=>array_shift($colors)),
	array('href'=>'siswa/siswa_import.php','label'=>'Import CSV Siswa','desc'=>'Import data siswa dari file CSV','icon'=>'&#128228;','color'=>array_shift($colors)),
	array('href'=>'referensi/tambahandata.php?from=Kesiswaan','label'=>'Kolom Tambahan Data','desc'=>'Konfigurasi kolom tambahan data siswa','icon'=>'&#128221;','color'=>array_shift($colors)),
	array('href'=>'','label'=>'Tahun Ajaran','desc'=>'Kelola tahun ajaran via menu Referensi','icon'=>'&#128197;','color'=>'#8a6a12','alert'=>'Gunakan menu Tahun Ajaran di bagian referensi untuk mendata Tahun Ajaran'),
), 'Semua menu pendataan &amp; pengaturan kesiswaan.');
menu_page_end();
