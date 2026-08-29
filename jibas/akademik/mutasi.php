<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Mutasi - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Mutasi', '&#128260;', 'Pendataan mutasi siswa');

menu_panel('all', array(
	array('href'=>'mutasi/jenis_mutasi_siswa.php','label'=>'Jenis Mutasi Siswa','desc'=>'Pendataan jenis mutasi siswa','icon'=>'&#128203;','color'=>'#2f6bb5'),
	array('href'=>'mutasi/mutasi_siswa.php','label'=>'Siswa Dimutasi','desc'=>'Pendataan siswa yang akan dimutasi','icon'=>'&#128260;','color'=>'#0a8f61'),
	array('href'=>'mutasi/daftar_mutasi.php','label'=>'Daftar Siswa Mutasi','desc'=>'Daftar siswa yang sudah dimutasi','icon'=>'&#128203;','color'=>'#7a4fb5'),
	array('href'=>'mutasi/statistik_mutasi_siswa.php','label'=>'Statistik Mutasi','desc'=>'Statistik mutasi siswa','icon'=>'&#128202;','color'=>'#b53f3f'),
), 'Pendataan &amp; pelaporan mutasi siswa.');
menu_page_end();
