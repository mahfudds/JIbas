<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Kenaikan & Kelulusan - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Kenaikan &amp; Kelulusan', '&#127891;', 'Pendataan kenaikan kelas, kelulusan &amp; alumni');

menu_panel('all', array(
	array('href'=>'siswa/siswa_kenaikan_main.php','label'=>'Siswa Naik Kelas','desc'=>'Pendataan siswa yang naik kelas','icon'=>'&#128202;','color'=>'#0a8f61'),
	array('href'=>'siswa/siswa_lulus_main.php','label'=>'Kelulusan Siswa','desc'=>'Pendataan kelulusan siswa','icon'=>'&#127891;','color'=>'#2f6bb5'),
	array('href'=>'siswa/siswa_tidak_naik_main.php','label'=>'Siswa Tidak Naik Kelas','desc'=>'Pendataan siswa yang tidak naik kelas','icon'=>'&#128683;','color'=>'#b53f3f'),
	array('href'=>'siswa/alumni_main.php','label'=>'Pendataan Alumni','desc'=>'Tambah, ubah, hapus data alumni','icon'=>'&#128101;','color'=>'#7a4fb5'),
	array('href'=>'siswa/alumni.php','label'=>'Daftar Alumni','desc'=>'Daftar data alumni','icon'=>'&#128203;','color'=>'#1896a8'),
	array('href'=>'siswa/alumni_cari.php','label'=>'Pencarian Alumni','desc'=>'Pencarian data alumni','icon'=>'&#128269;','color'=>'#c2701f'),
	array('href'=>'','label'=>'Departemen / Tahun &amp; Kelas','desc'=>'Kelola data baru via menu Referensi','icon'=>'&#128197;','color'=>'#8a6a12','alert'=>'Untuk mendata Departemen Baru, Tahun Ajaran Baru, Angkatan Baru, dan Kelas Baru, silakan lakukan di Bagian Referensi'),
), 'Pendataan kenaikan kelas, kelulusan &amp; data alumni.');
menu_page_end();
